<?php

namespace App\Models;

use App\Models\Rental;
use App\Traits\EvaluationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use EvaluationTrait;

    protected $guarded = [];

    /**
     * Get all of the rentals for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get rental statement text for the Customer
     *
     * @return string
     */
    public function statement(): string
    {
        $this->loadMissing('rentals.movie.category');
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $result = "Rental Record for " . $this->name . "\n";


        // determine amounts for each line
        foreach ($this->rentals as $rental) {
            $expression_variables = [
                '{days_rented}' => $rental->days_rented
            ];
            $thisAmount = 0;

            $thisAmount += $this->evaluateAmountExpression($rental->movie->category->amount_expression, $expression_variables);

            if ($this->evaluateBooleanStatement($rental->movie->category->additional_amount_statement, $expression_variables)) {
                $thisAmount += $this->evaluateAmountExpression($rental->movie->category->additional_amount_expression, $expression_variables);
            }

            // add frequent renter points
            $frequentRenterPoints++;

            // add bonus for a two day new release rental
            if (
                $rental->movie->category->frequent_points_bonus &&
                $this->evaluateBooleanStatement($rental->movie->category->frequent_points_bonus_statement, $expression_variables)
            ) {
                $frequentRenterPoints++;
            }

            // show figures for this rental
            $result .= "\t" . $rental->movie->title . "\t" .
                $thisAmount . "\n";
            $totalAmount += $thisAmount;
        }

        // add footer lines
        $result .= "Amount owed is " . $totalAmount . "\n";
        $result .= "You earned " . $frequentRenterPoints .
            " frequent renter points";

        return $result;
    }
}
