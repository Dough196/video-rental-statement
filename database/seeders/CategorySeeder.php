<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Regular',
                'amount_expression' => '2',
                'additional_amount_expression' => '({days_rented} - 2) * 1.5',
                'additional_amount_statement' => '{ "statements": [ { "comparator": ">", "field": "{days_rented}", "value": 2 } ] }',
                'frequent_points_bonus' => false,
                'frequent_points_bonus_statement' => null,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
            [
                'name' => 'New Release',
                'amount_expression' => '{days_rented} * 3',
                'additional_amount_expression' => null,
                'additional_amount_statement' => null,
                'frequent_points_bonus' => true,
                'frequent_points_bonus_statement' => '{ "statements": [ { "comparator": ">", "field": "{days_rented}", "value": 1 } ] }',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
            [
                'name' => 'Childrens',
                'amount_expression' => '1.5',
                'additional_amount_expression' => '({days_rented} - 3) * 1.5',
                'additional_amount_statement' => '{ "statements": [ { "comparator": ">", "field": "{days_rented}", "value": 3 } ] }',
                'frequent_points_bonus' => false,
                'frequent_points_bonus_statement' => null,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
        ]);
    }
}
