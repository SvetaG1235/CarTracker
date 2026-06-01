<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Insurance;
use App\Models\ServiceCard;
use App\Models\Car;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    // 🎯 Если этот водитель основной — сбрасываем старого
    if ($data['is_primary']) {
        Driver::where('car_id', $data['car_id'])
              ->update(['is_primary' => false]);
    }

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

        // ✅ Исправлено: используем storage диск вместо public/
        if ($r->hasFile('policy_file')) {
            $file = $r->file('policy_file');
            $filename = uniqid('ins_') . '.' . $file->getClientOriginalExtension();
            
            // Сохраняем в storage/app/uploads/insurance_policies/
            $path = $file->storeAs('uploads/insurance_policies', $filename, 'local');
            $data['policy_file'] = $path; // сохраняем относительный путь
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

        // ✅ Исправлено: удаляем из storage/
        if ($insurance->policy_file) {
            $fullPath = storage_path('app/' . $insurance->policy_file);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
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

        // ✅ Исправлено: используем storage диск
        if ($r->hasFile('barcode_image')) {
            $file = $r->file('barcode_image');
            $filename = uniqid('sc_') . '.' . $file->getClientOriginalExtension();
            
            // Сохраняем в storage/app/uploads/service_cards/
            $path = $file->storeAs('uploads/service_cards', $filename, 'local');
            $data['barcode_image'] = $path;
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

        // ✅ Исправлено: удаляем из storage/
        if ($service_card->barcode_image) {
            $fullPath = storage_path('app/' . $service_card->barcode_image);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $service_card->delete();
        return back()->with('success', 'Карта удалена');
    }
}