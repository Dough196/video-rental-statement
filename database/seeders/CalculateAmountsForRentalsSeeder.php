<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CalculateAmountsForRentalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = Rental::whereNull('amount')
            ->orWhereNull('frequent_points')
            ->limit(50)
            ->get();
        
        foreach ($rentals as $rental) {
            $rental->calculateAmounts();
            $rental->save();
        }
    }
}
