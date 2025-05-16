<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class PeriodController extends Controller
{
    /**
     * GET /periods
     * 
     * Display all periods.
     */
    public function index()
    {
        $periods = Period::orderBy('start_date', 'desc')->get()->filter();

        return Inertia::render('periods/Index', [
            'periods' => $periods,
        ]);
    }

    /**
     * GET /periods/create
     * 
     * Show the create period form.
     */
    public function create()
    {
        return Inertia::render('periods/Create');
    }

    /**
     * POST /periods
     *
     * Store a new period.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' =>'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:draft,active,ended,expired',
        ]);

        $validated['created_by'] = Auth::id();

        Period::create($validated);

        return redirect()->route('periods.index')->with('success', 'Period created successfully.');
    }

    /**
     * GET /periods/{period}
     *
     * Show the period.
     */
    public function show(Period $period)
    {
        return Inertia::render('periods/Show', [
            'period' => $period,
        ]);
    }

    /**
     * GET /periods/{period}/edit
     *
     * Show the edit period form.
     */
    public function edit(Period $period)
    {
        return Inertia::render('periods/Edit', [
            'period' => $period,
        ]);
    }

    /**
     * PUT /periods/{period}
     *
     * Update the period.
     */
    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' =>'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' =>'required|in:draft,active,ended,expired',
        ]);

        $period->update($validated);

        return redirect()->route('periods.index')->with('success', 'Period updated successfully.');
    }

    /**
     * DELETE /periods/{period}
     *
     * Delete the period.
     */
    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Period deleted successfully.');
    }

    /**
     * POST /periods/{period}/start
     * 
     * Start the period.
     */
    public function start(Period $period)
    {
        $period->markAsActive();

        return redirect()->route('periods.index')->with('success', 'Period started successfully.');
    }

    /**
     * POST /periods/{period}/end
     * 
     * End the period.
     */
    public function stop(Period $period)
    {
        $period->markAsEnded();

        return redirect()->route('periods.index')->with('success', 'Period ended successfully.');
    }

    /**
     * POST /periods/{period}/extend
     * 
     * Extend the period.
     */
    public function extend(Request $request, Period $period)
    {
        $validated = $request->validate([
            'end_date' => 'required|date|after:start_date',
        ]);

        $period->update([
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('periods.index')->with('success', 'Period extended successfully.');
    }
}
