@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Автомобили</h4>
    <a href="{{ route('cars.create') }}" class="btn btn-app">Добавить автомобиль</a>
</div>

<div class="card card-app">
    <div class="card-body p-0">
        <table class="table table-app table-hover mb-0">
            <thead>
                <tr>
                    <th>Марка/Модель</th>
                    <th>Год</th>
                    <th>Гос. номер</th>
                    <th>Пробег</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse(auth()->user()->cars()->latest()->paginate(10) as $car)
                <tr class="cursor-pointer" onclick="location.href='{{ route('cars.show', $car) }}'">
                    <td><strong>{{ $car->brand }} {{ $car->model }}</strong></td>
                    <td>{{ $car->year ?? '—' }}</td>
                    <td>{{ $car->plate ?? '—' }}</td>
                    <td>{{ number_format($car->mileage ?? 0) }} км</td>
                    <td class="text-end" onclick="event.stopPropagation()">
                        <div class="btn-group" role="group">
                            <a href="{{ route('cars.show', $car) }}" class="btn btn-sm btn-outline-app">Просмотр</a>
                            <a href="{{ route('cars.edit', $car) }}" class="btn btn-sm btn-outline-app">Изменить</a>
                            <form action="{{ route('cars.destroy', $car) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить автомобиль?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-app text-danger">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Автомобили не добавлены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">
       {{ $cars->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection