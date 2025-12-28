<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:pending,completed,in_progress',
        ];
    }
}
