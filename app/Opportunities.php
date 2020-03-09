<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunities extends Model
{
    //
    protected $fillable = [
    	"Opportunity_Name",
 		"Contract_in_months",
	 	"Monthly_Cost",
	 	"Total_Revenue",
	 	"Expected_Close_Date",
	 	"Probability_as_a_percentage",
	 	"Lead_Source",
	 	"Organization",
	 	"Sales_Stage",
        "Client_Name",
	  	"Assigned_To",
		"Description",
		"Product",
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'Assigned_To');
    }

    public function getQuotes()
    {
    	return $this->hasMany('App\Quotes', 'Opportunity', 'id');
    }

    public function getInvoices()
    {
        return $this->hasMany('App\Invoices', 'Opportunity', 'id');
    }
}
