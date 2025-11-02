<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            Order::FIELD_FUEL_TYPE => 'required|string|max:50',
            Order::FIELD_QUANTITY => 'required|int|min:1',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Все поля являются обязательными',
            
            Order::FIELD_FUEL_TYPE . '.required' => 'Тип топлива обязателен для заполнения',
            Order::FIELD_FUEL_TYPE . '.string' => 'Тип топлива должен быть строкой',
            Order::FIELD_FUEL_TYPE . '.max' => 'Тип топлива не должен превышать 50 символов',
            
            Order::FIELD_QUANTITY . '.required' => 'Количество обязательно для заполнения',
            Order::FIELD_QUANTITY . '.integer' => 'Количество должно быть числом',
            Order::FIELD_QUANTITY . '.min' => 'Количество топлива не может быть меньше 1',
        ];
    }
}