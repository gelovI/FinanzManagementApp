<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;

class ReportController extends Controller
{
    public function index()
    {
        $incomes = Income::orderBy('date', 'desc')->get();
        $expenses = Expense::orderBy('date', 'desc')->get();
        $categories = Category::all();

        return view('reports.index', compact('incomes', 'expenses', 'categories'));
    }
}
