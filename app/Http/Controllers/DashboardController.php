<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Notification;
use App\Models\SavingsPlan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter für Tage
        $filterDays = $request->get('filter');
        $queryDate = $filterDays ? now()->subDays($filterDays) : null;

        // Gesamteinnahmen und -ausgaben
        $totalIncome = Income::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->sum('amount');

        $totalExpense = Expense::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->sum('amount');

        // Kategorien für Einnahmen und Ausgaben
        $incomeCategories = Category::where('type', 'income')->get();
        $expenseCategories = Category::where('type', 'expense')->get();

        // Einnahmen und Ausgaben abrufen
        $recentIncomes = Income::with('category')
            ->where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->get();

        $recentExpenses = Expense::with('category')
            ->where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->get();

        // Rechnungen hinzufügen
        $recentExpenses->each(function ($expense) {
            $expense->hasDocument = !empty($expense->document);
        });

        // Typ zur Unterscheidung hinzufügen
        $recentIncomes->map(fn($income) => $income->type = 'income');
        $recentExpenses->map(fn($expense) => $expense->type = 'expense');

        // Letzte Transaktionen kombinieren und sortieren
        $transactions = $recentIncomes->merge($recentExpenses)->sortByDesc('date');

        return view('dashboard', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'incomeCategories',
            'expenseCategories'
        ));
    }


    public function checkNotifications()
    {
        $userId = Auth::id();

        // 1. Saldo-Benachrichtigung
        $totalIncome = Income::where('user_id', $userId)->sum('amount');
        $totalExpense = Expense::where('user_id', $userId)->sum('amount');
        $saldo = $totalIncome - $totalExpense;

        if ($saldo < ($totalIncome * 0.15)) {
            Notification::create([
                'user_id' => $userId,
                'message' => 'Dein Saldo ist auf 15% gesunken!',
            ]);
        }

        // 2. Sparplan-Benachrichtigung
        $savingsPlans = SavingsPlan::where('user_id', $userId)->get();
        foreach ($savingsPlans as $plan) {
            $progress = ($plan->current_amount / $plan->target_amount) * 100;
            if ($progress >= 90) {
                Notification::create([
                    'user_id' => $userId,
                    'message' => "Dein Sparziel '{$plan->name}' ist zu 90% erfüllt!",
                ]);
            }
        }
    }
}
