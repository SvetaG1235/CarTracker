@extends('layouts.app')
@section('content')
<h2 class="mb-4">⚙️ Панель администратора</h2>
<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card bg-primary text-white shadow-sm"><div class="card-body"><h6>👥 Пользователей</h6><h3 class="mb-0">{{ $usersCount }}</h3></div></div></div>
    <div class="col-md-3"><div class="card bg-success text-white shadow-sm"><div class="card-body"><h6>🚗 Автомобилей</h6><h3 class="mb-0">{{ $carsCount }}</h3></div></div></div>
    <div class="col-md-3"><div class="card bg-info text-white shadow-sm"><div class="card-body"><h6>💰 Всего расходов</h6><h3 class="mb-0">{{ number_format($expensesTotal, 0, '.', ' ') }} ₽</h3></div></div></div>
    <div class="col-md-3"><div class="card bg-warning text-dark shadow-sm"><div class="card-body"><h6>⏰ Активных напоминаний</h6><h3 class="mb-0">{{ $activeReminders }}</h3></div></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <a href="{{ route('admin.users.index') }}" class="card text-decoration-none shadow-sm h-100 border-0 hover-shadow">
            <div class="card-body"><h5 class="card-title">👥 Управление пользователями</h5><p class="card-text text-muted mb-0">Смена ролей, просмотр списка, удаление аккаунтов</p></div>
        </a>
    </div>

</div>
@endsection