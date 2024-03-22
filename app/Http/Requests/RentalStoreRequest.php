<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentalStoreRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'movie_id' => 'required|integer|exists:movies,id',
            'days_rented' => 'required|integer'
        ];
    }
}
