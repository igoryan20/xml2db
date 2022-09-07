<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ConcreteCarCharacteristic;
use App\Models\CarGeneration;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Array2DbService
{
    private $allCars;
    private array $updatedCarsId;

    public function sendArray2Db(array $data)
    {
        foreach ($data['offers'] as $offer) {
            $this->sendOffer($offer);
        }
    }

    private function sendOffer(array $offer)
    {
        $this->allCars = ConcreteCarCharacteristic::all();
        foreach ($offer as $concreteCar) {
            $this->sendConcreteCar($concreteCar);
        }
        $this->deleteNotUpdatedCars();
    }

    private function sendConcreteCar(array $car)
    {
        $concreteCar = ConcreteCarCharacteristic::where('concrete_car_id', $car['id'])->first();
        if ($concreteCar) {
            $this->updatedCarsId[] = $car['id'];
            $this->updateTables($car, $concreteCar);
        } else {
            $this->addToTables($car);
        }
    }

    private function updateTables(array $car, $concreteCar)
    {
        DB::transaction(function () use ($car, $concreteCar) {

            $concreteCar->year = $car['year'];
            $concreteCar->run = $car['run'];
            $concreteCar->color = $car['color'];
            $concreteCar->body_type = $car['body-type'];
            $concreteCar->engine_type = $car['engine-type'];
            $concreteCar->transmission = $car['transmission'];
            $concreteCar->gear_type = $car['gear-type'];
            $concreteCar->generation_id = $car['generation_id'];

            $carModel = $concreteCar->model;
            $carModel->mark = $car['mark'];
            $carModel->model = $car['model'];

            $carModel->save();

            if ($concreteCar->generation_id) {
                $carGeneration = $concreteCar->generation;
                $carGeneration->generation_id = $car['generation_id'];
                $carGeneration->generation = $car['generation'];

                $carGeneration->save();
            }


            $concreteCar->save();
        });
    }

    private function addToTables(array $car)
    {
        print_r($car);

        DB::transaction(function () use ($car) {
            $carModel = new CarModel;
            $carModel->mark = $car['mark'];
            $carModel->model = $car['model'];

            $carModel->save();

            $concreteCar = new ConcreteCarCharacteristic;

            if ($car['generation_id']) {
                $carGeneration = new CarGeneration;
                $carGeneration->generation_id = $car['generation_id'];
                $carGeneration->generation = $car['generation'];
                $carGeneration->car_model_id = $carModel->id;

                $carGeneration->save();

                $concreteCar->generation_id = ($car['generation_id']);
            }


            $concreteCar->concrete_car_id = $car['id'];
            $concreteCar->year = $car['year'];
            $concreteCar->run = $car['run'];
            $concreteCar->color = $car['color'];
            $concreteCar->body_type = $car['body-type'];
            $concreteCar->engine_type = $car['engine-type'];
            $concreteCar->transmission = $car['transmission'];
            $concreteCar->gear_type = $car['gear-type'];
            $concreteCar->model_id = $carModel->id;

            $concreteCar->save();
        });
    }

    private function deleteNotUpdatedCars()
    {
        foreach ($this->allCars as $car) {
            if (!in_array($car->characteristic_id, $this->updatedCarsId)) {
                ConcreteCarCharacteristic::where('concrete_car_id', $car->characteristic_id)->delete();
            }
        }
    }
}
