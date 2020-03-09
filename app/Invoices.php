<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
      protected $fillable = [
    	"Invoice_Subject",
    	"Amount",
    	"Organization",
        "Client",
        "Quote",
    	"Quote_Stage",
    	"Assigned_To",
    	"Description",
    	"Opportunity",
    	"products",
    	"productDetails",
        "Transaction",
    ];
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

    public function getQuotes()
    {
        return $this->hasOne('App\Quotes', 'Invoice');
    }

    
}
