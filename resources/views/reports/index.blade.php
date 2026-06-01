@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Отчёты</h4>
    <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-app">Скачать CSV</a>
</div>

<!-- Фильтры -->
<div class="card card-app mb-4">
    <div class="card-header fw-bold">Параметры отчёта</div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small text-muted">Период группировки</label>
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>По неделям</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>По месяцам</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>По годам</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Автомобиль</label>
                <select name="car_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Все автомобили</option>
                    @foreach($cars as $id => $name)
                        <option value="{{ $id }}" {{ $carId == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Категория расходов</label>
                <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all">Все категории</option>
                    <option value="fuel" {{ $category == 'fuel' ? 'selected' : '' }}>Топливо</option>
                    <option value="wash" {{ $category == 'wash' ? 'selected' : '' }}>Мойка</option>
                    <option value="repair" {{ $category == 'repair' ? 'selected' : '' }}>Ремонт</option>
                    <option value="maintenance" {{ $category == 'maintenance' ? 'selected' : '' }}>ТО</option>
                    <option value="insurance" {{ $category == 'insurance' ? 'selected' : '' }}>Страховка</option>
                    <option value="other" {{ $category == 'other' ? 'selected' : '' }}>Прочее</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Диапазон дат</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    <span class="input-group-text">—</span>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    <button type="submit" class="btn btn-outline-app">Применить</button>
                </div>
            </div>
            <!-- Кнопка сброса фильтров -->
<div class="mt-3">
    <a href="{{ route('reports') }}" class="btn btn-outline-secondary btn-sm">
        🔄 Сбросить фильтры
    </a>
</div>
        </form>
    </div>
</div>

<!-- KPI карточки -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-app">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Всего потрачено</h6>
                <h3 class="mb-0 fw-bold">{{ number_format($totalSum ?? 0, 0, '.', ' ') }} ₽</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-app">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Количество операций</h6>
                <h3 class="mb-0 fw-bold">{{ $totalCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-app">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Средний чек</h6>
                <h3 class="mb-0 fw-bold">
                    {{ $totalCount > 0 ? number_format(($totalSum ?? 0) / $totalCount, 0, '.', ' ') : 0 }} ₽
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Графики -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card card-app">
            <div class="card-header fw-bold">Динамика расходов</div>
            <div class="card-body">
                @if(empty($labels) || empty($totals))
                    <div class="text-center text-muted py-5">Нет данных для отображения</div>
                @else
                    <canvas id="barChart" style="max-height: 300px;"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-app">
            <div class="card-header fw-bold">Расходы по категориям</div>
            <div class="card-body">
                @if(empty($pieLabels) || empty($pieValues))
                    <div class="text-center text-muted py-5">Нет данных для отображения</div>
                @else
                    <canvas id="pieChart" style="max-height: 300px;"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Детализация -->
<div class="card card-app">
    <div class="card-header fw-bold">Детализация по категориям</div>
    <div class="card-body p-0">
        <table class="table table-app mb-0">
            <thead>
                <tr>
                    <th>Категория</th>
                    <th class="text-end">Сумма</th>
                    <th class="text-end">Доля в расходах</th>
                    <th class="text-end">Количество операций</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byCategory ?? [] as $item)
                @php 
                    $percent = ($totalSum ?? 0) > 0 ? round((($item->total ?? 0) / $totalSum) * 100) : 0;
                    $categoryNames = [
                        'fuel' => 'Топливо', 'wash' => 'Мойка', 'repair' => 'Ремонт',
                        'maintenance' => 'ТО', 'insurance' => 'Страховка', 'other' => 'Прочее'
                    ];
                @endphp
                <tr>
                    <td>{{ $categoryNames[$item->category] ?? $item->category }}</td>
                    <td class="text-end fw-bold">{{ number_format($item->total ?? 0, 2, '.', ' ') }} ₽</td>
                    <td class="text-end">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-secondary" role="progressbar" 
                                 style="width: {{ $percent }}%" 
                                 aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $percent }}%
                            </div>
                        </div>
                    </td>
                    <td class="text-end">{{ $item->count ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Нет данных для отображения</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Данные для графиков
    const barLabels = {!! json_encode($labels ?? []) !!};
    const barData = {!! json_encode($totals ?? []) !!};
    const pieLabels = {!! json_encode($pieLabels ?? []) !!};
    const pieValues = {!! json_encode($pieValues ?? []) !!};
    
    // 🔑 Русские названия категорий
    const categoryNames = {
        'fuel': 'Топливо',
        'wash': 'Мойка',
        'repair': 'Ремонт',
        'maintenance': 'ТО',
        'insurance': 'Страховка',
        'other': 'Прочее'
    };
    
    // 🔑 Красивые цвета (гармонируют с синей темой)
    const categoryColors = {
        'fuel': '#f59e0b',         // Оранжевый
        'wash': '#06b6d4',         // Бирюзовый
        'repair': '#ef4444',       // Красный
        'maintenance': '#1e40af',  // Тёмно-синий
        'insurance': '#8b5cf6',    // Фиолетовый
        'other': '#6b7280'         // Серый
    };

    // Столбчатый график (динамика)
    if(barLabels.length > 0 && barData.length > 0) {
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Расходы (₽)',
                    data: barData,
                    backgroundColor: '#2e2d2d',
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('ru-RU') + ' ₽';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('ru-RU') + ' ₽';
                            }
                        },
                        grid: { color: '#e1e1df' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 🔵 Круговая диаграмма (доли категорий)
    if(pieLabels.length > 0 && pieValues.length > 0) {
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        
        // Преобразуем английские метки в русские и подбираем цвета
        const translatedLabels = pieLabels.map(label => categoryNames[label] || label);
        const colors = pieLabels.map(label => categoryColors[label] || '#6b7280');
        
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: translatedLabels,  // ← Русские названия!
                datasets: [{
                    data: pieValues,
                    backgroundColor: colors,  // ← Красивые цвета!
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Open Sans', sans-serif", size: 12 },
                            padding: 15,
                            usePointStyle: true  // ← Точки вместо квадратиков
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                return label + ': ' + value.toLocaleString('ru-RU') + ' ₽ (' + percent + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
});
</script>
@endpush