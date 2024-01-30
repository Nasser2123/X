<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'user_name' =>['Required' , 'String' ,'max:20' ,'unique:users'],
            'email' =>['Required','email', 'unique:users'],
            'password' =>['Required' , 'String' , 'Confirmed' ,'min:1'],
            'password_confirmation' =>['Required'],

        ];
    }
}
