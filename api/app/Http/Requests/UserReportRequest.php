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

    protected function prepareForValidation(): void
    {
        if ($this->method() === 'POST') {
            $gameMatch = \App\Models\GameMatch::where("user_id", $this->user()->id)
                ->where("match_uuid", $this->match_uuid)
                ->first();

            if ($gameMatch) {
                $this->merge(['game_match_id' => $gameMatch->id]);
            }
        }
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
            "round_number" => "required|integer",
            "comment" => "required|string",
        ];

        if ($this->method() === 'POST') {
            $validations = array_merge($validations, [
                'game_match_id' => [
                    'required',
                    Rule::unique('user_reports')->where(
                        fn($query) => $query->where('user_id', $this->user()->id)
                    ),
                ],
            ]);
        }

        if ($this->method() === 'PUT') {
            $validations = array_merge($validations, [
                "user_id" => "required|integer|exists:users,id",
                "status" => ["required", Rule::enum(ReportStatusEnum::class)]
            ]);
        }

        return $validations;
    }

    public function messages(): array
    {
        return [
            'game_match_id.unique' => 'Ezt a felhasználót már bejelentetted. Dolgozunk rajta.',
        ];
    }
}
