<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBooleanStatementRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Define valid options for field and comparator
        $validFields = ['{days_rented}'];
        $validComparators = ['>', '<', '>=', '<=', '==', '!='];

        // Decode the JSON string into an associative array
        $data = $value;
        if (is_string($data)) {
            $data = json_decode($value, true);
        }

        // Check if "statements" key exists and is an array
        if (!isset($data['statements']) || !is_array($data['statements'])) {
            $fail('The :attribute should contains statements property as an array.');
        }

        // Iterate over each statement and validate its structure
        foreach ($data['statements'] as $statement) {
            // Check if statement has all required keys
            if (!isset($statement['field'], $statement['value'], $statement['comparator'])) {
                $fail('The statement from :attribute should contains field, value and comparator properties.');
            }

            // Check if "field" is a valid option
            if (!in_array($statement['field'], $validFields)) {
                $fail('Invalid `field` property value.');
            }

            // Check if "value" is an unsigned integer
            if (!is_int($statement['value']) || $statement['value'] < 0) {
                $fail('Invalid `value` property value, only unsigned intergar are allowed.');
            }

            // Check if "comparator" is a valid option
            if (!in_array($statement['comparator'], $validComparators)) {
                $fail('Invalid `comparator` property value.');
            }
        }
    }
}
