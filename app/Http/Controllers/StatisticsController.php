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
}
