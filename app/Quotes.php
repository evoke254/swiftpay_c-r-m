<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    protected $fillable = [
    	"Quote_Subject",
    	"Valid_To",
    	"Contact_Name",
    	"Amount",
    	"Organization",
        "Client",
        "Invoice",
    	"Quote_Stage",
    	"Assigned_To",
    	"Description",
    	"Opportunity",
    	"products",
    	"productDetails"
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

    public function getInvoices()
    {
        return $this->hasOne('App\Invoices', 'Quote');
    }
    

}
