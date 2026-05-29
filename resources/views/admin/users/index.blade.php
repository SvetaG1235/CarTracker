@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>👥 Пользователи системы</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Назад</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Действия</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <select name="role" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Пользователь</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Администратор</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пользователя? Все его данные тоже будут удалены.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">️</button>
                        </form>
                        @else
                            <span class="badge bg-secondary">Вы</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Нет пользователей</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $users->links() }}</div>
</div>
@endsection