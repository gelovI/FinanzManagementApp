<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavingsPlan;

class SavingsPlanController extends Controller
{
    public function index()
    {
        $savingsPlans = SavingsPlan::where('user_id', Auth::id())->get();
        $totalTargetAmount = $savingsPlans->sum('target_amount');
        $totalCurrentAmount = $savingsPlans->sum('current_amount');

        return view('savings.index', compact('savingsPlans', 'totalTargetAmount', 'totalCurrentAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
        ]);

        SavingsPlan::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
        ]);

        return redirect()->route('savings.index')->with('success', 'Sparplan erfolgreich erstellt.');
    }

    public function update(Request $request, SavingsPlan $savingsPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
        ]);

        $savingsPlan->update($request->only('name', 'target_amount', 'current_amount'));

        return redirect()->route('savings.index')->with('success', 'Sparplan erfolgreich aktualisiert.');
    }


    public function destroy(SavingsPlan $savingsPlan)
    {
        $savingsPlan->delete();

        return redirect()->route('savings.index')->with('success', 'Sparplan erfolgreich gelöscht.');
    }
}
