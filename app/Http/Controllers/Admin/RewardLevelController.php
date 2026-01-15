<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardLevel;
use Illuminate\Http\Request;

class RewardLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewardLevels = RewardLevel::orderBy('sort_order')->orderBy('level')->get();
        return view('admin.pages.reward-level.index', compact('rewardLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.reward-level.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|integer|unique:reward_levels,level',
            'level_name' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:50',
            'investment_required' => 'required|numeric|min:0',
            'reward_amount' => 'required|numeric|min:0',
            'is_premium' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        RewardLevel::create([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'icon_class' => $request->icon_class,
            'icon_color' => $request->icon_color,
            'investment_required' => $request->investment_required,
            'reward_amount' => $request->reward_amount,
            'is_premium' => $request->has('is_premium') ? true : false,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.reward-level.index')
            ->with('success', 'Reward level created successfully.');
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
        $rewardLevel = RewardLevel::findOrFail($id);
        return view('admin.pages.reward-level.edit', compact('rewardLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rewardLevel = RewardLevel::findOrFail($id);

        $request->validate([
            'level' => 'required|integer|unique:reward_levels,level,' . $id,
            'level_name' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
            'icon_color' => 'nullable|string|max:50',
            'investment_required' => 'required|numeric|min:0',
            'reward_amount' => 'required|numeric|min:0',
            'is_premium' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $rewardLevel->update([
            'level' => $request->level,
            'level_name' => $request->level_name,
            'icon_class' => $request->icon_class,
            'icon_color' => $request->icon_color,
            'investment_required' => $request->investment_required,
            'reward_amount' => $request->reward_amount,
            'is_premium' => $request->has('is_premium') ? true : false,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.reward-level.index')
            ->with('warning', 'Reward level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rewardLevel = RewardLevel::findOrFail($id);
        $rewardLevel->delete();

        return redirect()->route('admin.reward-level.index')
            ->with('danger', 'Reward level deleted successfully.');
    }
}

