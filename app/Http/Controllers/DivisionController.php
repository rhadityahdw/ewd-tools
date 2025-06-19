<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::all();

        return Inertia::render('divisions/Index', [
            'divisions' => $divisions
        ]);
    }

    public function create()
    {
        return Inertia::render('divisions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        Division::create([
            'code' => $request->code,
            'name' => $request->name
        ]);

        return redirect()->route('divisions.index')->with('success', 'Division created successfully');
    }

    public function show(Division $division)
    {
        return Inertia::render('divisions/Show', [
            'division' => $division
        ]);
    }

    public function edit(Division $division)
    {
        return Inertia::render('divisions/Edit', [
            'division' => $division
        ]);
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255'
        ]);

        $division->update([
            'code' => $request->code,
            'name' => $request->name
        ]);

        return redirect()->route('divisions.index')->with('success', 'Division updated successfully');
    }

    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('divisions.index')->with('success', 'Division deleted successfully');
    }

}
