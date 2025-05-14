<?php

namespace App\Http\Controllers;

use App\Models\BorrowerDetail;
use Illuminate\Http\Request;

class BorrowerDetailController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'borrower_group' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'economic_sector' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'borrower_business' => 'required|string|max:255',
            'collectibility' => 'required|integer|min:1|max:5',
            'restructuring' => 'required|boolean',
        ]);

        BorrowerDetail::create($validated);

        // Simpan borrower_id ke session
        session(['borrower_id' => $validated['borrower_id']]);
        
        return redirect()->route('forms.facility')->with('success', 'Detail debitur berhasil ditambahkan.');
    }
}
