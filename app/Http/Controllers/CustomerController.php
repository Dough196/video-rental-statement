<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CustomerStoreRequest;

class CustomerController extends Controller
{
    /**
     * Store a new customer.
     */
    public function store(CustomerStoreRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        if (empty($customer)) {
            return response()->json([
                'error' => 'Customer could not be created'
            ], 503);
        }

        return response()->json([
            'customer' => $customer
        ], 200);
    }

    public function plainStatement(Customer $customer)
    {
        echo '<pre>';
        echo $customer->statement();
        echo '</pre>';
    }

    public function htmlStatement($customer)
    {
        $customer = Customer::withSum('rentals', 'amount')
            ->withSum('rentals', 'frequent_points')
            ->with('rentals.movie.category')
            ->find($customer);

        return view('customer.statement', ['customer' => $customer]);
    }
}
