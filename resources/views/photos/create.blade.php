@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>📸 Добавить фото</h4>
    <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-app">← Отмена</a>
</div>

<div class="card card-app">
    <div class="card-body">
        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">
            
            <div class="mb-3">
                <label class="form-label">Фотография</label>
                <input type="file" 
                       name="image" 
                       class="form-control @error('image') is-invalid @enderror" 
                       accept="image/*"
                       required>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Категория</label>
                <select name="category" class="form-select" required>
                    <option value="exterior">Экстерьер</option>
                    <option value="interior">Интерьер</option>
                    <option value="engine">Двигатель</option>
                    <option value="other">Прочее</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            
            <button type="submit" class="btn btn-app">💾 Сохранить</button>
        </form>
    </div>
</div>
@endsection