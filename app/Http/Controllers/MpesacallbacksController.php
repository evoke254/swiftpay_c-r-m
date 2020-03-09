<?php

namespace App\Http\Controllers;

use App\Mpesacallbacks;
use App\Pointofsale;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\PosStockUpdateEvent;
class MpesacallbacksController extends Controller
{


    public function index()
    {
        //
    }



    public function create()
    {
        //
    }

    public function posmpesa(Request $request)
    {
        //
    }


    public function show(Mpesacallbacks $mpesacallbacks)
    {
        $mpesaResponse = json_decode($request->getContent());
        $mpesaCall = $this->mpesaCallbacks($mpesaResponse);
        
        $getTransaction = Mpesastk::where('CheckoutRequestID', $mpesaCall->CheckoutRequestID)->first();
        $PointofsaleId = $getTransaction->id;
        $pos = Pointofsale::findorFail($PointofsaleId);

        if ($mpesaCall->resultdcode == '0') {  
            if((int)$mpesaCall->amount == $pos->Amount)
            $pos->Reference = $mpesaCall->receipt;
            $pos->Status = 'MPESA received';
            $pos->save();
            event(new PosStockUpdateEvent($posTransaction));
        }
        else



    }


    public function edit(Mpesacallbacks $mpesacallbacks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mpesacallbacks  $mpesacallbacks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mpesacallbacks $mpesacallbacks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mpesacallbacks  $mpesacallbacks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mpesacallbacks $mpesacallbacks)
    {
        //
    }
}
