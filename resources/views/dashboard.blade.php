@extends('layouts.app')

@section('content')
<!-- 📊 Карточки статистики (исправлено: col-4 для мобильной строки) -->
<div class="row g-2 mb-3">
    <div class="col-4">
        <div class="kpi-card">
            <div class="kpi-icon"></div>
            <div class="kpi-value">{{ auth()->user()->cars()->count() }}</div>
            <div class="kpi-label">Авто</div>
        </div>
    </div>
    <div class="col-4">
        <div class="kpi-card">
            <div class="kpi-icon"></div>
            <div class="kpi-value">{{ number_format(auth()->user()->expenses()->whereMonth('date', now()->month)->sum('amount'), 0, '.', ' ') }} ₽</div>
            <div class="kpi-label">Расходы за месяц</div>
        </div>
    </div>
    <div class="col-4">
        <div class="kpi-card">
            <div class="kpi-icon"></div>
            <div class="kpi-value">{{ auth()->user()->reminders()->where('status', 'active')->count() }}</div>
            <div class="kpi-label">Активные напоминания</div>
        </div>
    </div>
</div>

<!-- ⚡ Быстрые действия -->
<h4 class="mb-3">Быстрое добавление</h4>
<div class="row g-3 mb-4">
    <!-- Форма расхода -->
    <div class="col-md-6">
        <div class="card card-app h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Новый расход</span>
                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-outline-app">Полная форма</a>
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
                                <option disabled>Нет авто</option>
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
                        <input type="text" name="description" class="form-control form-control-sm" placeholder="Например: Лукойл 95">
                    </div>
                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                    
                    <button type="submit" class="btn btn-app btn-sm w-100">Записать</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Форма напоминания -->
    <div class="col-md-6">
        <div class="card card-app h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Новое напоминание</span>
                <a href="{{ route('reminders.create') }}" class="btn btn-sm btn-outline-app">Полная форма</a>
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
                                <option disabled>Нет авто</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Тип *</label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="oil">Масло</option>
                            <option value="coolant">Антифриз</option>
                            <option value="tires">Шины</option>
                            <option value="brake_fluid">Тормоза</option>
                            <option value="inspection">Техосмотр</option>
                            <option value="custom">Прочее</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Заголовок *</label>
                        <input type="text" name="title" class="form-control form-control-sm" required placeholder="Например: Замена фильтра">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Дата *</label>
                        <input type="date" name="due_date" class="form-control form-control-sm" value="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                    </div>

                    <button type="submit" class="btn btn-app btn-sm w-100">Создать</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 📋 Лента последних действий -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Последние действия</h4>
    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-app">Все записи</a>
</div>

<!-- Таблица с обёрткой для скролла -->
<div class="card card-app">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-app table-hover mb-0">
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
                        $expenses = auth()->user()->expenses()
                            ->with('car:id,brand,model')
                            ->latest('date')
                            ->take(3)
                            ->get();
                        
                        $reminders = auth()->user()->reminders()
                            ->where('status', 'active')
                            ->latest('due_date')
                            ->take(2)
                            ->get();
                        
                        $categoryNames = [
                            'fuel' => 'Топливо', 'wash' => 'Мойка', 'repair' => 'Ремонт',
                            'maintenance' => 'ТО', 'insurance' => 'Страховка', 'other' => 'Прочее'
                        ];
                        $typeNames = [
                            'oil' => 'Масло', 'coolant' => 'Антифриз', 'tires' => 'Шины',
                            'brake_fluid' => 'Тормоза', 'inspection' => 'Техосмотр', 'custom' => 'Прочее'
                        ];
                        
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
                            <td><span class="badge badge-app badge-app-secondary">{{ $item['type'] }}</span></td>
                            <td>{{ $item['desc'] }}</td>
                            <td class="text-end fw-bold">{{ $item['val'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Пока нет активности</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection