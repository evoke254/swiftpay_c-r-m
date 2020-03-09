<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
      protected $fillable = [
    	"Contact_Name",
 		"Account_Number",
	 	"Mobile",
        "Organization",
	 	"Location",
	 	"Phone",
	 	"Email_1",
	 	"Email_2",
	 	"Address",
	 	"Description",
	 	"Assigned_To",
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'Assigned_To');
    }
    public function getOrganization()
    {
        return $this->belongsTo('App\Organization', 'Organization');
    }

    public function getQuotes()
    {
    	return $this->hasMany('App\Quotes', 'Client', 'id');
    }

    public function getInvoices()
    {
        return $this->hasMany('App\Invoices', 'Client', 'id');
    }

}
