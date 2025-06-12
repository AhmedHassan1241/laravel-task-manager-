<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            //
            'title' => 'sometimes|string|regex:/[a-zA-Zء-ي]/|max:40',
            'description' => 'sometimes|nullable|string',
            'priority' => 'sometimes|integer|min:1|max:5'
        ];
    }
    public function messages()
    {
        return [
            'title.string' => " يجب ان يكون العنوان نصاً",
            'title.regex' => " يجب ان يكون العنوان نصاً",
            'title.max'=>"العنوان لا يجب ان يزيد عن 40 حرفاً",
            'description.string'=>"الوصف يجب ان يكون نصاً",
            'priority.integer' => "يجب ان تكون الاولوية رقم صحيح",
            'priority.min' => "يجب قيمة الاولوية تكون اكثر من 1",
            'priority.max' => "يجب قيمة الاولوية تكون اقل من 5",
        ];
    }
}
