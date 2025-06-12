<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            //all rules that i was used in validate in TaskController : this way to seperate validate rules from controllers
            'title' => 'required|string|regex:/[a-zA-Zء-ي]/|max:40',
            'description' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
            // 'user_id' => 'required|exists:users,id'
        ];
    }
}
