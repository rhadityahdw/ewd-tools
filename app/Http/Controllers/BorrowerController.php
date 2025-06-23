<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowerRequest;
use App\Http\Resources\BorrowerResource;
use App\Models\Borrower;
use App\Models\Division;
use App\Services\BorrowerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BorrowerController extends Controller
{
    public function __construct(
        protected BorrowerService $borrowerService
    ) {}
    
    public function index()
    {
        $borrower = Borrower::with('division')->get();

        $borrowers = BorrowerResource::collection($borrower);
        
        return Inertia::render('borrowers/Index', [
            'borrowers' => $borrowers
        ]);
    }

    public function create()
    {
        return Inertia::render('borrowers/Create', [
            'divisions' => Division::all()
        ]);
    }

    public function store(BorrowerRequest $request)
    {
        $this->borrowerService->createBorrower($request->validated());

        return redirect()->route('borrowers.index')->with('success', 'Borrower created successfully');
    }

    public function show(Borrower $borrower)
    {
        return Inertia::render('borrowers/Show', [
            'borrower' => new BorrowerResource($borrower->load('division'))
        ]);
    }

    public function edit(Borrower $borrower)
    {
        
        return Inertia::render('borrowers/Edit', [
            'borrower' => new BorrowerResource($borrower),
            'divisions' => Division::all()
        ]);
    }

    public function update(BorrowerRequest $request, Borrower $borrower)
    {
        $this->borrowerService->updateBorrower($borrower, $request->validated());

        return redirect()->route('borrowers.index')->with('success', 'Borrower updated successfully');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        
        return redirect()->route('borrowers.index')->with('success', 'Borrower deleted successfully');
    }
}
