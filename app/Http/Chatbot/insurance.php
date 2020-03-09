<?php
namespace App\Http\Twilio;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;

use App\Insurance;
use App\InsuranceOrg;
use App\InsuranceServices;
use App\StkpushResponse;


class insurance extends Conversation
{

    public function welcome()
    {
        $insuranceCompanies =  InsuranceOrg::all();
                    $insurance_companies = "";
                    $counter = 1;
                    foreach ($insuranceCompanies as $insuranceCompany) {
                        $insurance_companies .=$insuranceCompany->id.". *".$insuranceCompany->name."* 👉🏾 ".$insuranceCompany->desc.". \n";
                        $counter++;
                    }
        $this->ask("Jambo 👋 and welcome to *Leadway Group* quick assistant. We are breaking the insurance silo and flattening the process. \n Kindly choose a company to proceed:\n".$insurance_companies , function(Answer $answer) use ($insuranceCompanies){
                $companyId = $answer->getText();

                if ($insuranceCompanies->contains('id', $companyId)) {
                    $this->getCompanyServices($companyId);
                }
                else {
                    return $this->repeat("_".$companyId."_ is not a valid option. Kindly try again:\n".$insurance_companies);                  
                }
        });
    }

    public function getCompanyServices($companyId)
    {
        $services = InsuranceOrg::findOrFail($companyId)->getCompanyServices;
        $company = InsuranceOrg::findOrFail($companyId);

        $services = "";
        $counter = 1;
        foreach ($services as $service) {
                        $services .=$service->id.". *".$service->name."* 👉🏾 ".$service->desc.". \n";
                        $counter++;
        }

        $this->ask("*".$company->name."* has the following products 👇. What are you interested in today? \n ".$services , function(Answer $answer) use ($services, $company){
                $serviceId = $answer->getText();

                if ($services->contains('id', $serviceId)) {
                    $this->getSupportChannel($serviceId, $company);
                }
                else {
                    return $this->repeat("_".$serviceId."_ is not a valid option. Kindly try again:\n".$services);                  
                }
        });
    }

    public function getSupportChannel($serviceId, $company)
    {
        $service = InsuranceServices::findOrFail($serviceId);


        $this->ask("How would you like us to help you with *".$service->name."* \n *1. Insure your phone* \n *2. Claims* \n *3. Renew Policy* \n *4. Retrieve agent infomation* \n *5. Pending Document follow-up*".$services , function(Answer $answer) use ($service, $company){
                $supportChannel = $answer->getText();
                $supportChannel = "Phone Insurance";
                $this->quotation($service, $company, $supportChannel);
        });
    }

    public function quotation($service, $company, $supportChannel)
    {

     $this->ask("Please reply with a 15 digit IMEI number. You can access this by dialing _*#06#_ " , function(Answer $answer) use ($service, $company, $supportChannel){
                $imei = $answer->getText();

                $this->purchaseProof($service, $company, $imei, $supportChannel);
        });   
    }

    public function purchaseProof($service, $company, $imei, $supportChannel)
    {
        $this->ask("The system has detected you have an *Huawei Y6 2017 [MYA-LII]*. Kindly share proof of purchase. \n We accept *Images* and *PDF* only.\n \n *Respond with anything to proceed* \n_Validation and document scanning will be activated for this_ " , function(Answer $answer) use ($service, $company, $imei, $supportChannel){

                $this->selectInsurer($service, $company, $imei, $supportChannel);
        }); 
    }
    public function selectInsurer($service, $company, $imei, $supportChannel)
    {

     $this->ask("Kindly select your preffered insurer. \n 1. Kenya Orient \n 2. Jubilee Insurance \n 3. UAP-Old Mutual \n 4. Takaful Insurance. \n  Kenya Orient picked by default \n *Respond with anything to proceed*" , function(Answer $answer) use ($service, $company, $imei, $supportChannel){
                $insurer = "Kenya Orient";
                $this->phoneValue($service, $company, $imei, $supportChannel, $insurer);
        });   
    }

    public function phoneValue($service, $company, $imei, $supportChannel, $insurer)
    {
     
     $this->ask("What is the value of your gadget? \n Please respond with a number (Eg: *30000* )" , function(Answer $answer) use ($service, $company, $imei, $supportChannel, $insurer){
                $estValue = $answer->getText();
                if ( preg_match('/^\d+$/', $estValue)) {
                    $this->compute_premium($service, $company, $imei, $supportChannel, $insurer, $estValue);
                } 
                else {
                    return $this->repeat("_".$estValue."_ includes either a space(s), letter or both. Please respond with a number (Example: *30000* )");                  
                } 
        });   
    }
    public function compute_premium($service, $company, $imei, $supportChannel, $insurer, $estValue)
    {
        $premium = $estValue * 0.1;
        $taxes = 0.045($estValue * 0.04);
        $premium_sum = $premium + $taxes;


        $this->ask("Your premium for \n *Huawei Y6 2017 [MYA-LII]* \n IMEI : *".$imei."* \n is *".$premium_sum."* . \n Kindly respond with the MPESA number to receive an STK Push. Accepatble _Number format_ is *2547XXXXXXXX* ." , function(Answer $answer) use ($service, $company, $imei, $premium_sum){
                $mpesaNumber = $answer->getText();
                    if (preg_match('/^[2]{1}[5]{1}[4]{1}[0-9]{9}$/i', $mpesaNumber)){
                        $model = "Huawei Y6 2017 [MYA-LII]";
                        $policy_id = $this->createPolicy($service, $company, $imei, $supportChannel, $insurer, $premium_sum, $model);

                        $mpesa =  $this->mpesaSTK($mpesaNumber, $premium_sum, $model, $policy_id);
                        $this->confirmPayment();
                    }
                    else  {
                        return $this->repeat('Something is not correct with the shared number ( _'.$mpesaNumber.'_  ). Please check on that and try again. NB use format _2547XXXXXXXX_ Safaricom registered number.');
                    } 
        });

    }
    public function createPolicy($service, $company, $imei, $supportChannel, $insurer, $premium_sum, $model)
    {
        $whatsapp_no = $this->bot->getUser();
        $whatsapp_number = $whatsapp_no->getId();
        $now = date('Y-m-d H:i:s');
        $generatePolicy = new Insurance();
        $generatePolicy->name =  "John Doe";
        $generatePolicy->phone =  $whatsapp_number;
        $generatePolicy->model =  $model;
        $generatePolicy->policy_number =  $this->policyNumberGenerator();
        $generatePolicy->status =  'Initiated awaiting Payment';
        $generatePolicy->policy_start =  $now;
        $generatePolicy->policy_end =  date('Y-m-d H:i:s', strtotime('+12 months', strtotime($now)));
        $generatePolicy->unique_id =  $imei;
        $generatePolicy->insurance_services_id =  $service->id;
        $generatePolicy->premium_amnt =  $premium_sum;
        $generatePolicy->insurer = $insurer;
        $generatePolicy->save();
        $policy_id = $generatePolicy->id;
        return $policy_id;
    }





    public function policyNumberGenerator()
        {            
            $rand_str1 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
            $rand_str2 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 2));
            $rand_int = mt_rand(100, 10000);

            $rand_code = $rand_str1.'/'.$rand_int.'/'.$rand_str2;
            while (DB::table('digiassurances')->where('policy_no', $rand_code)->exists()) {
                    $rand_str1 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
                    $rand_str2 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 2));
                    $rand_int = mt_rand(100, 10000);
                    $rand_code = $rand_str1.'/'.$rand_int.'/'.$rand_str2;
            }
            return $rand_code;
        }


    public function mpesaSTK($mpesaNumber, $premium_sum, $model, $policy_id)
        {
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $whatsapp_no = $this->bot->getUser();
        $whatsapp_number = $whatsapp_no->getId();


        $BusinessShortCode = '767219';
        $LipaNaMpesaPasskey = '2f17ec5bd74084eb21121d75ec6f69f49e3b02d493747fd03f25740f74f57849';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $premium_sum;
        $PartyA = $mpesaNumber;
        $PartyB = $BusinessShortCode;
        $PhoneNumber = $mpesaNumber;
        $CallBackURL = 'https://app.swiftpayafrica.com/digiassuranceMPESA';
        $AccountReference = $model;
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


        $stkpushResponse->booking_id = $policy_id;
        //change $whatsapp_number to a suitable variable
        $stkpushResponse->chatuser_id = $whatsapp_number;  
        $stkpushResponse->save(); 
        return $stkpushResponse->id;
        }






    public function confirmPayment()
    {
        $this->say("*Payment succesfully initiated*. 💳📲 \n I will revert with an update shortly.");
    }


    public function run()
    {
        // This will be called immediately
        $this->welcome();
    }






















}