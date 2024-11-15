<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public static function getSavingsProgress()
    {
        $savingsPlans = SavingsPlan::all();
        return $savingsPlans->map(function ($plan) {
            return [
                'name' => $plan->name,
                'progress' => $plan->current_amount / $plan->target_amount * 100,
            ];
        });
    }

    public static function getIncomeVsExpense()
    {
        return [
            'income' => Income::sum('amount'),
            'expense' => Expense::sum('amount'),
        ];
    }
}

