<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "car_models";
    protected $primaryKey = "id";
    protected $fillable = ['name','carMakeID','createdBy','dateModified','dateCreated'];
    public $timestamps= false;

    public function carMake()
    {
        return $this->belongsTo(CarMake::class,'carMakeID','id');
    }
}
