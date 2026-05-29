<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'car_id'           => 'nullable|exists:cars,id',
            'title'            => 'required|string|max:100',
            'type'             => 'required|in:oil,coolant,brake_fluid,tires,inspection,custom',
            'due_date'         => 'required|date',
            'is_recurring'     => 'boolean',
            'is_mileage_based' => 'boolean',
            'mileage_interval' => 'nullable',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->sometimes('mileage_interval', 'required|integer|min:100', function ($input) {
            return $input->is_mileage_based == 1;
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_recurring'     => filter_var($this->is_recurring, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'is_mileage_based' => filter_var($this->is_mileage_based, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ]);
    }
}