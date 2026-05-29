@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Расходы</h4>
    <a href="{{ route('expenses.create') }}" class="btn btn-makk">Внести расход</a>
</div>

<div class="card card-makk">
    <div class="card-body p-0">
        <table class="table table-makk table-hover mb-0">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Автомобиль</th>
                    <th>Категория</th>
                    <th>Описание</th>
                    <th class="text-end">Сумма</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $catMap = [
                        'fuel' => 'Топливо',
                        'wash' => 'Мойка',
                        'repair' => 'Ремонт',
                        'maintenance' => 'ТО',
                        'insurance' => 'Страховка',
                        'other' => 'Прочее'
                    ];
                @endphp
                @forelse($expenses as $exp)
                <tr>
                    <td>{{ $exp->date?->format('d.m.Y') }}</td>
                    <td>{{ $exp->car?->brand }} {{ $exp->car?->model }}</td>
                    <td>
                        <span class="badge badge-makk badge-makk-secondary text-uppercase">
                            {{ $catMap[$exp->category] ?? $exp->category }}
                        </span>
                    </td>
                    <td>{{ Str::limit($exp->description, 40) }}</td>
                    <td class="text-end fw-bold">{{ number_format($exp->amount, 2, '.', ' ') }} ₽</td>
                    <td class="text-end">
                        <a href="{{ route('expenses.edit', $exp) }}" class="btn btn-sm btn-outline-makk">Изменить</a>
                        <form action="{{ route('expenses.destroy', $exp) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить запись о расходе?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-makk text-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Записи не найдены</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white">{{ $expenses->links() }}</div>
    @endif
</div>
@endsection