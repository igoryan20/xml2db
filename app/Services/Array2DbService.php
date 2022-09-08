<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CarOffer;
use App\Models\CarGeneration;
use App\Models\CarModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Array2DbService
{
    private Collection $allOffers;
    private array $updatedOffersId;

    public function sendArray2Db(array $data)
    {
        foreach ($data['offers'] as $offers) {
            $this->sendOffers($offers);
        }
    }

    private function sendOffers(array $offers)
    {
        $this->allOffers = CarOffer::all();
        foreach ($offers as $offer) {
            $this->sendOffer($offer);
        }
        $this->deleteNotUpdatedOffers();
    }

    private function sendOffer(array $offer)
    {
        $offerDb = CarOffer::where('offer_id', $offer['id'])->first();
        if ($offerDb) {
            $this->updatedOffersId[] = $offer['id'];
            $this->updateTables($offer, $offerDb);
        } else {
            $this->addToTables($offer);
        }
    }

    private function updateTables(array $offer, $offerDb)
    {
        DB::transaction(function () use ($offer, $offerDb) {
            $offerDb->year = $offer['year'];
            $offerDb->run = $offer['run'];
            $offerDb->color = $offer['color'];
            $offerDb->body_type = $offer['body-type'];
            $offerDb->engine_type = $offer['engine-type'];
            $offerDb->transmission = $offer['transmission'];
            $offerDb->gear_type = $offer['gear-type'];
            $offerDb->generation_id = $offer['generation_id'];
            $carModel = CarModel::where('model_id', $offerDb->model_id)->first();
            $carModel->mark = $offer['mark'];
            $carModel->model = $offer['model'];
            $carModel->save();
            if ($offerDb->generation_id) {
                $carGeneration = CarGeneration::where('generation_id', $offerDb->generation_id)->first();
                $carGeneration->generation_id = $offer['generation_id'];
                $carGeneration->generation = $offer['generation'];
                $carGeneration->save();
            }
            $offerDb->save();
        });
    }

    private function addToTables(array $car)
    {
        DB::transaction(function () use ($car) {
            $carModel = CarModel::where('mark', $car['mark'])->where('model', $car['model'])->first();
            if (!$carModel) {
                $carModel = new CarModel;
                $carModel->mark = $car['mark'];
                $carModel->model = $car['model'];
                $carModel->save();
            }
            $concreteCar = new CarOffer;
            if ($car['generation_id']) {
                $carGeneration = CarGeneration::where('generation_id', $car['generation_id'])->first();
                if (!$carGeneration) {
                    $carGeneration = new CarGeneration;
                    $carGeneration->generation_id = $car['generation_id'];
                    $carGeneration->generation = $car['generation'];
                    $carGeneration->model_id = $carModel->model_id;
                    $carGeneration->save();
                }
                $concreteCar->generation_id = ($car['generation_id']);
            }
            $concreteCar->offer_id = $car['id'];
            $concreteCar->year = $car['year'];
            $concreteCar->run = $car['run'];
            $concreteCar->color = $car['color'];
            $concreteCar->body_type = $car['body-type'];
            $concreteCar->engine_type = $car['engine-type'];
            $concreteCar->transmission = $car['transmission'];
            $concreteCar->gear_type = $car['gear-type'];
            $concreteCar->model_id = $carModel->model_id;
            $concreteCar->save();
        });
    }

    private function deleteNotUpdatedOffers()
    {
        foreach ($this->allOffers as $car) {
            if (!in_array($car->offer_id, $this->updatedOffersId)) {
                DB::transaction(function () use ($car) {
                    $carOffers = CarOffer::where('model_id', $car->model_id)->get();
                    $generation = null;
                    if ($car->generation_id) {
                        $generationOffers = CarOffer::where('generation_id', $car->generation_id)->get();
                        if ($generationOffers->count() === 1) {
                            $generation = CarGeneration::where('generation_id', $car->generation_id);
                        }
                    }
                    CarOffer::where('offer_id', $car->offer_id)->delete();
                    if ($generation) {
                        $generation->delete();
                    }
                    if ($carOffers->count() === 1) {
                        CarModel::where('model_id', $car->model_id)->delete();
                    }
                });
            }
        }
    }
}
