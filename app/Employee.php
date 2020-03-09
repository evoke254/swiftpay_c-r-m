<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
    	"First_Name",
    	"Second_Name",
    	"Department",
    	"Mobile",
    	"Alternate_Mobile",
    	"Email",
    	"Alternate_Email",
        "Image",
        "Contract_End",
        "Contract_Start",
        "Residence",

    ];

    public function getUser()
    {
        return $this->belongsTo('App\User', 'User');
    }

}
