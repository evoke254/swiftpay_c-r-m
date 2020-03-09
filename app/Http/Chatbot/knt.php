<?php
namespace App\Http\Twilio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;

use App\kntheatre;
use App\kntheatrebookings;
use App\StkpushResponse;


class knt extends Conversation
{

    public function welcome()
    {
        $theatres =  kntheatre::all();
                    $theatre_rooms = "";
                    $counter = 1;
                    foreach ($theatres as $theatre) {
                        $theatre_rooms .=$theatre->id.". *".$theatre->name."*  (".$theatre->capacity." Persons) for Kshs.".$theatre->cost.". \n";
                        $counter++;
                    }
        $this->ask("Jambo 👋 and welcome to *Kenya National Theatre* booking assistant. We will use this chat to book a room for your upcoming event. \n Kindly choose the room that best suites your event:\n".$theatre_rooms , function(Answer $answer) use ($theatres, $theatre_rooms){
                $getRoom = $answer->getText();

                if ($theatres->contains('id', $getRoom)) {
                    $this->getDateTime($getRoom);
                }
                else {
                    return $this->repeat("_".$getRoom."_ is definitely not a valid option. Kindly try again:\n".$theatre_rooms);                  
                }
        });
    }

    public function getDateTime($theatreroomId)
    {
       $theatre =  kntheatre::where('id', $theatreroomId)->first();
//       $this->say("You have selected".$theatre->name." which is a".$theatre->desc. " \n With a capacity of".$theatre->capacity." and costs Kshs. ".$theatre->cost." \n. Please share");
       $this->ask("please share the effective date of reservation for *".$theatre->name."* \n using the format *DD/MM/YYYY* ", function(Answer $answer) use ($theatreroomId, $theatre) {
                $getReservationDate = $answer->getText();
                list($d, $m, $y) = explode('/', $answer->getText());
                if (checkdate($m, $d, $y)) {
                    date_default_timezone_set('Africa/Nairobi');
                    $rsvpDate = $y.'/'.$m.'/'.$d; 
                    $passDate = $d.'-'.$m.'-'.$y;
                    $now = date('Y/m/d');
                    $now2 = date('Y/n/j');
                    if (strtotime($rsvpDate) < strtotime($now) || strtotime($rsvpDate) < strtotime($now2)) {
                      return $this->repeat("_".$getReservationDate."_ is in the past. Let's work with at least today or a future date");  
                    }
                    else {
                         $bookings = kntheatrebookings::where('kntheaters_id', $theatreroomId)
                                                        ->where('status', 'active')
                                                        ->get(); 
                            if ($bookings->isEmpty()) {
                                $initiateBooking = new kntheatrebookings();
                                $initiateBooking->kntheaters_id  = $theatreroomId;
                                $initiateBooking->bookdate_start  = $getReservationDate;
                                $initiateBooking->bookdate_end  = $getReservationDate;
                                $initiateBooking->status  = "Booking Process Initiated";
                                $initiateBooking->save();
                                $booking_id = $initiateBooking->id;
                                $this->confirmBooking($theatre, $booking_id, $passDate);  
                            }
                            else{
                                $this->ask('Sorry we already have a reservation at this hour. This will prompt user with the next available timelines.', function (Answer $answer) {

                                });                      
                            }                    
                    }
                }
                else {
                    return $this->repeat("The system noticed _".$getReservationDate."_ is not a valid date. Please use the format DD/MM/YYYY.");                  
                }
        }); 
    }

    public function confirmBooking($theatre, $booking_id, $getReservationDate)
    {
       $this->ask("You have initiated a booking for ".$theatre->name." at a cost of Kshs. ".$theatre->cost."  on *".date('D, jS M Y', strtotime($getReservationDate))."*\n. Please respond with \n *1. Pay* to checkout via MPESA _other payment gateways can be intergrated here_ \n *2. Quote* to get a quote.", function(Answer $answer) use ($theatre, $booking_id) {

                $payment = $answer->getText();
                if ((strtolower($payment) == 'pay') || ($payment == '1')){
                    $this->pay($theatre, $booking_id);
                }
                elseif ( (strtolower($payment) == 'quote') || ($payment == '2')) {
                    $this->say(' Most coprporates would prefer email and we will intergrate that for you. ');
                }
       });
    }
    public function pay($theatre, $booking_id)
    {
        
        $this->ask('Kindly respond with the MPESA number to receive an STK Push. Accepatble _Number format_ is *2547XXXXXXXX* .' , function(Answer $answer) use ($theatre, $booking_id){
                $mpesaNumber = $answer->getText();
                    if (preg_match('/^[2]{1}[5]{1}[4]{1}[0-9]{9}$/i', $mpesaNumber)){
                        $cost = $theatre->cost;
                        $mpesa =  $this->mpesaSTK($mpesaNumber, $cost, $theatre->name, $booking_id);
                        $this->confirmPayment();

                        $booking = kntheatrebookings::findOrFail($booking_id); 
                        $booking->status = "payment Initiated";
                        $booking->paid_amnt = $cost;
                        $booking->save();
                    }
                    else  {
                        return $this->repeat('Something is not correct with the shared number ( _'.$mpesaNumber.'_  ). Please check on that and try again. NB use format _2547XXXXXXXX_ Safaricom registered number.');
                    }

              });
    }





    public function mpesaSTK($mpesaNumber, $cost, $theatreName, $booking_id)
        {
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $whatsapp_no = $this->bot->getUser();
        $whatsapp_number = $whatsapp_no->getId();


        $BusinessShortCode = '767219';
        $LipaNaMpesaPasskey = '2f17ec5bd74084eb21121d75ec6f69f49e3b02d493747fd03f25740f74f57849';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $cost;
        $PartyA = $mpesaNumber;
        $PartyB = $BusinessShortCode;
        $PhoneNumber = $mpesaNumber;
        $CallBackURL = 'https://app.swiftpayafrica.com/kntMPESA';
        $AccountReference = $theatreName;
        $TransactionDesc = Str::limit($theatreName, 196);
        $Remarks = 'Thank you for using SwiftPay Africa.';

        $trans_push =$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

        $stkresponse = json_decode($trans_push);
        $stkpushResponse = new StkpushResponse();

        $stkpushResponse->PartyA = $PartyA;
        $stkpushResponse->PartyB = $PartyB;
        $stkpushResponse->Amount = $Amount;
        $stkpushResponse->TransactionType = $TransactionType;
        if ( array_key_exists('errorMessage', $stkresponse) ) {
                $stkpushResponse->errorCode = $stkresponse->errorCode;
                $stkpushResponse->errorMessage = $stkresponse->errorMessage;
                $stkpushResponse->CustomerMessage  = $stkresponse->requestId;
        } else{
        $stkpushResponse->MerchantRequestID = $stkresponse->MerchantRequestID;
        $stkpushResponse->CheckoutRequestID = $stkresponse->CheckoutRequestID;
        $stkpushResponse->ResponseCode = $stkresponse->ResponseCode;
        $stkpushResponse->ResponseDescription = $stkresponse->ResponseDescription;
        $stkpushResponse->CustomerMessage  = $stkresponse->CustomerMessage;
         }


        $stkpushResponse->booking_id = $booking_id;
        //change $whatsapp_number to a suitable variable
        $stkpushResponse->chatuser_id = $whatsapp_number;  
        $stkpushResponse->save(); 
        return $stkpushResponse->id;
        }






    public function confirmPayment()
    {
        $this->say('Thank you. I will take several seconds break as we wait for MPESA confirmation. Chat soon.');
    }


    public function run()
    {
        // This will be called immediately
        $this->welcome();
    }






















}