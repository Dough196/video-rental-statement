<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

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
