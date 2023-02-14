<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required|max:255|min:5|unique:users,login',
            'email' => 'email:rfc,dns|required|unique:users,email|max:255',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'same:password'
        ];
    }

    public function getUserDataForRegistration(): array
    {
        $userData = [];
        foreach (['login', 'email', 'password'] as $inputField) {
            $userData[$inputField] = $this->$inputField;
        }

        return $userData;
    }
}
