<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable= [
    		"Partner_Name",
    		"Debit",
    		"Credit",
    		"Code",
    		"Status",
    		"Partner_Id",
    		"Invoice",
    		"POS",
    		"Assigned_To",
    		"Reference"
    	];
    
    public function getInvoice()
    {
        return $this->belongsTo('App\Invoices', 'Transaction');
    }
    
    public function getUser()
    {
        return $this->belongsTo('App\User', 'Assigned_To');
    }

    public function getClient()
    {
        return $this->belongsTo('App\Contacts', 'Client');
    }

    public function getOpportunity()
    {
        return $this->belongsTo('App\Opportunities', 'Opportunity');
    }
}
