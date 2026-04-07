<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "user_id" => ["required", "integer", "exists:users,id"],
            "release_date" => ["required", "date_format:Y-m-d H:i:s", "after:now"],
            "reason" => ["required", "string"],
        ];
    }
}
