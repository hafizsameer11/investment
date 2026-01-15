<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CurrencyConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencyConversion = CurrencyConversion::first();
        return view('admin.pages.currency-conversion.index', compact('currencyConversion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If a record already exists, redirect to edit
        $currencyConversion = CurrencyConversion::first();
        if ($currencyConversion) {
            return redirect()->route('admin.currency-conversion.edit', $currencyConversion->id);
        }
        
        return view('admin.pages.currency-conversion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ensure only one record exists
        if (CurrencyConversion::count() > 0) {
            return redirect()->route('admin.currency-conversion.index')
                ->withErrors(['error' => 'Currency conversion already exists. Please edit the existing record.']);
        }

        $request->validate([
            'rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            CurrencyConversion::create([
                'rate' => $request->rate,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('admin.currency-conversion.index')
                ->with('success', 'USD to PKR conversion rate created successfully.');
        } catch (\Exception $e) {
            Log::error('Currency Conversion Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create currency conversion. Please check the logs for details.']);
        }
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
        $currencyConversion = CurrencyConversion::findOrFail($id);
        return view('admin.pages.currency-conversion.edit', compact('currencyConversion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $currencyConversion = CurrencyConversion::findOrFail($id);

        $request->validate([
            'rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $currencyConversion->update([
                'rate' => $request->rate,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('admin.currency-conversion.index')
                ->with('warning', 'USD to PKR conversion rate updated successfully.');
        } catch (\Exception $e) {
            Log::error('Currency Conversion Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update currency conversion. Please check the logs for details.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currencyConversion = CurrencyConversion::findOrFail($id);
        $currencyConversion->delete();

        return redirect()->route('admin.currency-conversion.index')
            ->with('danger', 'Currency conversion deleted successfully.');
    }
}
