<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Reminder;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'usersCount'      => User::count(),
            'carsCount'       => Car::count(),
            'expensesTotal'   => Expense::sum('amount'),
            'activeReminders' => Reminder::where('status', 'active')->count(),
        ]);
    }
}