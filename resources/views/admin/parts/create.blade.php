@extends('layouts.app')
@section('content')
<h2 class="mb-3">{{ isset($part) ? '✏️ Редактирование' : '➕ Добавление' }} запчасти</h2>
<form action="{{ isset($part) ? route('admin.parts.update', $part) : route('admin.parts.store') }}" method="POST" class="card p-4 shadow-sm">
    @csrf
    @if(isset($part)) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Название *</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $part->name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Бренд</label><input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $part->brand ?? '') }}"></div>
        <div class="col-md-4"><label class="form-label">Артикул (SKU)</label><input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $part->sku ?? '') }}"></div>
        <div class="col-md-4"><label class="form-label">Цена (₽)</label><input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $part->price ?? '') }}"></div>
        <div class="col-12"><label class="form-label">Совместимость (JSON)</label><textarea name="compatibility" class="form-control @error('compatibility') is-invalid @enderror" rows="2" placeholder='{"brands":["Toyota","Honda"],"years":[2018,2019,2020]}'>{{ old('compatibility', $part->compatibility ?? '') }}</textarea></div>
        <div class="col-12"><label class="form-label">Описание</label><textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $part->description ?? '') }}</textarea></div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">💾 Сохранить</button>
        <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary">Отмена</a>
    </div>
</form>
@endsection