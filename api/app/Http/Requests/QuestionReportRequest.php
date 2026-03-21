<?php

namespace App\Http\Requests;

use App\ReportStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionReportRequest extends FormRequest
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
        if ($this->method() === 'DELETE' || $this->method() === 'GET') {
            return [];
        }

        $validations =  [
            "comment" => "required|string",
        ];

        if ($this->method() === 'POST') {
            $validations = array_merge($validations, [
                'question_token' => 'required|string',
                'question_id' => [
                    'required',
                    Rule::unique('question_reports')->where(
                        fn($query) =>
                        $query->where('user_id', $this->user()->id)
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
            'question_id.unique' => 'Ezt a kérdést már bejelentetted. Köszönjük hozzájárulásodat.',
        ];
    }
}
