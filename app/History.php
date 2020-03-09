<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
    	"moduleName",
        "refModule",
    	"column",
    	"prev_value",
    	"updated_value",
    	"user_id",
    	"operation",
    	"object_id"
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
    /*one for each module*/
    public function getOpportunities()
    {
    	return $this->belongsTo('App\Opportunities', 'object_id');
    }

    public function getProducts()
    {
        return $this->belongsTo('App\Products', 'object_id');
    }

    public function getQuotes()
    {
        return $this->belongsTo('App\Quotes', 'object_id');
    }
        public function getInvoices()
    {
        return $this->belongsTo('App\Invoices', 'object_id');
    }


        public function getOrganization()
    {
        return $this->belongsTo('App\Organization', 'object_id');
    }
        public function getContacts()
    {
        return $this->belongsTo('App\Contacts', 'object_id');
    }
    

    public function getEmployee()
    {
        return $this->belongsTo('App\Contacts', 'object_id');
    }
}
