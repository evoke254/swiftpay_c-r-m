<?php

namespace App\Listeners;

use App\Events\StockUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Products;
use App\Transaction;
use App\Invoices;

class StockUpdateEventListener
{

    public function __construct()
    {
        //
    }

    public function handle(StockUpdateEvent $event)
    {
        $invoicedProducts = $event->invoicedProducts->products;
        $products = json_decode($invoicedProducts);

        foreach ($products as $product) {

            
            $productId = (int)$product->product->id;
            $getprdctModel = Products::find($productId);
            $orderedQnty = (int)$product->quantity;


            if (isset($product->prevQuantity)) {   
                $prevQnty = (int)$product->prevQuantity;
                $Units_Sold = (((int)$getprdctModel->Units_Sold - $prevQnty) + $orderedQnty);
                $Available_Stock = (((int)$getprdctModel->Available_Stock + $prevQnty) - $Units_Sold);
                $Actual_Stock = (((int)$getprdctModel->Actual_Stock + $prevQnty) - $Units_Sold);

                $this->updateTransaction($event->invoicedProducts);
            } else {
                $this->createTransaction($event->invoicedProducts);

                $Units_Sold = ((int)$getprdctModel->Units_Sold + $orderedQnty);
                $Available_Stock = ((int)$getprdctModel->Available_Stock - $Units_Sold);
                $Actual_Stock = ((int)$getprdctModel->Actual_Stock - $Units_Sold);

            }


            $getprdctModel->Units_Sold = $Units_Sold;
            $getprdctModel->Available_Stock = $Available_Stock;
            $getprdctModel->Actual_Stock = $Actual_Stock;
            $getprdctModel->save();

        }
    }
    public function createTransaction($invoice)
    {        
            

            $transaction = new Transaction;
            $transaction->Transaction_Name = $invoice->Invoice_Subject;
            $transaction->Debit = $invoice->Amount;
            $transaction->Organization = $invoice->Organization;
            $transaction->Client = $invoice->Client;
            $transaction->invoice = $invoice->id;
            $transaction->Status = 'Paid';
            $transaction->Assigned_To = $invoice->Assigned_To;
            $transaction->Code = $this->transCode();
            $transaction->save();

            $invoice = Invoices::find($invoice->id);
            $invoice->Transaction = $transaction->id;
            $invoice->save();
    }

public function updateTransaction($invoice)
    {        
            $transaction = Transaction::find($invoice->Transaction);
            $transaction->Status = 'Cancelled';
            $transaction->Credit = $transaction->Debit;
            $transaction->Debit = 0;
            $transaction->save();

            $transaction = new Transaction;
            $transaction->Transaction_Name = $invoice->Invoice_Subject;
            $transaction->Debit = $invoice->Amount;
            $transaction->Organization = $invoice->Organization;
            $transaction->Client = $invoice->Client;
            $transaction->invoice = $invoice->id;
            $transaction->Status = 'Paid';
            $transaction->Assigned_To = $invoice->Assigned_To;
            $transaction->Code = $this->transCode();
            $transaction->save();

            $invoice = Invoices::find($invoice->id);
            $invoice->Transaction = $transaction->id;
            $invoice->save();

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
