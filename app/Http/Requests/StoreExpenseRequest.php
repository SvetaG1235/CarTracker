<?php namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'car_id'      => 'required|exists:cars,id',
            'category'    => 'required|in:fuel,wash,repair,maintenance,insurance,other',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'description' => 'nullable|string|max:255',
        ];
    }
}