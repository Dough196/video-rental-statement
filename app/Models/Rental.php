<?php

namespace App\Models;

use App\Models\Movie;
use App\Models\Customer;
use App\Traits\EvaluationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;
    use EvaluationTrait;

    /**
     * Get the customer that owns the Rental
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the movie that owns the Rental
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Get rental statement for the model
     *
     * @return string
     */
    public function calculateAmounts(): void {
        $this->loadMissing('movie.category');
        $expression_variables = [
            '{days_rented}' => $this->days_rented
        ];
        $this->amount = 0;
        $this->frequent_points = 0;

        $this->amount += $this->evaluateAmountExpression($this->movie->category->amount_expression, $expression_variables);

        if ($this->evaluateBooleanStatement($this->movie->category->additional_amount_statement, $expression_variables)) {
            $this->amount += $this->evaluateAmountExpression($this->movie->category->additional_amount_expression, $expression_variables);
        }

        // add frequent renter points
        $this->frequent_points++;

        // add bonus for a two day new release rental
        if (
            $this->movie->category->frequent_points_bonus &&
            $this->evaluateBooleanStatement($this->movie->category->frequent_points_bonus_statement, $expression_variables)
        ) {
            $this->frequent_points++;
        }
    }
}
