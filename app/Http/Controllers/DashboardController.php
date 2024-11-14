<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter abrufen
        $filter = $request->get('filter');
        dd($filter);
        $queryDate = now();

        if ($filter) {
            $days = (int) $filter;
            $queryDate = now()->subDays($days);
        }

        $incomes = Income::where('user_id', Auth::id())
            ->where('date', '>=', $queryDate)
            ->select('amount', 'category_id', 'date', 'description', 'id', DB::raw("'income' as type"))
            ->with('category')
            ->get();

        $expenses = Expense::where('user_id', Auth::id())
            ->where('date', '>=', $queryDate)
            ->select('amount', 'category_id', 'date', 'description', 'id', DB::raw("'expense' as type"))
            ->with('category')
            ->get();

        $transactions = $incomes->merge($expenses)->sortByDesc('date');

        return view('dashboard', compact('transactions'));
    }
}
