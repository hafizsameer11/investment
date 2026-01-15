<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EarningCommissionStructure;
use Illuminate\Http\Request;

class EarningCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissions = EarningCommissionStructure::orderBy('level')->get();
        return view('admin.pages.earning-commission.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.earning-commission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|integer|unique:earning_commission_structures,level',
            'level_name' => 'required|string|max:255',
            'commission_rate' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        EarningCommissionStructure::create([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'commission_rate' => $request->commission_rate,
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
        $commission = EarningCommissionStructure::findOrFail($id);
        return view('admin.pages.earning-commission.edit', compact('commission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commission = EarningCommissionStructure::findOrFail($id);

        $request->validate([
            'level' => 'required|integer|unique:earning_commission_structures,level,' . $id,
            'level_name' => 'required|string|max:255',
            'commission_rate' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        $commission->update([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'commission_rate' => $request->commission_rate,
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

