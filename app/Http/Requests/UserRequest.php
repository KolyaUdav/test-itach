<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::FIELD_EMAIL => 'required|string|max:50',
            User::FIELD_PASSWORD => 'required|string|max:25',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Все поля являются обязательными',
            
            User::FIELD_EMAIL . '.required' => 'Email обязателен для заполнения',
            User::FIELD_EMAIL . '.string' => 'Email должен быть строкой',
            User::FIELD_EMAIL . '.max:50' => 'Email не должен превышать 50 символов',
            
            User::FIELD_PASSWORD . '.required' => 'Пароль обязателен для заполнения',
            User::FIELD_PASSWORD . '.string' => 'Пароль должен быть строкой',
            User::FIELD_PASSWORD . '.max:25' => 'Пароль не должен превышать 25 символов',
        ];
    }
}