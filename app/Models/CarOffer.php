<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarOffer extends Model
{
    use HasFactory;

    public $table = 'car_offers';

    protected $primaryKey = 'offer_id';
}
