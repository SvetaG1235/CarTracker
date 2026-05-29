@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Создать напоминание</h4>
    <a href="{{ route('reminders.index') }}" class="btn btn-outline-makk">Назад к списку</a>
</div>

<form action="{{ route('reminders.store') }}" method="POST" class="card card-makk p-4">
    @csrf
    <input type="hidden" name="redirect_back" value="1">

    <div class="row g-3">
        <div class="col-md-6">
            <label for="car_id" class="form-label">Привязать к авто (опционально)</label>
            <select name="car_id" id="car_id" class="form-select @error('car_id') is-invalid @enderror">
                <option value="">Без привязки</option>
                @foreach(auth()->user()->cars as $car)
                    <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                        {{ $car->brand }} {{ $car->model }}
                    </option>
                @endforeach
            </select>
            @error('car_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="reminderType" class="form-label">Тип напоминания *</label>
            <div class="input-group">
                <select name="type" id="reminderType" class="form-select @error('type') is-invalid @enderror" required>
                    @php
                        $types = [
                            'oil' => 'Замена масла', 'coolant' => 'Антифриз', 'brake_fluid' => 'Тормозная жидкость',
                            'tires' => 'Шины', 'inspection' => 'Техосмотр', 'custom' => 'Прочее'
                        ];
                    @endphp
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-makk" type="button" data-bs-toggle="modal" data-bs-target="#infoModal">
                    ℹ️
                </button>
            </div>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Модальное окно с подсказкой -->
        <div class="modal fade" id="infoModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoTitle">ℹ️ Информация</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="infoContent">
                        <!-- Контент подгрузится через JS -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-makk" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="title" class="form-label">Заголовок *</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title') }}" placeholder="Например: Замена воздушного фильтра" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="due_date" class="form-label">Срок выполнения *</label>
            <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                   value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Повторяющееся -->
        <div class="col-12">
            <input type="hidden" name="is_recurring" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_recurring" value="1" id="recurringCheck" 
                       {{ old('is_recurring') ? 'checked' : '' }}>
                <label class="form-check-label" for="recurringCheck">Повторяющееся напоминание</label>
            </div>
        </div>

        <!-- Блок пробега -->
        <div class="col-12 mt-3 p-3 rounded border" id="mileageBlock" style="display: none; background: var(--makk-light);">
            <h6 class="mb-2 fw-bold">Напоминание по пробегу</h6>
            <input type="hidden" name="is_mileage_based" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_mileage_based" value="1" id="mileageToggle" 
                       {{ old('is_mileage_based') ? 'checked' : '' }}>
                <label class="form-check-label" for="mileageToggle">Привязать к пробегу (авто-генерация)</label>
            </div>
            <div id="mileageFields" style="display: none;">
                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label small">Интервал (км) *</label>
                        <input type="number" name="mileage_interval" class="form-control form-control-sm" 
                               value="{{ old('mileage_interval') }}" placeholder="Например: 10000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Следующий порог</label>
                        <input type="text" class="form-control form-control-sm bg-secondary text-white" id="calcNextMileage" readonly value="—">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-makk">Создать напоминание</button>
        <a href="{{ route('reminders.index') }}" class="btn btn-outline-makk">Отмена</a>
    </div>
</form>

<script>
// Динамическая подгрузка информации при смене типа
document.getElementById('reminderType')?.addEventListener('change', function() {
    updateInfoModal(this.value);
});

// Инициализация при загрузке
updateInfoModal(document.getElementById('reminderType')?.value);

function updateInfoModal(type) {
    const info = @json(\App\Helpers\MaintenanceInfo::getTypes());
    const data = info[type];
    if (!data) return;

    const content = document.getElementById('infoContent');
    document.getElementById('infoTitle').textContent = data.name;
    
    content.innerHTML = `
        <div class="mb-3">
            <h6 class="fw-bold text-primary">Когда менять:</h6>
            <p class="mb-0">${data.when}</p>
        </div>
        <div class="mb-3">
            <h6 class="fw-bold text-success">Зачем:</h6>
            <p>${data.why}</p>
        </div>
        <div class="mb-3">
            <h6 class="fw-bold text-danger">Если не менять:</h6>
            <p>${data.consequences}</p>
        </div>
        <div>
            <h6 class="fw-bold">Советы:</h6>
            <ul class="mb-0">
                ${data.tips.map(tip => `<li>${tip}</li>`).join('')}
            </ul>
        </div>
    `;
}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const carSelect = document.querySelector('select[name="car_id"]');
    const mileageBlock = document.getElementById('mileageBlock');
    const mileageToggle = document.getElementById('mileageToggle');
    const mileageFields = document.getElementById('mileageFields');
    const mileageInterval = document.querySelector('input[name="mileage_interval"]');
    const calcNext = document.getElementById('calcNextMileage');

    function updateUI() {
        const hasCar = carSelect?.value;
        const isMileage = mileageToggle?.checked;
        mileageBlock.style.display = hasCar ? 'block' : 'none';
        mileageFields.style.display = isMileage ? 'block' : 'none';
        if (isMileage && mileageInterval?.value) {
            calcNext.value = `Интервал: ${mileageInterval.value} км`;
        }
    }

    carSelect?.addEventListener('change', updateUI);
    mileageToggle?.addEventListener('change', updateUI);
    mileageInterval?.addEventListener('input', updateUI);
    updateUI();
});
</script>
@endsection