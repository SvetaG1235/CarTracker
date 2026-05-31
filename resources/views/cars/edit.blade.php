@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Редактирование автомобиля</h4>
    <a href="{{ route('cars.index') }}" class="btn btn-outline-app">Назад к списку</a>
</div>

<div class="card card-app">
    <div class="card-body">
        <form action="{{ route('cars.update', $car) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="brand" class="form-label">Марка *</label>
                    <input type="text" id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" 
                           value="{{ old('brand', $car->brand) }}" required>
                    @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="model" class="form-label">Модель *</label>
                    <input type="text" id="model" name="model" class="form-control @error('model') is-invalid @enderror" 
                           value="{{ old('model', $car->model) }}" required>
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Год выпуска</label>
                    <input type="number" id="year" name="year" class="form-control @error('year') is-invalid @enderror" 
                           value="{{ old('year', $car->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                    @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="plate" class="form-label">Гос. номер</label>
                    <input type="text" id="plate" name="plate" class="form-control @error('plate') is-invalid @enderror" 
                           value="{{ old('plate', $car->plate) }}">
                    @error('plate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="mileage" class="form-label">Пробег (км)</label>
                    <input type="number" id="mileage" name="mileage" class="form-control @error('mileage') is-invalid @enderror" 
                           value="{{ old('mileage', $car->mileage ?? 0) }}" min="0">
                    @error('mileage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="vin" class="form-label">VIN</label>
                    <input type="text" id="vin" name="vin" class="form-control @error('vin') is-invalid @enderror" 
                           value="{{ old('vin', $car->vin) }}" maxlength="17">
                    @error('vin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="notes" class="form-label">Примечание</label>
                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $car->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-app">Сохранить изменения</button>
                <a href="{{ route('cars.index') }}" class="btn btn-outline-app">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection