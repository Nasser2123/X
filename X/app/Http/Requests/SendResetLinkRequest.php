<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendResetLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
             return [
                 'email' => 'required|email|Exists:users,email'
             ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.*' => 'You do not have a account , please register.',
        ];
    }
}
