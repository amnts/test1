<?php

namespace App\Http\Requests;

use App\Models\Tariff;
use App\Services\Tariffs;
use Illuminate\Foundation\Http\FormRequest;
use DateTime;

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
        $testDeliveryDate = function ($attribute, $value, $fail)
        {
            try {
                if (!request()->has('tariff_id') || empty($value)) {
                    throw new \Exception;
                }

                $date = DateTime::createFromFormat('Y-m-d+', $value);

                if (!($date instanceof DateTime)) {
                    throw new \Exception;
                }

                $tariff = Tariff::find(request()->tariff_id);

                if (!$tariff || !(new Tariffs)->isDateValidForTariff($date, $tariff)) {
                    throw new \Exception;
                }
            } catch (\Exception $e) {
                $fail('The ' . $attribute . ' is invalid.');
            }

            return true;
        };

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'min:2', 'max:32'],
            'tariff_id' => ['required', 'numeric', 'exists:tariffs,id'],
            'delivery_date_start' => ['required', 'string', 'regex:/^\d{4}-\d\d-\d\d/', $testDeliveryDate],
        ];
    }

    /**
    * Tranform the data after validation.
    *
    * @return array
    */
    public function getPreparedData(): array
    {
        $data = $this->validated();

        $date = DateTime::createFromFormat('Y-m-d+', $data['delivery_date_start']);

        if ($date) {
            $data['delivery_date_start'] = $date->format('Y-m-d');
        }

        return $data;
    }
}
