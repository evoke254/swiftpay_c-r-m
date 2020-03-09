<?php

namespace App\Listeners;

use App\Products;
use App\Transaction;
use App\Pointofsale;
use App\Events\PosStockUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PosStockUpdateEventListener
{

    public function __construct()
    {
        //
    }


    public function handle(PosStockUpdateEvent $event)
    {
        $posOrder = $event->posOrder->products;
        $products = json_decode($posOrder);

        foreach ($products as $product) {

            
            $productId = (int)$product->product->id;
            $getprdctModel = Products::find($productId);
            $orderedQnty = (int)$product->quantity;

            $this->createTransaction($event->posOrder);

            $Units_Sold = ((int)$getprdctModel->Units_Sold + $orderedQnty);
            $Available_Stock = ((int)$getprdctModel->Available_Stock - $Units_Sold);
            $Actual_Stock = ((int)$getprdctModel->Actual_Stock - $Units_Sold);

            $getprdctModel->Units_Sold = $Units_Sold;
            $getprdctModel->Available_Stock = $Available_Stock;
            $getprdctModel->Actual_Stock = $Actual_Stock;
            $getprdctModel->save();

        }
    }

    public function createTransaction($posOrder)
    {        
            

            $transaction = new Transaction;
            $transaction->Transaction_Name = $posOrder->Subject.'-'.$posOrder->Amount;
            $transaction->Debit = $posOrder->Amount;
            $transaction->Organization = $posOrder->Organization;
            $transaction->Client = $posOrder->Client;
            $transaction->POS = $posOrder->id;
            $transaction->Reference = $posOrder->Reference;
            $transaction->Status = 'Paid';
            $transaction->Assigned_To = $posOrder->Assigned_To;
            $transaction->Code = $this->transCode();
            $transaction->save();

            $Pointofsale = Pointofsale::find($posOrder->id);
            $Pointofsale->Transaction = $transaction->id;
            $Pointofsale->save();
    }

    public function transCode()
    {            
        $rand_str1 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
        $rand_str2 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 3));
        $rand_int = mt_rand(10, 100);

        $rand_code = $rand_str1.$rand_str2.$rand_int;
        while (Transaction::where('Code', $rand_code)->exists()) {
                $rand_str1 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
                $rand_str2 = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 3));
                $rand_int = mt_rand(10, 100);
                $rand_code = $rand_str1.$rand_str2.$rand_int;
        }
        return $rand_code;
    }

}
