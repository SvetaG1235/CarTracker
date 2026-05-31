@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Внести расход</h4>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-app">Назад</a>
</div>

<div class="card card-app">
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Автомобиль *</label>
                    <select name="car_id" class="form-select @error('car_id') is-invalid @enderror" required>
                        <option value="">Выберите авто</option>
                        @foreach(auth()->user()->cars as $car)
                            <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>{{ $car->brand }} {{ $car->model }}</option>
                        @endforeach
                    </select>
                    @error('car_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Категория *</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Выберите категорию</option>
                        <option value="fuel" {{ old('category') == 'fuel' ? 'selected' : '' }}>Топливо</option>
                        <option value="wash" {{ old('category') == 'wash' ? 'selected' : '' }}>Мойка</option>
                        <option value="repair" {{ old('category') == 'repair' ? 'selected' : '' }}>Ремонт</option>
                        <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>ТО</option>
                        <option value="insurance" {{ old('category') == 'insurance' ? 'selected' : '' }}>Страховка</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Прочее</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Дата *</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Сумма (₽) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-app">Сохранить</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-app">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection