<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'document' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Falls eine Datei hochgeladen wird
        $documentPath = null;
        if ($request->hasFile('document')) {
            // Datei speichern und den Pfad erhalten
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        Expense::create([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'description' => $request->description,
            'document' => $documentPath,
        ]);

        return redirect()->route('dashboard.transactions')->with('success', 'Ausgabe erfolgreich erstellt.');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Datei aktualisieren
        $documentPath = $expense->document;
        if ($request->hasFile('document')) {
            // Alte Datei löschen, falls vorhanden
            if ($documentPath) {
                Storage::disk('public')->delete($documentPath);
            }

            // Neue Datei speichern
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        // Datensatz aktualisieren
        $expense->update([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
            'document' => $documentPath,
        ]);

        return redirect()->route('dashboard.transactions')->with('success', 'Ausgabe erfolgreich aktualisiert.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('reports.index')->with('success', 'Ausgabe erfolgreich gelöscht.');
    }
}
