<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\History;
use App\Mpesastk;
use App\Mpesacallbacks;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function getColumnHeader($modelName)
    {
    	$columns = Schema::getColumnListing($modelName);
    	$columns = array_diff($columns, ["id", "created_at", "updated_at"]);
    	foreach ($columns as $key => $column) {
    		$column_type = Schema::getColumnType($modelName, $column);
    		$column_name = str_replace("_", " ", $column);
    		$viewColumn[] = $column_name;
            $DBcolumn[$column] = array(Schema::getColumnType($modelName, $column) => $column_name);
    	}
        return $DBcolumn;
    }
    public function log_history($historyPayload)
    {
        History::create($historyPayload);
    }



    public function MpesaPushStk($mpesaNumber, $bill, $pos_id)
        {
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $BusinessShortCode = '181195';
        $LipaNaMpesaPasskey = 'c105ce97905337aba0176e06982a2eacd7da226beac4f0b2fc930d033934bf46';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = ceil($bill);
        $PartyA = $mpesaNumber;
        $PartyB = $BusinessShortCode;
        $PhoneNumber = $mpesaNumber;
        $CallBackURL = env('MPESA_CALLBACK');
        $AccountReference = 'Pay Bill';
        $TransactionDesc = Str::limit('Pay Bill', 196);
        $Remarks = 'Thank you for using SwiftPay Africa.';

        $trans_push =$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

        $stkresponse = json_decode($trans_push);
        $stkpushResponse = new Mpesastk();

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
        $stkpushResponse->pointofsales = $pos_id;  
        $stkpushResponse->save(); 
        return $stkpushResponse;

    }

    public function mpesaCallbacks($Mpesapayload)
    {
        $ResultCode = $mpesaResponse->Body->stkCallback->ResultCode;
        $CheckoutRequestID = $mpesaResponse->Body->stkCallback->CheckoutRequestID;
        $CheckoutRequestIDs = Mpesastk::pluck('CheckoutRequestID')->toArray(); 

        if (!in_array($CheckoutRequestID, $CheckoutRequestIDs)) {
            die('We dont do that here.');
        }
        if ($ResultCode == 0) {
            $Amount = $mpesaResponse->Body->stkCallback->CallbackMetadata->Item[0]->Value;
            $MpesaReceiptNumber = $mpesaResponse->Body->stkCallback->CallbackMetadata->Item[1]->Value;
            $TransactionDate = $mpesaResponse->Body->stkCallback->CallbackMetadata->Item[3]->Value;
            $PhoneNumber = $mpesaResponse->Body->stkCallback->CallbackMetadata->Item[4]->Value;
        }
        $MerchantRequestID = $mpesaResponse->Body->stkCallback->MerchantRequestID;
        $CheckoutRequestID = $mpesaResponse->Body->stkCallback->CheckoutRequestID;
        $ResultDesc = $mpesaResponse->Body->stkCallback->ResultDesc;

        $mpesaCall = new Mpesacallbacks;
        $mpesaCall->resultdcode = $ResultCode;
         if ($ResultCode == 0) {
            $mpesaCall->amount = $Amount;
            $mpesaCall->receipt = $MpesaReceiptNumber;
            $mpesaCall->datetime = $TransactionDate;
            $mpesaCall->number = $PhoneNumber;       
        }
        $mpesaCall->merchantID = $MerchantRequestID;
        $mpesaCall->checkoutID = $CheckoutRequestID;
        $mpesaCall->resultdesc = $ResultDesc;
        $mpesaCall->save();

        return $mpesaCall;
    }





}
