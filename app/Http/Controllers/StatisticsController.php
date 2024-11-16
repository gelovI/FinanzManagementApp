<?php

namespace App\Http\Controllers;

use App\Models\SavingsPlan;
use App\Models\Income;
use App\Models\Expense;

class StatisticsController extends Controller
{
    // Statistik für Sparpläne
    public function savingsStatistics()
    {
        $savingsPlans = SavingsPlan::all();

        $statistics = $savingsPlans->map(function ($plan) {
            return [
                'name' => $plan->name,
                'target_amount' => $plan->target_amount,
                'current_amount' => $plan->current_amount,
            ];
        });

        return response()->json($statistics);
    }

    // Statistik für Einnahmen und Ausgaben
    public function incomeExpenseStatistics()
    {
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');

        return response()->json([
            'income' => $totalIncome,
            'expense' => $totalExpense,
        ]);
    }

    // Einnahmen/Ausgaben nach Kategorie
    public function categoryStatistics()
    {
        $expensesByCategory = Expense::with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($group) {
                return $group->sum('amount');
            });

        return response()->json($expensesByCategory);
    }

    public function getMonthlyIncomeExpense()
    {
        $currentYear = now()->year;

        // Gruppiere Einnahmen nach Monat
        $income = Income::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Gruppiere Ausgaben nach Monat
        $expense = Expense::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Erstelle die Datenstruktur für alle Monate
        $months = collect(range(1, 12))->map(function ($month) use ($income, $expense) {
            return [
                'month' => $month,
                'income' => $income[$month] ?? 0, // Einnahmen für den Monat oder 0
                'expense' => $expense[$month] ?? 0, // Ausgaben für den Monat oder 0
            ];
        });

        return response()->json($months);
    }
}
