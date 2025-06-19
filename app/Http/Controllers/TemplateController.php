<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Aspect;
use App\Models\AspectVersion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function index()
    {
        // Load templates with aspect versions and their weights
        $templates = Template::with(['aspectVersions' => function($query) {
            $query->with('aspect');
        }])->get();

        return Inertia::render('templates/Index', [
            'templates' => $templates,
        ]);
    }

    public function create()
    {
        // Get all aspects with their latest versions
        $aspects = Aspect::with(['aspectVersions' => function($query) {
            $query->where('effective_from', '<=', now())
                  ->orderBy('version', 'desc')
                  ->limit(1);
        }])->get();

        return Inertia::render('templates/Create', [
            'aspects' => $aspects,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'aspect_weights' => ['required', 'array', 'min:1'],
            'aspect_weights.*.aspect_version_id' => ['required', 'exists:aspect_versions,id'],
            'aspect_weights.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
    
        // Validate total weight
        $totalWeight = collect($validatedData['aspect_weights'])->sum('weight');
        if (abs($totalWeight - 100) > 0.01) {
            return back()->withErrors(['aspect_weights' => 'Total bobot aspek harus 100%']);
        }
    
        $template = Template::create([
            'name' => $validatedData['name'],
        ]);
    
        // Attach aspect versions with weights
        $pivotData = collect($validatedData['aspect_weights'])->mapWithKeys(function ($item) {
            return [$item['aspect_version_id'] => ['weight' => $item['weight']]];
        })->toArray();
    
        $template->aspectVersions()->attach($pivotData);
    
        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dibuat.');
    }

    public function show(Template $template)
    {
        $template->load(['aspectVersions.aspect', 'aspectVersions.questionVersions.options']);

        return Inertia::render('templates/Show', [
            'template' => $template,
        ]);
    }

    public function edit(Template $template)
    {
        $template->load(['aspectVersions']);
        
        $aspects = Aspect::with(['aspectVersions' => function($query) {
            $query->where('effective_from', '<=', now())
                  ->orderBy('version', 'desc')
                  ->limit(1);
        }])->get();

        return Inertia::render('templates/Edit', [
            'template' => $template,
            'aspects' => $aspects,
        ]);
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'aspect_weights' => ['required', 'array'],
            'aspect_weights.*.aspect_version_id' => ['required', 'exists:aspect_versions,id'],
            'aspect_weights.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Validate total weight
        $totalWeight = collect($validated['aspect_weights'])->sum('weight');
        if (abs($totalWeight - 100) > 0.01) {
            return back()->withErrors(['aspect_weights' => 'Total bobot aspek harus 100%']);
        }

        $template->update([
            'name' => $validated['name'],
        ]);

        $pivotData = collect($validated['aspect_weights'])->mapWithKeys(function ($item) {
            return [$item['aspect_version_id'] => ['weight' => $item['weight']]];
        })->toArray();

        $template->aspectVersions()->sync($pivotData);

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(Template $template)
    {
        $template->aspectVersions()->detach();
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }
}
