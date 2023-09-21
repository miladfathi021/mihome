<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required_if:phone,null|nullable|email|string|unique:users,email',
            'phone' => 'required_if:email,null|nullable|string|unique:users,phone',
            'password' => 'required|string|min:6|max:255',
            'workspace' => 'required|string|min:3|max:255'
        ];
    }
}
