<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        if ($this->method() === 'GET' || $this->method() === 'DELETE') return [];

        if ($this->route()->uri() === 'api/questions/{question}/answer') {
            return [
                'answers' => ['required', 'array', 'size:4'],
                'answers.*.answer' => ['required', 'string'],
                'answers.*.is_correct' => ['required', 'boolean'],
            ];
        }
        return [
            "subject_id" => "required|integer|exists:subjects,id",
            "question" => "required|string",
        ];
    }
}
