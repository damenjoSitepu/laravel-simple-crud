<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorBookRequest extends FormRequest
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
                "book_id" => ["required"]
            ];
        }
    }

    /**
     * Define Message Validation
     *
     * @return array
     */
    public function messages(): array 
    {
        if ($this->getMethod() === "POST") {
            return [
                "book_id.required" => "Please select at least one book!",
            ];
        }
    }
}
