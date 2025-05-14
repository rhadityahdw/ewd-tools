<?php

namespace App\Http\Controllers;

use App\Models\Aspect;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AspectController extends Controller
{
    public function index()
    {
        $aspects = Aspect::with('questions')->get();

        return Inertia::render('aspects/Index', [
            'aspects' => $aspects,
        ]);
    }

    public function create()
    {
        return Inertia::render('aspects/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', 'unique:aspects,code'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.weight' => ['required', 'numeric'],
            'questions.*.true_score' => ['required', 'numeric'],
            'questions.*.false_score' => ['required', 'numeric'],
            'questions.*.is_mandatory' => ['required', 'boolean'],
        ]);

        $aspect = Aspect::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'],
        ]);

        foreach ($validated['questions'] as $question) {
            $aspect->questions()->create($question);
        }

        $aspect->calculateQuestionWeights();

        return redirect()->route('aspects.index')->with('success', 'Aspek dan pertanyaan berhasil dibuat.');
    }

    public function show(Aspect $aspect)
    {
        $aspect->load('questions');
        return Inertia::render('aspects/Edit', [
            'aspect' => $aspect,
        ]);
    }

    public function edit(Aspect $aspect)
    {
        $aspect->load('questions');

        return Inertia::render('aspects/Edit', [
            'aspect' => $aspect,
        ]);
    }

    public function update(Request $request, Aspect $aspect)
    {
        // Validasi data aspect
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', 'unique:aspects,code,' . $aspect->id],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.weight' => ['required', 'numeric'],
            'questions.*.true_score' => ['required', 'numeric'],
            'questions.*.false_score' => ['required', 'numeric'],
            'questions.*.is_mandatory' => ['required', 'boolean'],
        ]);

        // Update data aspect
        $aspect->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'],
        ]);

       // Proses update pertanyaan
        $existingQuestionIds = [];
        foreach ($validated['questions'] as $questionData) {
            if (isset($questionData['id'])) {
                // Update pertanyaan yang ada
                $question = $aspect->questions()->find($questionData['id']);
                if ($question) {
                    $question->update([
                        'question_text' => $questionData['question_text'],
                        'weight' => $questionData['weight'],
                    ]);
                    $existingQuestionIds[] = $question->id;
                }
            } else {
                // Tambah pertanyaan baru
                $newQuestion = $aspect->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'weight' => $questionData['weight'],
                ]);
                $existingQuestionIds[] = $newQuestion->id;
            }
        }

        // Hapus pertanyaan yang tidak ada
        $aspect->questions()->whereNotIn('id', $existingQuestionIds)->delete();

        // Menghitung ulang bobot untuk pertanyaan setelah update
        $aspect->calculateQuestionWeights();

        return redirect()->route('aspects.index')
            ->with('success', 'Aspek dan pertanyaan berhasil diperbarui.');
    }

    public function destroy(Aspect $aspect)
    {
        $aspect->delete();
        return redirect()->route('aspects.index')
            ->with('success', 'Aspek berhasil dihapus.');
    }
}
