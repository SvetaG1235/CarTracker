<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
public function index()
{
    // Базовый запрос
    $query = Photo::with('car');
    
    // 🔑 Фильтр по автомобилю, если выбран
    if (request('car_id')) {
        $query->where('car_id', request('car_id'));
    }
    
    // 🔐 Безопасность: только фото машин текущего пользователя
    $query->whereHas('car', function($q) {
        $q->where('user_id', auth()->id());
    });
    
    $photos = $query->latest()->paginate(12);
    
    // Сортировка для удобства в фильтре
    $cars = Car::where('user_id', auth()->id())
        ->orderBy('brand')
        ->orderBy('model')
        ->orderBy('plate')
        ->get();
    
    return view('photos.index', compact('photos', 'cars'));
}
    
    public function create(Car $car)
    {
        return view('photos.create', compact('car'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'description' => 'nullable|string|max:255',
            'category' => 'required|in:exterior,interior,engine,other'
        ]);
        
        // Создаём папку
        $uploadDir = storage_path('app/uploads/photos');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Сохраняем фото
        $file = $request->file('image');
        $filename = uniqid('photo_') . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);
        
        Photo::create([
            'car_id' => $request->car_id,
            'image_path' => 'uploads/photos/' . $filename,
            'description' => $request->description,
            'category' => $request->category
        ]);
        
        return redirect()->route('photos.index')->with('success', 'Фото добавлено');
    }
    
    public function destroy(Photo $photo)
    {
        // Проверяем права
        if ($photo->car->user_id != auth()->id()) {
            abort(403);
        }
        
        // Удаляем файл
        $fullPath = storage_path('app/' . $photo->image_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        
        $photo->delete();
        return back()->with('success', 'Фото удалено');
    }
}
