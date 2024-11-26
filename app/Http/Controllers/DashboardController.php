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
        // Gesamteinnahmen und -ausgaben
        $totalIncome = Income::where('user_id', Auth::id())->sum('amount');
        $totalExpense = Expense::where('user_id', Auth::id())->sum('amount');

        // Kategorien für Einnahmen und Ausgaben
        $categories = Category::all();

        $filterDays = $request->get('filter');

        // Berechne Filterdatum
        $queryDate = $filterDays ? now()->subDays($filterDays) : null;

        // Gesamteinnahmen
        $totalIncome = Income::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->sum('amount');

        // Gesamtausgaben
        $totalExpense = Expense::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->sum('amount');

        // Kategorien
        $categories = Category::all();

        // Einnahmen und Ausgaben abrufen
        $recentIncomes = Income::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->get();

        $recentExpenses = Expense::where('user_id', Auth::id())
            ->when($queryDate, fn($query) => $query->where('date', '>=', $queryDate))
            ->get();

        // Letzte Transaktionen kombinieren
        $transactions = $recentIncomes->merge($recentExpenses)->sortByDesc('date');

        return view('dashboard', compact('transactions', 'totalIncome', 'totalExpense', 'categories', 'recentIncomes', 'recentExpenses'));
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
