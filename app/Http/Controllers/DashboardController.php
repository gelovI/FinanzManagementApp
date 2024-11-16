<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;

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
}
