<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class calendar extends Model
{
    protected $fillable = [
    	"title",
    	"location",
    	"object_id",
    	"User_id",
    	"attendees",
    	"start",
    	"end",
    	"details",
    	"module_name",
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'User_id');
    }
}
