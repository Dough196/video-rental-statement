<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RentalStoreRequest;

class RentalController extends Controller
{
    function store(RentalStoreRequest $request) : JsonResponse {
        $rental = Rental::create($request->validated());

        $rental->calculateAmounts();
        $rental->save();

        if (empty($rental)) {
            return response()->json([
                'error' => 'Rental could not be created'
            ], 503);
        }

        return response()->json([ 'rental' => $rental ], 200);
    }
}
