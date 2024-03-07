<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function plainStatement (Customer $customer) {
        echo '<pre>';
        echo $customer->statement();
        echo '</pre>';
        // return response()->json($customer->statement());
    }
}
