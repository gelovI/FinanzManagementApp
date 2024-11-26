<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\Category;


class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())->orderBy('date', 'desc')->get();

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        Expense::create([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard.transactions')->with('success', 'Ausgabe erfolgreich erstellt.');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $expense->update($request->all());

        return redirect()->back()->with('success', 'Einnahme erfolgreich aktualisiert!');
    }
}
