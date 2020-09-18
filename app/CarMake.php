<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "car_makes";
    protected $primaryKey = "id";
    protected $fillable = ['name','createdBy','dateModified','dateCreated'];
    public $timestamps= false;

    public function carModel() {
        return $this->hasMany(CarModel::class, 'carMakeID', 'id');
    }
}
