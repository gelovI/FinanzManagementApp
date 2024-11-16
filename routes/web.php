<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\SavingsPlanController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DashboardController;
use App\Models\Income;
use App\Models\Expense;
use App\Models\SavingsPlan;
use App\Models\Category;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource('incomes', IncomeController::class);
Route::resource('expenses', ExpenseController::class);

Route::get('/dashboard', function () {
    $categories = Category::all();
    $totalIncome = Income::where('user_id', Auth::id())->sum('amount');
    $totalExpense = Expense::where('user_id', Auth::id())->sum('amount');
    return view('dashboard', compact('categories', 'totalIncome', 'totalExpense'));
})->name('dashboard');


Route::get('/dashboard', function (Request $request) {
    $filterDays = $request->query('filter');

    // Berechne das Filterdatum, wenn ein `filter`-Wert angegeben ist
    $filterDate = $filterDays ? now()->subDays($filterDays) : null;

    // Gesamteinnahmen
    $totalIncome = Income::where('user_id', Auth::id())
        ->when($filterDate, function ($query) use ($filterDate) {
            $query->whereDate('date', '>=', $filterDate);
        })
        ->sum('amount');

    // Gesamtausgaben
    $totalExpense = Expense::where('user_id', Auth::id())
        ->when($filterDate, function ($query) use ($filterDate) {
            $query->whereDate('date', '>=', $filterDate);
        })
        ->sum('amount');

    // Einnahmen abrufen
    $recentIncomes = Income::where('user_id', Auth::id())
        ->when($filterDate, function ($query) use ($filterDate) {
            $query->whereDate('date', '>=', $filterDate);
        })
        ->latest('date')
        ->get();

    // Ausgaben abrufen
    $recentExpenses = Expense::where('user_id', Auth::id())
        ->when($filterDate, function ($query) use ($filterDate) {
            $query->whereDate('date', '>=', $filterDate);
        })
        ->latest('date')
        ->get();

    // Letzte Transaktionen kombinieren und sortieren
    $recentTransactions = $recentIncomes->merge($recentExpenses)
        ->sortByDesc('date')
        ->values();

    return view('dashboard', compact('totalIncome', 'totalExpense', 'recentTransactions'));
})->name('dashboard');

Route::get('/savings-plans', [SavingsPlanController::class, 'index'])->name('savings.index');
Route::post('/savings-plans', [SavingsPlanController::class, 'store'])->name('savings.store');
Route::resource('savings', SavingsPlanController::class);
Route::put('/savings-plans/{savingsPlan}', [SavingsPlanController::class, 'update'])->name('savings.update');
Route::delete('/savings-plans/{savingsPlan}', [SavingsPlanController::class, 'destroy'])->name('savings.destroy');

Route::get('/statistics/savings', [StatisticsController::class, 'savingsStatistics']);
Route::get('/statistics/income-expense', [StatisticsController::class, 'incomeExpenseStatistics']);
Route::get('/statistics/categories', [StatisticsController::class, 'categoryStatistics']);
Route::get('/statistics', function () {
    return view('statistics.index');
})->name('statistics.index');
Route::get('/statistics/monthly-income-expense', [StatisticsController::class, 'getMonthlyIncomeExpense']);
