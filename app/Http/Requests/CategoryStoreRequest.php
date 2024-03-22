<?php

namespace App\Http\Requests;

use App\Rules\ValidMathExpressionRule;
use App\Rules\ValidBooleanStatementRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'amount_expression' => ['required', 'string', new ValidMathExpressionRule],
            'additional_amount_expression' => ['nullable', 'string', new ValidMathExpressionRule],
            'additional_amount_statement' => ['required_unless:additional_amount_expression,null', new ValidBooleanStatementRule],
            'frequent_points_bonus' => 'required|boolean',
            'frequent_points_bonus_statement' => ['nullable', new ValidBooleanStatementRule]
        ];
    }
}
