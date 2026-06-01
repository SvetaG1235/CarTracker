<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Car;
use App\Http\Requests\StoreExpenseRequest;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->with('car:id,brand,model')
            ->latest('date')->paginate(15);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        return view('expenses.create', compact('cars'));
    }

public function store(StoreExpenseRequest $request)
{
    auth()->user()->expenses()->create($request->validated());
    
    $back = $request->boolean('redirect_back') ? back() : redirect()->route('expenses.index');
    return $back->with('success', 'Расход записан');
}

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) abort(403, 'Нет прав доступа');

        $cars = Car::where('user_id', auth()->id())->get();
        return view('expenses.edit', compact('expense', 'cars'));
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) abort(403);
        $expense->delete();
        return back()->with('success', 'Запись удалена');
    }
}