<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MovieStoreRequest;

class MovieController extends Controller
{
    function store(MovieStoreRequest $request) : JsonResponse {

        $movie = Movie::create($request->validated());

        if (empty($movie)) {
            return response()->json([
                'error' => 'Movie could not be created'
            ], 503);
        }

        return response()->json([ 'movie' => $movie ], 200);
    }
}
