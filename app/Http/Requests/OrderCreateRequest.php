<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'min:2', 'max:32'],
            'tariff_id' => ['required', 'numeric', 'exists:tariffs,id'],
            'delivery_day_id' => ['required', 'numeric', 'exists:delivery_days,id'],
        ];
    }
}
