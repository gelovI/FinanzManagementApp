<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavingsPlan;

class SavingsPlanController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        SavingsPlan::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return redirect()->route('savings_plans.index')->with('success', 'Sparplan erfolgreich erstellt.');
    }

    public function update(Request $request, SavingsPlan $savingsPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $savingsPlan->update($request->all());

        return redirect()->route('savings_plans.index')->with('success', 'Sparplan erfolgreich aktualisiert.');
    }

    public function destroy(SavingsPlan $savingsPlan)
    {
        $savingsPlan->delete();

        return redirect()->route('savings_plans.index')->with('success', 'Sparplan erfolgreich gelöscht.');
    }
}
