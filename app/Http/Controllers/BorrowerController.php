<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowers = Borrower::with('division')->get();
        
        return Inertia::render('borrowers/Index', [
            'borrowers' => $borrowers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();
        
        return Inertia::render('borrowers/Create', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        Borrower::create([
            'name' => $request->name,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('borrowers.index')->with('success', 'Borrower created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrower $borrower)
    {
        return Inertia::render('borrowers/Show', [
            'borrower' => $borrower->load('division')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrower $borrower)
    {
        $divisions = Division::all();
        
        return Inertia::render('borrowers/Edit', [
            'borrower' => $borrower,
            'divisions' => $divisions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrower $borrower)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $borrower->update([
            'name' => $request->name,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('borrowers.index')->with('success', 'Borrower updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        
        return redirect()->route('borrowers.index')->with('success', 'Borrower deleted successfully');
    }
}
