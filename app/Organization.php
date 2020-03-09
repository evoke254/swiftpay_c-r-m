<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    
  protected $fillable = [
    	"Organization_Name",
 		"Account_Number",
	 	"Mobile",
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

     public function getQuotes()
    {
    	return $this->hasMany('App\Quotes', 'Organization', 'id');
    }

    public function getInvoices()
    {
        return $this->hasMany('App\Invoices', 'Organization', 'id');
    }
    
    public function getContact()
    {
        return $this->hasMany('App\Contacts', 'Organization', 'id');
    }

   
}
