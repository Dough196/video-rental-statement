<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function store(CategoryStoreRequest $request) : JsonResponse {
        $category = Category::create($request->validated());
        if (empty($category)) {
            return response()->json([
                'error' => 'Category could not be created'
            ], 503);
        }
        return response()->json(['category' => $category], 200);
    }
}
