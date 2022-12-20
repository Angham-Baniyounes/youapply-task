<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|regex:/^[A-Za-z0-9._+-]*$/|unique:users',
            'phone_number' => 'required|string|min:9|max:14|unique:users',
            'password' => 'required|string|min:6|max:14|confirmed',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'gender' => 'required|in:male,female'
        ];
    }
}
