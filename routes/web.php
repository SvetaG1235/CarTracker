<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CarRelatedController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// 🌐 Гостевая страница
Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migrations', function () {
    if (!app()->environment('production')) {
        return 'Only available in production';
    }
    
    try {
        Artisan::call('migrate --force');
        $output = Artisan::output();
        
        return '<pre style="font-size:12px;">' . 
               "<strong>✅ Миграции выполнены!</strong>\n\n" . 
               $output . 
               "\n\n<strong>⚠️ УДАЛИ ЭТОТ РОУТ ПОСЛЕ ПРОВЕРКИ!</strong>" .
               '</pre>';
    } catch (\Exception $e) {
        return '<pre style="color:red;font-size:12px;"><strong>❌ Ошибка:</strong> ' . 
               $e->getMessage() . '</pre>';
    }
});// ->middleware('auth'); // <-- закомментировано для первого запуска

// 🖥️ Дашборд
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 👤 Профиль
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🚗 Основные модули
Route::middleware('auth')->group(function () {
    Route::resource('cars', CarController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('reminders', ReminderController::class);
    
    Route::patch('/reminders/{reminder}/done', [ReminderController::class, 'markDone'])->name('reminders.done');
    
    // 📊 Отчёты
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // 🔹 Водители
    Route::post('/drivers', [CarRelatedController::class, 'storeDriver'])->name('drivers.store');
    Route::delete('/drivers/{driver}', [CarRelatedController::class, 'destroyDriver'])->name('drivers.destroy');

    // 🔹 Страховка
    Route::post('/insurances', [CarRelatedController::class, 'storeInsurance'])->name('insurances.store');
    Route::delete('/insurances/{insurance}', [CarRelatedController::class, 'destroyInsurance'])->name('insurances.destroy');

    // 🔹 Сервисные карты
    Route::post('/service-cards', [CarRelatedController::class, 'storeServiceCard'])->name('service_cards.store');
    Route::delete('/service-cards/{service_card}', [CarRelatedController::class, 'destroyServiceCard'])->name('service_cards.destroy');
});

// 🔐 АДМИН-ПАНЕЛЬ
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    Route::get('/debug/reminders', function () {
        Artisan::call('reminders:check', ['--days' => 7]);
        return '<pre>' . Artisan::output() . '</pre>';
    })->name('admin.debug.reminders');
});

require __DIR__.'/auth.php';