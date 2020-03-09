<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pointofsale extends Model
{
    protected $fillable = [
 			"Subject",
            "Amount",
            "Assigned_To",
            "Organization",
            "Client",
            "Description",
            "products",
            "Transaction",
            "Status",
            "Pay_Via"
    ];

    public function getUser()
    {
        return $this->belongsTo('App\User', 'Assigned_To');
    }
}
