<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMathExpressionRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Define the regex pattern for variable names
        $variablePattern = '/\{[a-zA-Z_]+\}/';

        // Check if all variable names are in the correct format
        // preg_match_all($variablePattern, $value, $matches);
        // foreach ($matches[0] as $variable) {
        //     // Remove curly braces
        //     $variableName = substr($variable, 1, -1);
        //     // Check if variable name starts with a letter or underscore
        //     if (!ctype_alpha($variableName[0]) && $variableName[0] !== '_') {
        //         $fail('The :attribute contains variable with invalid format.');
        //     }
        // }

        // Replace variables with a placeholder value for evaluation
        $expression = preg_replace($variablePattern, '1', $value);

        // Define the regex pattern for the entire math expression
        $mathExpressionPattern = '/^(\({0,1}[0-9]+(\.[0-9]+)?\){0,1}(\s*[\+\-\*\/]\s*\({0,1}[0-9]+(\.[0-9]+)?\){0,1})*)$/';

        // Check if the expression matches the math expression pattern
        if (!preg_match($mathExpressionPattern, $expression)) {
            $fail('The :attribute contains not allowed characters or is not valid.');
        }

        // Check for balanced parentheses
        $parenthesesCount = 0;
        for ($i = 0; $i < strlen($value); $i++) {
            if ($value[$i] === '(') {
                $parenthesesCount++;
            } elseif ($value[$i] === ')') {
                $parenthesesCount--;
            }

            // If the count becomes negative, there are more closing parentheses than opening ones
            if ($parenthesesCount < 0) {
                $fail("The :attribute has an unexpected ')'.");
                break;
            }
        }

        // Check if there are an equal number of opening and closing parentheses
        if ($parenthesesCount > 0) {
            $fail("The :attribute has an unexpected '('.");
        }
    }
}
