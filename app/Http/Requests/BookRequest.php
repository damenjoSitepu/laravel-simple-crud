<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        if ($this->getMethod() === "POST") {
            return [
                "name" => [
                    "required",
                ],
                "isbn" => [
                    "required",
                ]
            ];
        } else if ($this->getMethod() === "PATCH") {
            return [
                "name" => [
                    "required",
                ],
                "isbn" => [
                    "required",
                ]
            ];
        }
    }

    public function messages(): array 
    {
        return [
            "name.required" => "Author name must be filled!",
            "isbn.required" => "ISBN must be filled!",
        ];
    }
}
