<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAspectRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'unique:aspect,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.weight' => ['required', 'numeric', 'min:0'],
            'questions.*.max_score' => ['required', 'numeric'],
            'questions.*.min_score' => ['required', 'numeric'],
            'questions.*.is_mandatory' => ['required', 'boolean'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*.option_text' => ['required_with:questions.*.options', 'string', 'max:255'],
            'questions.*.options.*.score' => ['required_with:questions.*.options', 'numeric'],
            'questions.*.visibility_rules' => ['nullable', 'array'],
            'questions.*.visibility_rules.*.description' => ['nullable', 'string'],
            'questions.*.visibility_rules.*.entity_type' => ['required_with:questions.*.visibility_rules', Rule::in(['aspect', 'question'])],
            'questions.*.visibility_rules.*.entity_id' => ['required_with:questions.*.visibility_rules', 'integer'],
            'questions.*.visibility_rules.*.source_type' => ['required_with:questions.*.visibility_rules', Rule::in(['borrower_detail', 'borrower_facility', 'answer'])],
            'questions.*.visibility_rules.*.source_field' => ['required_with:questions.*.visibility_rules', 'string'],
            'questions.*.visibility_rules.*.operator' => ['required_with:questions.*.visibility_rules', 'string'],
            'questions.*.visibility_rules.*.value' => ['required_with:questions.*.visibility_rules', 'string'],
        ];
    }
}
