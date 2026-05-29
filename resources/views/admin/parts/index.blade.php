@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>📦 Справочник запчастей</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parts.create') }}" class="btn btn-success">+ Добавить</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Назад</a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light"><tr><th>Название</th><th>Бренд</th><th>Артикул</th><th>Цена</th><th>Действия</th></tr></thead>
            <tbody>
                @forelse($parts as $part)
                <tr>
                    <td>{{ $part->name }}</td>
                    <td>{{ $part->brand ?? '-' }}</td>
                    <td><code>{{ $part->sku ?? '-' }}</code></td>
                    <td>{{ $part->price ? number_format($part->price, 2).' ₽' : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.parts.edit', $part) }}" class="btn btn-sm btn-outline-primary">✏️</a>
                        <form action="{{ route('admin.parts.destroy', $part) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить запчасть?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Справочник пуст</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $parts->links() }}</div>
</div>
@endsection