<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAspectRequest;
use App\Http\Resources\AspectResource;
use App\Models\Aspect;
use App\Models\AspectVersion;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Services\AspectBuilderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Exception;

class AspectController extends Controller
{
    public function __construct(
        protected AspectBuilderService $aspectBuilderService
    ) {}

    public function index()
    {
        $aspects = Aspect::with('latestVersion')->latest()->get();

        return Inertia::render('aspects/Index', [
            'aspects' => AspectResource::collection($aspects),
        ]);
    }

    public function create()
    {
        return Inertia::render('aspects/Create');
    }

    public function store(StoreAspectRequest $request)
    {
        try {
            $validated = $request->validated();

            $this->aspectBuilderService->createAspectWithQuestions($validated);

            return redirect()->route('aspects.index')->with('success', 'Aspect created successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to create aspect: ' . $e->getMessage()]);
        }
    }

    public function show(Aspect $aspect)
    {
        $aspect->load(['versions.questionsVersions.options', 'versions.questionsVersions.question.visibilityRules']);

        return Inertia::render('aspects/Show', [
            'aspect' => new AspectResource($aspect),
        ]);
    }

    public function edit(Aspect $aspect)
    {
        $aspect->load(['versions.questionsVersions.options', 'versions.questionsVersions.question.visibilityRules']);

        return Inertia::render('aspects/Edit', [
            'aspect' => new AspectResource($aspect),
        ]);
    }

    public function update(StoreAspectRequest $request, Aspect $aspect)
    {
        try {
            $validated = $request->validated();

            $this->aspectBuilderService->updateAspectWithQuestions($aspect, $validated);

            return redirect()->route('aspects.index')->with('success', 'Aspect updated successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to update aspect: ' . $e->getMessage()]);
        }
    }

    public function destroy(Aspect $aspect)
    {
        try {
            $aspect->delete();

            return redirect()->route('aspects.index')->with('success', 'Aspect deleted successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete aspect: ' . $e->getMessage()]);
        }
    }
}
