<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        $categories = Category::where('type', 'income')->get();
        return view('incomes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        Income::create([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard.transactions')->with('success', 'Einnahme erfolgreich erstellt.');
    }

    public function update(Request $request, Income $income)
    {
        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $income->update($request->all());

        return redirect()->route('reports.index')->with('success', 'Einnahme erfolgreich aktualisiert.');
    }

    public function edit($id)
    {
        // Für Benutzer, die die URL direkt aufrufen.
        return redirect()->route('reports.index');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('reports.index')->with('success', 'Einnahme erfolgreich gelöscht.');
    }
}
