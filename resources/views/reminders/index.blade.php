@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Напоминания о ТО</h4>
    <a href="{{ route('reminders.create') }}" class="btn btn-app">Создать напоминание</a>
</div>

{{-- Если совсем пусто --}}
@if(collect($groups)->flatten()->isEmpty())
    <div class="text-center text-muted py-5 card card-app">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Нет напоминаний</h4>
            <p class="mb-3">Создайте первое напоминание о ТО или замене жидкостей</p>
            <a href="{{ route('reminders.create') }}" class="btn btn-outline-app">Создать напоминание</a>
        </div>
    </div>
@else
    <div class="accordion" id="remindersAccordion">
        @php
            $groupsConfig = [
                'oil'         => ['label' => 'Замена масла', 'badge' => 'badge-app badge-app-warning'],
                'coolant'     => ['label' => 'Антифриз', 'badge' => 'badge-app badge-app-info'],
                'brake_fluid' => ['label' => 'Тормозная жидкость', 'badge' => 'badge-app badge-app-danger'],
                'tires'       => ['label' => 'Шины', 'badge' => 'badge-app badge-app-secondary'],
                'inspection'  => ['label' => 'Техосмотр', 'badge' => 'badge-app badge-app-primary'],
                'custom'      => ['label' => 'Прочее', 'badge' => 'badge-app badge-app-secondary'],
                'done'        => ['label' => 'Выполненные', 'badge' => 'badge-app badge-app-secondary'],
            ];
        @endphp

        @foreach($groupsConfig as $key => $config)
            @if($groups[$key]->isNotEmpty())
            <div class="accordion-item card-app mb-3 border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $key !== 'oil' ? 'collapsed' : '' }} fw-semibold" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}">
                        {{ $config['label'] }}
                        <span class="badge {{ $config['badge'] }} ms-2">{{ $groups[$key]->count() }}</span>
                    </button>
                </h2>
                <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key === 'oil' ? 'show' : '' }}" data-bs-parent="#remindersAccordion">
                    <div class="accordion-body p-2" style="background: var(--app-light);">
                        
                        @foreach($groups[$key] as $rem)
                        <!-- Карточка напоминания -->
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 mb-2 rounded border" style="background: #fff;">
                            <div class="me-3">
                                <h6 class="mb-1 fw-bold">
                                    {{ $rem->title }}
                                    <!-- ℹ️ Тултип с подсказкой -->
                                    <button class="btn btn-sm btn-link p-0 ms-1 text-muted" type="button" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" 
                                            title="@php echo \App\Helpers\MaintenanceInfo::getInfo($rem->type)['why'] ?? 'Информация недоступна'; @endphp">
                                        <small>ℹ️</small>
                                    </button>
                                </h6>
                                <small class="text-muted d-block mb-1">
                                    @if($rem->car) {{ $rem->car->brand }} {{ $rem->car->model }} • @endif
                                    
                                    @if($rem->is_mileage_based)
                                        Каждые <strong>{{ $rem->mileage_interval }} км</strong> (след: {{ $rem->next_mileage_due }} км)
                                    @else
                                        До: <strong>{{ $rem->due_date?->format('d.m.Y') }}</strong>
                                        @if($rem->due_date?->isPast() && $rem->status === 'active')
                                            <span class="text-danger fw-bold ms-1">(Просрочено!)</span>
                                        @endif
                                    @endif
                                    
                                    @if($rem->is_recurring) <span class="badge badge-app badge-app-warning ms-1">Повтор</span> @endif
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                @if($rem->status === 'active')
                                    <form action="{{ route('reminders.done', $rem) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-app" title="Отметить выполненным">✓</button>
                                    </form>
                                @endif
                                <form action="{{ route('reminders.destroy', $rem) }}" method="POST" onsubmit="return confirm('Удалить напоминание?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-app text-danger" title="Удалить">Удалить</button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
@endif

<!-- Инициализация тултипов Bootstrap (после контента) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, { trigger: 'hover focus' });
    });
});
</script>
@endsection