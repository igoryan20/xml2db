<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarModel extends Model
{
    use HasFactory;

    public $table = 'car_models';

    protected $primaryKey = 'model_id';

    public function generations(): HasMany
    {
        return $this->hasMany(CarGeneration::class);
    }

    public function concreteCarCharacteristics(): HasMany
    {
        return $this->hasMany(CarOffer::class);
    }
}
