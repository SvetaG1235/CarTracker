{{-- resources/views/admin/parts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<h2 class="mb-3">✏️ Редактирование запчасти</h2>

<form action="{{ route('admin.parts.update', $part) }}" method="POST" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Название *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $part->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Бренд</label>
            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" 
                   value="{{ old('brand', $part->brand) }}">
            @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Артикул (SKU)</label>
            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                   value="{{ old('sku', $part->sku) }}">
            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Цена (₽)</label>
            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" 
                   value="{{ old('price', $part->price) }}">
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Совместимость (JSON)</label>
            <textarea name="compatibility" class="form-control @error('compatibility') is-invalid @enderror" 
                      rows="2" placeholder='{"brands":["Toyota","Honda"],"years":[2018,2019,2020]}'>{{ old('compatibility', is_array($part->compatibility) ? json_encode($part->compatibility, JSON_PRETTY_PRINT) : $part->compatibility) }}</textarea>
            @error('compatibility')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Указывай совместимость в формате JSON для будущего API</div>
        </div>
        <div class="col-12">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                      rows="3">{{ old('description', $part->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">💾 Сохранить изменения</button>
        <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary">Отмена</a>
    </div>
</form>
@endsection