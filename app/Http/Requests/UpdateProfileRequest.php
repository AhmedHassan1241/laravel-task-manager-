<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            //  'user_id'=>'sometimes|exists:users,id',
            'phone'=>'sometimes|string|max:15',
            'address'=>'sometimes|nullable|string|max:100',
            'date_of_birth'=>'sometimes|nullable||date',
            'bio'=>'sometimes|nullable|string'
        ];
    }
}
