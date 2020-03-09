<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mpesacallbacks extends Model
{
    //
    protected $fillable = [
    		"amount",
    		"number",
    		"resultCode",
    		"merchantID",
    		"checkoutID",
    		"receipt",
    		"datetime",
    		"resultdesc",
    		"resultdcode",
    ];

    public function mpesaStk()
    {
    	return $this->belongsTo('App\Mpesastk', 'merchantID');
    }
}
