<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookAuthorRequest extends FormRequest
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
                "author_id" => ["required"],
                "role" => ["required"],
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
                "author_id.required" => "Please select at least one author!",
                "role.required" => "Please select role!",
            ];
        }
    }
}
