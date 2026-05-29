<?php namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'brand'   => 'required|string|max:50',
            'model'   => 'required|string|max:50',
            'year'    => 'nullable|integer|min:1900|max:'.(date('Y')+1),
            'vin'     => 'nullable|string|max:17|unique:cars,vin',
            'plate'   => 'nullable|string|max:20',
            'mileage' => 'nullable|integer|min:0',
        ];
    }
}