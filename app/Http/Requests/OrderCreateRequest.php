<?php

namespace App\Http\Requests;

use App\Models\DeliveryDay;
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
        $deliveryDayExistsRule = function ($attribute, $value, $fail) {
            if (!request()->has('tariff_id')) {
                $fail('The ' . $attribute . ' is invalid.');
            }

            $dayExists = DeliveryDay::where('tariff_id', request()->tariff_id)
                ->where('week_day', $value)
                ->exists();

            if (!$dayExists) {
                $fail('The ' . $attribute . ' is invalid.');
            }
        };

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'min:2', 'max:32'],
            'tariff_id' => ['required', 'numeric', 'exists:tariffs,id'],
            'delivery_day' => ['required', 'string', 'size:3', $deliveryDayExistsRule],
        ];
    }
}
