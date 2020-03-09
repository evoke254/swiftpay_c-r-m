<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
    	"Product_Name",
 		"Selling_Price",
	 	"Buying_Price",
	 	"Commission",
	 	"Image",
	 	"Assigned_To",
	 	"Organization",
	 	"Sales_Stage",
	  	"Assigned_To",
		"Description",
		"Units_Sold",
		"Available_Stock",
		"Actual_Stock",
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User', 'Assigned_To');
    }





    protected static function boot()
    {
        parent::boot();
        Products::creating(function ($model) {
        	if(empty($model->Actual_Stock)){
           		$model->Actual_Stock = 0;
           		$model->Available_Stock = 0;
        	}
        	else {
            	$model->Available_Stock = $model->Actual_Stock;
        	}
	            $model->Units_Sold = 0;
        });
    
    Products::saving(function ($model) {
            if(empty($model->Actual_Stock)){
                $model->Actual_Stock = 0;
                $model->Available_Stock = 0;
            }
            else {
                $model->Available_Stock = $model->Actual_Stock;
            }
        });
    }
 
}
