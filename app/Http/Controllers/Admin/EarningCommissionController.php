<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EarningCommissionStructure;
use App\Models\MiningPlan;
use Illuminate\Http\Request;

class EarningCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissions = EarningCommissionStructure::with('miningPlan')
            ->orderBy('level')
            ->orderBy('mining_plan_id')
            ->get();
        return view('admin.pages.earning-commission.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = MiningPlan::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.earning-commission.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|integer|min:1|max:5',
            'level_name' => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'mining_plan_id' => 'nullable|exists:mining_plans,id',
            'is_active' => 'boolean',
        ]);

        // Check for unique combination of level and mining_plan_id
        $existing = EarningCommissionStructure::where('level', $request->level)
            ->where('mining_plan_id', $request->mining_plan_id ?: null)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['level' => 'A commission structure for this level and plan combination already exists.']);
        }

        EarningCommissionStructure::create([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'commission_rate' => $request->commission_rate,
            'mining_plan_id' => $request->mining_plan_id ?: null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.earning-commission.index')
            ->with('success', 'Earning commission structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commission = EarningCommissionStructure::with('miningPlan')->findOrFail($id);
        $plans = MiningPlan::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.earning-commission.edit', compact('commission', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commission = EarningCommissionStructure::findOrFail($id);

        $request->validate([
            'level' => 'required|integer|min:1|max:5',
            'level_name' => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'mining_plan_id' => 'nullable|exists:mining_plans,id',
            'is_active' => 'boolean',
        ]);

        // Check for unique combination of level and mining_plan_id (excluding current record)
        $existing = EarningCommissionStructure::where('level', $request->level)
            ->where('mining_plan_id', $request->mining_plan_id ?: null)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['level' => 'A commission structure for this level and plan combination already exists.']);
        }

        $commission->update([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'commission_rate' => $request->commission_rate,
            'mining_plan_id' => $request->mining_plan_id ?: null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.earning-commission.index')
            ->with('warning', 'Earning commission structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commission = EarningCommissionStructure::findOrFail($id);
        $commission->delete();

        return redirect()->route('admin.earning-commission.index')
            ->with('danger', 'Earning commission structure deleted successfully.');
    }
}

