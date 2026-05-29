@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>{{ $car->brand }} {{ $car->model }}</h4>
    <div class="btn-group">
        <a href="{{ route('cars.edit', $car) }}" class="btn btn-makk">Изменить</a>
        <a href="{{ route('cars.index') }}" class="btn btn-outline-makk">Назад</a>
    </div>
</div>

<!-- Вкладки -->
<ul class="nav nav-tabs mb-4" id="carTabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info">Информация</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-drivers">Водители</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-insurance">Страховка</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-service">Сервисные карты</button></li>
</ul>

<div class="tab-content">
    <!-- Информация -->
    <div class="tab-pane fade show active" id="tab-info">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-makk">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Год:</strong> {{ $car->year ?? '—' }}</li>
                        <li class="list-group-item"><strong>VIN:</strong> <code>{{ $car->vin ?? '—' }}</code></li>
                        <li class="list-group-item"><strong>Гос. номер:</strong> {{ $car->plate ?? '—' }}</li>
                        <li class="list-group-item bg-light"><strong>Пробег:</strong> {{ number_format($car->mileage) }} км</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-makk">
                    <div class="card-header">Статистика</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h4 class="fw-bold mb-0">{{ number_format($car->expenses()->sum('amount'), 0) }} ₽</h4>
                                <small class="text-muted">Всего расходов</small>
                            </div>
                            <div class="col-4 border-end">
                                <h4 class="fw-bold mb-0">{{ $car->expenses()->count() }}</h4>
                                <small class="text-muted">Записей</small>
                            </div>
                            <div class="col-4">
                                <h4 class="fw-bold mb-0">{{ $car->reminders()->where('status','active')->count() }}</h4>
                                <small class="text-muted">Напоминаний</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Водители -->
    <div class="tab-pane fade" id="tab-drivers">
        <div class="card card-makk mb-3">
            <div class="card-body">
                <form action="{{ route('drivers.store') }}" method="POST" class="row g-2 align-items-end">
                    @csrf <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <div class="col-md-4">
                        <label class="form-label small">ФИО</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">№ прав</label>
                        <input type="text" name="license_number" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Телефон</label>
                        <input type="text" name="phone" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-makk btn-sm w-100">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-makk table-sm">
            <thead><tr><th>ФИО</th><th>№ прав</th><th>Телефон</th><th>Основной</th><th></th></tr></thead>
            <tbody>
                @forelse($car->drivers as $d)
                <tr>
                    <td>{{ $d->full_name }}</td>
                    <td><code>{{ $d->license_number ?? '—' }}</code></td>
                    <td>{{ $d->phone ?? '—' }}</td>
                    <td>{{ $d->is_primary ? '<span class="badge badge-makk badge-makk-success">Да</span>' : '<span class="badge badge-makk badge-makk-secondary">Нет</span>' }}</td>
                    <td>
                        <form action="{{ route('drivers.destroy', $d) }}" method="POST" onsubmit="return confirm('Удалить водителя?')">
                            @csrf @method('DELETE') <button class="btn btn-sm btn-outline-makk text-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty <tr><td colspan="5" class="text-center text-muted py-3">Водители не добавлены</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Страховка -->
    <div class="tab-pane fade" id="tab-insurance">
        <div class="card card-makk mb-3">
            <div class="card-body">
                <form action="{{ route('insurances.store') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                    @csrf <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <div class="col-md-2">
                        <label class="form-label small">Тип</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="osago">ОСАГО</option>
                            <option value="casco">КАСКО</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">№ полиса</label>
                        <input type="text" name="policy_number" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Компания</label>
                        <input type="text" name="company" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Действует до</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">PDF</label>
                        <input type="file" name="policy_file" class="form-control form-control-sm" accept="application/pdf">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-makk btn-sm w-100">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-3">
            @forelse($car->insurances()->latest('end_date')->get() as $ins)
            <div class="col-md-6">
                <div class="card card-makk h-100 border-{{ $ins->is_active ? 'success' : 'secondary' }}">
                    <div class="card-body position-relative">
                        @if(!$ins->is_active)<span class="badge badge-makk badge-makk-secondary position-absolute top-0 end-0 m-2">Истекла</span>@endif
                        <h6 class="card-title fw-bold">{{ strtoupper($ins->type) }} — {{ $ins->company }}</h6>
                        <p class="mb-1 small"><strong>Полис:</strong> {{ $ins->policy_number }}</p>
                        <p class="mb-1 small"><strong>Действует до:</strong> {{ $ins->end_date?->format('d.m.Y') }}</p>
                        @if($ins->policy_file)
                            <a href="{{ asset($ins->policy_file) }}" target="_blank" class="btn btn-sm btn-outline-makk w-100 mt-2">Скачать полис</a>
                        @endif
                        <form action="{{ route('insurances.destroy', $ins) }}" method="POST" class="d-inline mt-2" onsubmit="return confirm('Удалить полис?')">
                            @csrf @method('DELETE') <button class="btn btn-sm btn-outline-makk text-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty <div class="col-12 text-center text-muted py-3">Страховки не добавлены</div>
            @endforelse
        </div>
    </div>

    <!-- Сервисные карты -->
    <div class="tab-pane fade" id="tab-service">
        <div class="card card-makk mb-3">
            <div class="card-body">
                <form action="{{ route('service_cards.store') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                    @csrf <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <div class="col-md-3">
                        <label class="form-label small">Название СТО</label>
                        <input type="text" name="workshop_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">№ карты</label>
                        <input type="text" name="service_card_number" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Телефон</label>
                        <input type="text" name="contact_phone" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Последний визит</label>
                        <input type="date" name="last_visit" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Штрихкод</label>
                        <input type="file" name="barcode_image" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-makk btn-sm w-100">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-3">
            @forelse($car->serviceCards()->latest('last_visit')->get() as $sc)
            <div class="col-md-4">
                <div class="card card-makk h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold mb-1">{{ $sc->workshop_name }}</h6>
                        @if($sc->service_card_number)
                            <span class="badge badge-makk badge-makk-secondary mb-2">№ {{ $sc->service_card_number }}</span>
                        @endif
                        <div class="my-3 bg-light rounded p-2 d-inline-block">
                            @if($sc->barcode_image)
                                <img src="{{ asset($sc->barcode_image) }}" class="img-fluid" style="max-height: 80px;" alt="Штрихкод">
                            @else
                                <div class="text-muted small py-2">Штрихкод не загружен</div>
                            @endif
                        </div>
                        <p class="mb-1 small"><strong>Последний визит:</strong> {{ $sc->last_visit?->format('d.m.Y') ?? '—' }}</p>
                        <div class="d-flex gap-1 justify-content-center">
                            @if($sc->contact_phone)<a href="tel:{{ $sc->contact_phone }}" class="btn btn-sm btn-outline-makk">Позвонить</a>@endif
                        </div>
                        <form action="{{ route('service_cards.destroy', $sc) }}" method="POST" class="mt-2" onsubmit="return confirm('Удалить сервисную карту?')">
                            @csrf @method('DELETE') <button class="btn btn-sm btn-outline-makk text-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty <div class="col-12 text-center text-muted py-3">Сервисные карты не добавлены</div>
            @endforelse
        </div>
    </div>
</div>
@endsection