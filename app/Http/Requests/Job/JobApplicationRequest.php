<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
