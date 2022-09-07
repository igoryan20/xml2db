<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcreteCarCharacteristic extends Model
{
    use HasFactory;

    public $table = 'concrete_car_characteristics';

    public function model()
    {
        return $this->belongsTo(CarModel::class, null, 'model_id');
    }

    public function generation()
    {
        return $this->belongsTo(CarGeneration::class, null, 'generation_id');
    }
}
