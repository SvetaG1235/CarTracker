@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>📸 Галерея</h4>
    <a href="{{ route('cars.index') }}" class="btn btn-outline-app">← К автомобилям</a>
</div>

<!-- Фильтр по машинам -->
<div class="card card-app mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('photos.index') }}" class="row g-2">
            <div class="col-auto">
                <select name="car_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Все автомобили</option>
                    @foreach($cars as $car)
<option value="{{ $car->id }}" {{ request('car_id') == $car->id ? 'selected' : '' }}>
    {{ $car->make }} {{ $car->model }} 
    @if($car->plate) 
        • <strong>{{ strtoupper($car->plate) }}</strong>
    @endif
</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Сетка фото -->
<div class="row g-3">
    @forelse($photos as $photo)
    <div class="col-md-4 col-lg-3">
        <div class="card card-app">
            <div class="position-relative">
                <img src="{{ route('files.show', ['path' => $photo->image_path]) }}" 
                     class="card-img-top" 
                     alt="{{ $photo->description }}"
                     style="height: 200px; object-fit: cover;">
                
                <!-- Кнопка удаления -->
                <button type="button" 
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                        onclick="if(confirm('Удалить фото?')) document.getElementById('delete-{{ $photo->id }}').submit()">
                    🗑️
                </button>
                <form id="delete-{{ $photo->id }}" 
                      action="{{ route('photos.destroy', $photo) }}" 
                      method="POST" 
                      style="display: none;">
                    @csrf @method('DELETE')
                </form>
            </div>
            
            <div class="card-body">
<small class="text-muted">
    {{ $photo->car->brand }} {{ $photo->car->model }}
    @if($photo->car->plate)
        <span class="badge badge-app-secondary ms-1">{{ strtoupper($photo->car->plate) }}</span>
    @endif
</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center text-muted py-5">
            <p>📭 Нет фотографий</p>
            <a href="{{ route('cars.index') }}" class="btn btn-app">Добавить первое фото</a>
        </div>
    </div>
    @endforelse
</div>

<!-- Пагинация -->
<div class="mt-4">
{{ $photos->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection