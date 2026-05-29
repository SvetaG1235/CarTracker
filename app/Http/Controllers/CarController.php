<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
// Если нужен сервис, путь должен быть таким (без App\Http\Controllers):
// use App\Services\MileageReminderChecker;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::where('user_id', auth()->id())->latest()->paginate(10);
        return view('cars.index', compact('cars'));
    }

    public function create() { return view('cars.create'); }

    public function store(StoreCarRequest $request)
    {
        auth()->user()->cars()->create($request->validated());
        return redirect()->route('cars.index')->with('success', 'Автомобиль добавлен');
    }

    public function show(Car $car) { $this->checkOwner($car); return view('cars.show', compact('car')); }
    
    public function edit(Car $car)  { $this->checkOwner($car); return view('cars.edit', compact('car')); }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $this->checkOwner($car);
        
        // Сохраняем старый пробег для сравнения
        $oldMileage = $car->mileage;
        
        // Обновляем данные
        $car->update($request->validated());
        
        // 🔥 ВАЖНО: Логику проверки напоминаний по пробегу лучше вынести в Модель (см. шаг 2 ниже)
        // Здесь просто обновляем машину, чтобы не ломать контроллер зависимостями
        
        return redirect()->route('cars.index')->with('success', 'Данные обновлены');
    }

    public function destroy(Car $car)
    {
        $this->checkOwner($car);
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'Автомобиль удалён');
    }

    private function checkOwner(Car $car)
    {
        if ($car->user_id !== auth()->id()) abort(403, 'Нет доступа к этому автомобилю');
    }
}