<?php

namespace App\Models;

use App\Models\Rental;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * Get all of the rentals for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

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

    protected function evaluateAmountExpression($expression, $variables = [])
    {
        // Replace variables in the expression with their values
        foreach ($variables as $variable => $value) {
            $expression = str_replace($variable, $value, $expression);
        }

        // Use eval() to evaluate the expression
        $result = @eval("return $expression;");

        // Check for errors
        if ($result === false) {
            return 0;
        }

        return $result;
    }

    protected function evaluateBooleanStatement($statement, $variables = [])
    {
        if (empty($statement)) {
            return true;
        }

        $data = json_decode($statement, true);

        $expressions = [];
        foreach ($data['statements'] as $statement) {
            $field = $statement['field'];
            $value = $statement['value'];
            $comparator = $statement['comparator'];

            $expressions[] = "$variables[$field] $comparator $value";
        }

        $booleanExpression = implode(" && ", $expressions);

        // Evaluate the boolean expression
        $result = @eval("return ($booleanExpression);");

        return $result;
    }
}
