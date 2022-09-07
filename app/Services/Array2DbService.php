<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class Array2DbService
{
   public function sendArray2Db(array $data)
   {
        foreach ($data['offers'] as $offer) {
            $this->sendOffer($offer);
        }
   }

   private function sendOffer(array $offer)
   {
       foreach ($offer as $concreteCar) {
           print_r($concreteCar);
       }
   }
}
