<?php

namespace App\Traits;

trait EvaluationTrait {

    /**
     *  Convert a string to a math expression and evaluate
     *
     * @param  string  $expression
     * @param  array  $variables
     * @return float
     */
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

    /**
     *  Convert a JSON to a boolean expression and evaluate
     *
     * @param  string|array  $statement
     * @param  array  $variables
     * @return bool
     */
    protected function evaluateBooleanStatement($statement, $variables = [])
    {
        if (empty($statement)) {
            return true;
        }

        $data = $statement;
        if (is_string($data)) {
            $data = json_decode($statement, true);
        }

        if (!isset($data['statements'])) {
            return true;
        }

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