<?php

namespace App\Http\Controllers;

use App\Pointofsale;
use App\History;
use App\Products;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\PosStockUpdateEvent;

class PointofsaleController extends Controller
{
    private $moduleName = 'pointofsales';
    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }

    public function index()
    {

        $products = Products::all('id', 'Product_Name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->Product_Name;
                         return $arr;
                     })->toArray();
        $Allproducts = Products::all();

        return view('pos', compact('DBcolumn', "moduleName", "products", "Allproducts"));
    }


    public function orders(Request $request)
    {
        $transaction = $request->all();

        if ($request->input('Status') == 'Pending') {
            $transaction = $request->all();
            $this->savePosTrans($transaction);
        }
        elseif ($request->input('Pay') == 'Pay') {

           if($request->input('payVia') == 'MPESA'){
                $transaction = $request->all();
                $transaction['Status'] = 'Mpesa Initiated';
                $transaction['Reference'] = 'Waiting for MPESA';
                $posTransaction = $this->savePosTrans($transaction);
                $mpesaNumber = '254'.$request->input('mpesaNumber');
                $Amount = $request->input('Amount');

                $mpesaResp = $this->MpesaPushStk($mpesaNumber, $Amount, $posTransaction->id);
                    if ($mpesaResp->ResponseCode != '0') {
                        return response()->json(['Error'=> $mpesaResp->errorMessage]);
                    } else {
                        return response()->json(['Success'=> $mpesaResp->ResponseDescription]);
                    }

           } elseif ($request->input('payVia') == 'CASH') {
                $transaction = $request->all();
                $transaction['Status'] = 'Cash Received';
                $transaction['Status'] = 'CASH';
                $posTransaction = $this->savePosTrans($transaction);
                event(new PosStockUpdateEvent($posTransaction));
                return response()->json(['Success'=> 'Cash Received']);
           }
           else {
            return response()->json(['Error'=> 'Payment Option not integrated']);
           }
        }



                return response($this->moduleName);
    }

    public function savePosTrans($transaction)
    {
        $posOrder = [
            'Subject'=> Auth::user()->name,
            'Amount' => $transaction['Amount'],
            'Assigned_To' => Auth::id(),
            'Reference' => '',
            'Pay_Via' => $transaction['payVia'],
            'Status' => $transaction['Status'],
        ];
                //Table doesnt have this columns thus unset them
        unset($transaction['mpesaNumber'], $transaction['Pay']);

        $products = json_encode($transaction['products']); 
        unset($transaction['products']);
        $insert = Pointofsale::create($posOrder);
        if (!$insert) {
                return response()->json(['Error'=> 'Transaction Not Saved. Call Kahaki on 0742968713']);
            } else {

                $update = Pointofsale::findorFail($insert->id);
                $update->products = $products;
                $update->save();
                $user_id = Auth::id();
                $moduleName = $this->moduleName; 
                $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $insert->id, 'operation' => 'Created', 'refModule');
                $this->log_history($historyPayload);

                return $update;
        }
    }
    public function pendingBills(Request $request)
    {

        $Bills = User::with(['getPos' => function($query){
                $query->where('Status', 'Pending');
                $query->orWhere('Status', 'Mpesa Initiated');
                $query->orWhere('Status', 'Card Initiated');
                }])
                ->get()
                ->toArray();
        $whichBill = "Pending Bills";
        return view('Bills', compact("Bills", "whichBill"));
    }
    public function clearedBills()
    {
    $Bills = User::with(['getPos' => function($query){
                $query->where('Status', 'Cash Received');
                $query->orWhere('Status', 'MPESA Received');
                $query->orWhere('Status', 'CARD Received');
                }])
                ->get()
                ->toArray();
        $whichBill = "Cleared Bills";
        return view('Bills', compact("Bills", "whichBill"));
    }




    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Pointofsale $pointofsale)
    {
        event(new PosStockUpdateEvent($pointofsale));
    }


    public function edit(Pointofsale $pointofsale)
    {
        //
    }


    public function update(Request $request, Pointofsale $pointofsale)
    {
        //
    }


    public function destroy(Pointofsale $pointofsale)
    {
        //
    }
}
