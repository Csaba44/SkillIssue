<?php

namespace App\Http\Requests;

use App\ReportStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->method() === "POST") return true;

        return $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // No validation for DELETE
        if ($this->method() === 'DELETE' || $this->method() === 'GET') {
            return [];
        }

        $validations =  [
            "game_match_id" => "required|integer|exists:game_matches,id",
            "round_number" => "required|integer",
            "comment" => "required|string",
        ];

        if ($this->method() === 'PUT') {
            $validations = array_merge($validations, [
                "user_id" => "required|integer|exists:users,id",
                "status" => ["required", Rule::enum(ReportStatusEnum::class)]
            ]);
        }

        return $validations;
    }
}
