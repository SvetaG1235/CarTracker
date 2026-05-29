<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Insurance;
use App\Models\ServiceCard;
use App\Models\Car;
use Illuminate\Support\Facades\Log;

class CarRelatedController extends Controller
{
    // 🔹 Водители
    public function storeDriver(Request $r)
    {
        $r->validate([
            'car_id'         => 'required|exists:cars,id',
            'full_name'      => 'required|string|max:100',
            'license_number' => 'nullable|string|max:50',
            'phone'          => 'nullable|string|max:20',
            'is_primary'     => 'boolean'
        ]);

        $data = $r->only(['car_id', 'full_name', 'license_number', 'phone']);
        $data['is_primary'] = $r->boolean('is_primary');

        Driver::create($data);
        return back()->with('success', 'Водитель добавлен');
    }

    public function destroyDriver(Driver $driver)
    {
        if (!$driver->id) abort(404, 'Запись не найдена');
        
        $car = Car::find($driver->car_id);
        if (!$car || $car->user_id != auth()->id()) {
            abort(403, 'Нет прав доступа');
        }

        $driver->delete();
        return back()->with('success', 'Водитель удалён');
    }

    // 🔹 Страховка
    public function storeInsurance(Request $r)
    {
        $data = $r->validate([
            'car_id'        => 'required|exists:cars,id',
            'type'          => 'required|in:osago,casco',
            'policy_number' => 'required|string|max:50',
            'company'       => 'required|string|max:100',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'cost'          => 'nullable|numeric|min:0',
            'policy_file'   => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $dir = public_path('uploads/insurance_policies');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        if ($r->hasFile('policy_file')) {
            $filename = uniqid('ins_') . '.' . $r->file('policy_file')->getClientOriginalExtension();
            $r->file('policy_file')->move($dir, $filename);
            $data['policy_file'] = 'uploads/insurance_policies/' . $filename;
        }

        Insurance::create($data);
        return back()->with('success', 'Страховка добавлена');
    }

    public function destroyInsurance(Insurance $insurance)
    {
        if (!$insurance->id) abort(404, 'Запись не найдена');
        
        $car = Car::find($insurance->car_id);
        if (!$car || $car->user_id != auth()->id()) {
            abort(403, 'Нет прав доступа');
        }

        if ($insurance->policy_file && file_exists(public_path($insurance->policy_file))) {
            unlink(public_path($insurance->policy_file));
        }
        $insurance->delete();
        return back()->with('success', 'Страховка удалена');
    }

    // 🔹 Сервисные карты
    public function storeServiceCard(Request $r)
    {
        $data = $r->validate([
            'car_id'              => 'required|exists:cars,id',
            'workshop_name'       => 'required|string|max:100',
            'service_card_number' => 'nullable|string|max:50',
            'barcode_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_phone'       => 'nullable|string|max:20',
            'address'             => 'nullable|string|max:200',
            'last_visit'          => 'nullable|date',
            'notes'               => 'nullable|string',
        ]);

        $dir = public_path('uploads/service_cards');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        if ($r->hasFile('barcode_image')) {
            $filename = uniqid('sc_') . '.' . $r->file('barcode_image')->getClientOriginalExtension();
            $r->file('barcode_image')->move($dir, $filename);
            $data['barcode_image'] = 'uploads/service_cards/' . $filename;
        }

        ServiceCard::create($data);
        return back()->with('success', 'Сервисная карта добавлена');
    }

    public function destroyServiceCard(ServiceCard $service_card)
    {
        if (!$service_card->id) abort(404, 'Запись не найдена');
        
        $car = Car::find($service_card->car_id);
        if (!$car || $car->user_id != auth()->id()) {
            abort(403, 'Нет прав доступа');
        }

        if ($service_card->barcode_image && file_exists(public_path($service_card->barcode_image))) {
            unlink(public_path($service_card->barcode_image));
        }
        $service_card->delete();
        return back()->with('success', 'Карта удалена');
    }
}