@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>{{ isset($car) ? 'Редактирование автомобиля' : 'Добавить автомобиль' }}</h4>
    <a href="{{ route('cars.index') }}" class="btn btn-outline-makk">Назад</a>
</div>

<div class="card card-makk">
    <div class="card-body">
        <form action="{{ isset($car) ? route('cars.update', $car) : route('cars.store') }}" method="POST">
            @csrf
            @isset($car)
                @method('PUT')
            @endisset

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Марка *</label>
                    <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" 
                           value="{{ old('brand', $car->brand ?? '') }}" required>
                    @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Модель *</label>
                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                           value="{{ old('model', $car->model ?? '') }}" required>
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Год выпуска</label>
                    <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" 
                           value="{{ old('year', $car->year ?? '') }}" min="1900" max="{{ date('Y') + 1 }}">
                    @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Гос. номер</label>
                    <input type="text" name="plate" class="form-control @error('plate') is-invalid @enderror" 
                           value="{{ old('plate', $car->plate ?? '') }}">
                    @error('plate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Пробег (км)</label>
                    <input type="number" name="mileage" class="form-control @error('mileage') is-invalid @enderror" 
                           value="{{ old('mileage', $car->mileage ?? 0) }}" min="0">
                    @error('mileage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">VIN</label>
                    <input type="text" name="vin" class="form-control @error('vin') is-invalid @enderror" 
                           value="{{ old('vin', $car->vin ?? '') }}" maxlength="17">
                    @error('vin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Примечание</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $car->notes ?? '') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-makk">{{ isset($car) ? 'Сохранить изменения' : 'Добавить' }}</button>
                <a href="{{ route('cars.index') }}" class="btn btn-outline-makk">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection