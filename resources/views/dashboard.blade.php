@extends('layouts.app')

@section('content')
<!-- 📊 Карточки статистики -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-makk">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Автомобили</h6>
                <h3 class="mb-0 fw-bold">{{ auth()->user()->cars()->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
    <div class="card card-makk">
        <div class="card-body text-center">
            <h6 class="text-muted mb-1">Расходы за месяц</h6>
            <h3 class="mb-0 fw-bold">
                {{ number_format(auth()->user()->expenses()->whereMonth('date', now()->month)->sum('amount'), 0, '.', ' ') }} ₽
            </h3>
        </div>
    </div>
</div>
    <div class="col-md-4">
        <div class="card card-makk">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Активные напоминания</h6>
                <h3 class="mb-0 fw-bold">
                    {{ auth()->user()->reminders()->where('status', 'active')->count() }}
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- ⚡ Быстрые действия -->
<h4 class="mb-3">Быстрое добавление</h4>
<div class="row g-4 mb-4">
    <!-- Форма расхода -->
    <div class="col-md-6">
        <div class="card card-makk h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Новый расход</span>
                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-outline-makk">Полная форма</a>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_back" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">Автомобиль *</label>
                        <select name="car_id" class="form-select form-select-sm" required>
                            @forelse(auth()->user()->cars as $car)
                                <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }}</option>
                            @empty
                                <option disabled>Сначала добавьте автомобиль</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted">Сумма *</label>
                            <input type="number" step="0.01" name="amount" class="form-control form-control-sm" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Категория *</label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="fuel">Топливо</option>
                                <option value="wash">Мойка</option>
                                <option value="repair">Ремонт</option>
                                <option value="maintenance">ТО</option>
                                <option value="insurance">Страховка</option>
                                <option value="other">Прочее</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Описание</label>
                        <input type="text" name="description" class="form-control form-control-sm" placeholder="Например: Лукойл 95, 40л">
                    </div>
                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                    
                    <button type="submit" class="btn btn-makk btn-sm w-100">Записать расход</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Форма напоминания -->
    <div class="col-md-6">
        <div class="card card-makk h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Новое напоминание</span>
                <a href="{{ route('reminders.create') }}" class="btn btn-sm btn-outline-makk">Полная форма</a>
            </div>
            <div class="card-body">
                <form action="{{ route('reminders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_back" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">Привязать к авто</label>
                        <select name="car_id" class="form-select form-select-sm">
                            <option value="">Без привязки</option>
                            @forelse(auth()->user()->cars as $car)
                                <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }}</option>
                            @empty
                                <option disabled>Нет автомобилей</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Тип *</label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="oil">Замена масла</option>
                            <option value="coolant">Антифриз</option>
                            <option value="tires">Шины</option>
                            <option value="brake_fluid">Тормозная жидкость</option>
                            <option value="inspection">Техосмотр</option>
                            <option value="custom">Прочее</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Заголовок *</label>
                        <input type="text" name="title" class="form-control form-control-sm" required placeholder="Например: Замена воздушного фильтра">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Дата напоминания *</label>
                        <input type="date" name="due_date" class="form-control form-control-sm" value="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                    </div>

                    <button type="submit" class="btn btn-makk btn-sm w-100">Создать напоминание</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 📋 Лента последних действий -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Последние действия</h4>
    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-makk">Все записи</a>
</div>

<div class="card card-makk">
    <div class="card-body p-0">
        <table class="table table-makk table-hover mb-0">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Тип</th>
                    <th>Описание</th>
                    <th class="text-end">Значение</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Получаем последние расходы
                    $expenses = auth()->user()->expenses()
                        ->with('car:id,brand,model')
                        ->latest('date')
                        ->take(3)
                        ->get();
                    
                    // Получаем активные напоминания
                    $reminders = auth()->user()->reminders()
                        ->where('status', 'active')
                        ->latest('due_date')
                        ->take(2)
                        ->get();
                    
                    // Словарь русских названий категорий
                    $categoryNames = [
                        'fuel' => 'Топливо',
                        'wash' => 'Мойка',
                        'repair' => 'Ремонт',
                        'maintenance' => 'ТО',
                        'insurance' => 'Страховка',
                        'other' => 'Прочее'
                    ];
                    
                    $typeNames = [
                        'oil' => 'Масло',
                        'coolant' => 'Антифриз',
                        'tires' => 'Шины',
                        'brake_fluid' => 'Тормоза',
                        'inspection' => 'Техосмотр',
                        'custom' => 'Прочее'
                    ];
                    
                    // Формируем общую ленту
                    $feed = collect();
                    
                    $expenses->each(fn($e) => $feed->push([
                        'date' => $e->date,
                        'type' => 'Расход',
                        'desc' => ($e->car?->brand ?? '') . ' ' . ($e->car?->model ?? '') . ' — ' . ($categoryNames[$e->category] ?? $e->category),
                        'val' => number_format($e->amount, 0, '.', ' ') . ' ₽'
                    ]));
                    
                    $reminders->each(fn($r) => $feed->push([
                        'date' => $r->due_date,
                        'type' => 'Напоминание',
                        'desc' => $r->title,
                        'val' => $typeNames[$r->type] ?? ucfirst($r->type)
                    ]));
                    
                    $feed = $feed->sortByDesc('date')->take(5);
                @endphp

                @forelse($feed as $item)
                    <tr>
                        <td>{{ $item['date']?->format('d.m.Y') ?? '—' }}</td>
                        <td>
                            <span class="badge badge-makk badge-makk-secondary">
                                {{ $item['type'] }}
                            </span>
                        </td>
                        <td>{{ $item['desc'] }}</td>
                        <td class="text-end fw-bold">{{ $item['val'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Пока нет активности</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection