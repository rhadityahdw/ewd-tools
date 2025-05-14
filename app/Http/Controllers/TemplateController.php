<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Aspect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller

{
    /**
     * Menampilkan semua daftar template beserta aspek dan bobotnya.
     */

    public function index()
    {
        // Ambil semua template beserta relasi aspek dan bobot (pivot 'weight')
        $templates = Template::with(['aspects' => fn ($q) => $q->withPivot('weight')])->get();

        return Inertia::render('templates/Index', [
            'templates' => $templates,
        ]);
    }

     /**
     * Menampilkan form untuk membuat template baru.
     */
    public function create()
    {
        // Ambil semua aspek beserta pertanyaannya untuk ditampilkan di form
        $aspects = Aspect::with('questions')->get();

        return Inertia::render('templates/Create', [
            'aspects' => $aspects,
        ]);
    }

    /**
     * Menyimpan template baru beserta aspek yang dipilih dan bobotnya.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'aspect_weights' => ['required', 'array'],
            'aspect_weights.*.id' => ['required', 'exists:aspects,id'],
            'aspect_weights.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Validasi total bobot aspek (harus 100%)
        $totalWeight = collect($validatedData['aspect_weights'])->sum('weight');
        if ($totalWeight !== 100) {
            return back()->withErrors(['aspect_weights' => 'Total bobot aspek harus 100%']);
        }

        // Simpan template
        $template = Template::create([
            'name' => $validatedData['name'],
        ]);

        // Siapkan data pivot: [aspect_id => ['weight' => value]]
        $pivotData = collect($validatedData['aspect_weights'])->mapWithKeys(function ($item) {
            return [$item['id'] => ['weight' => $item['weight']]];
        })->toArray();

        // Hubungkan aspek ke template dengan bobotnya
        $template->aspects()->attach($pivotData);

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Menampilkan detail template dan seluruh aspek serta pertanyaannya.
     */
    public function show(Template $template)
    {
        // Load aspek dan pertanyaan yang terkait dengan template
        $template->load(['aspects.questions' => fn ($q) => $q->orderBy('id')]);

        return Inertia::render('templates/Show', [
            'template' => $template,
        ]);
    }

    /**
     * Menampilkan form edit untuk template yang dipilih.
     */
    public function edit(Template $template)
    {
        // Ambil aspek yang terkait dengan template (beserta bobot dari pivot)
        $template->load(['aspects' => fn ($q) => $q->withPivot('weight')]);

        // Ambil semua aspek (untuk memungkinkan pengeditan aspek)
        $aspects = Aspect::with('questions')->get();

        return Inertia::render('templates/Edit', [
            'template' => $template,
            'aspects' => $aspects,
        ]);
    }

    /**
     * Memperbarui data template dan sinkronisasi aspek beserta bobot.
     */
    public function update(Request $request, Template $template)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'aspect_weights' => ['required', 'array'],
            'aspect_weights.*.id' => ['required', 'exists:aspects,id'],
            'aspect_weights.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Perbarui nama template
        $template->update([
            'name' => $validated['name'],
        ]);

        // Siapkan data sinkronisasi pivot
        $pivotData = collect($validated['aspect_weights'])->mapWithKeys(function ($item) {
            return [$item['id'] => ['weight' => $item['weight']]];
        })->toArray();

        // Sinkronisasi aspek dan bobot ke template
        $template->aspects()->sync($pivotData);

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Menghapus template beserta relasinya ke aspek.
     */
    public function destroy(Template $template)
    {
        // Putuskan hubungan dengan semua aspek
        $template->aspects()->detach();

        // Hapus template
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }
}
