<?php

namespace App\Http\Controllers;

use App\Models\BorrowerFacility;
use Illuminate\Http\Request;

class BorrowerFacilityController extends Controller
{
    public function store(Request $request)
    {
        // Ambil borrower_id dari session
        $borrowerId = $request->session()->get('borrower_id');

        // Jika borrower_id ada, tambahkan ke data yang akan disimpan
        if ($borrowerId) {
            $request->merge(['borrower_id' => $borrowerId]);
        }

        // Validasi data
        $validated = $request->validate([
            'borrower_id' => 'required|integer',
            'facility_name' => 'required|string',
            'limit' => 'required|decimal:2',
            'outstanding' => 'required|decimal:2',
            'interest_rate' =>'required|decimal:2',
            'principal_arrears' =>'required|decimal:2',
            'interest_arrears' =>'required|decimal:2',
            'pdo_days' => 'required|integer',
            'maturity_date' =>'required|date'
        ]);

        // Simpan data ke dalam database
        BorrowerFacility::create($validated);

        return redirect()->route('dashboard')->with('success', 'Fasilitas debitur berhasil ditambahkan.');
    }
}
