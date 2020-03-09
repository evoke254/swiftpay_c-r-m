<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chats extends Model
{
        protected $fillable = [
    	"message",
    	"module_name",
    	"object_id",
    	"User_id"
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'User_id');
    }

}
