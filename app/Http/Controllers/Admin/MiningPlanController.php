<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiningPlan;
use Illuminate\Http\Request;

class MiningPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = MiningPlan::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.pages.mining-plan.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.mining-plan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'required|numeric|min:0|gte:min_investment',
            'daily_roi_min' => 'required|numeric|min:0|max:100',
            'daily_roi_max' => 'required|numeric|min:0|max:100|gte:daily_roi_min',
            'hourly_rate' => 'nullable|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        MiningPlan::create([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'subtitle' => $request->subtitle,
            'icon_class' => $request->icon_class,
            'min_investment' => $request->min_investment,
            'max_investment' => $request->max_investment,
            'daily_roi_min' => $request->daily_roi_min,
            'daily_roi_max' => $request->daily_roi_max,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.mining-plan.index')
            ->with('success', 'Mining plan created successfully.');
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
        $plan = MiningPlan::findOrFail($id);
        return view('admin.pages.mining-plan.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plan = MiningPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'required|numeric|min:0|gte:min_investment',
            'daily_roi_min' => 'required|numeric|min:0|max:100',
            'daily_roi_max' => 'required|numeric|min:0|max:100|gte:daily_roi_min',
            'hourly_rate' => 'nullable|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $plan->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'subtitle' => $request->subtitle,
            'icon_class' => $request->icon_class,
            'min_investment' => $request->min_investment,
            'max_investment' => $request->max_investment,
            'daily_roi_min' => $request->daily_roi_min,
            'daily_roi_max' => $request->daily_roi_max,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.mining-plan.index')
            ->with('warning', 'Mining plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plan = MiningPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.mining-plan.index')
            ->with('danger', 'Mining plan deleted successfully.');
    }
}

















