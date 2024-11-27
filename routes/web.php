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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

use App\Models\Income;
use App\Models\Expense;
use App\Models\SavingsPlan;
use App\Models\Category;
use App\Models\Notification;

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

Route::get('login', function () {
    return view('login');
})->name('login');

require __DIR__ . '/auth.php';

Route::resource('incomes', IncomeController::class);
Route::resource('expenses', ExpenseController::class);

Route::resource('incomes', IncomeController::class)->except(['edit', 'create']);
Route::resource('expenses', ExpenseController::class)->except(['edit', 'create']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard.transactions', [DashboardController::class, 'index'])->name('dashboard.transactions');

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

Route::get('/notifications', function () {
    $userId = Auth::id();

    $totalIncome = Income::where('user_id', $userId)->sum('amount');
    $totalExpense = Expense::where('user_id', $userId)->sum('amount');
    $balance = $totalIncome - $totalExpense;

    // Prüfen: Kontostand unter 15%
    if ($balance <= 300) {
        Notification::updateOrCreate(
            [
                'user_id' => $userId,
                'message' => 'Ihr Kontostand ist unter 300 Euro gefallen.',
            ],
            [
                'is_read' => false,
            ]
        );
    }

    // Sparpläne prüfen
    $savingsPlans = SavingsPlan::where('user_id', $userId)->get();
    foreach ($savingsPlans as $plan) {
        if ($plan->current_amount / $plan->target_amount >= 0.9) {
            Notification::updateOrCreate(
                [
                    'user_id' => $userId,
                    'message' => 'Ihr Sparplan "' . $plan->name . '" hat 90% seines Ziels erreicht!',
                ],
                [
                    'is_read' => false,
                ]
            );
        }
    }

    return response()->json(Notification::where('user_id', $userId)
        ->where('is_read', false)
        ->get());
});

Route::post('/notifications/store', [NotificationController::class, 'store'])->name('notifications.store');
Route::put('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
