<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspect extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_aspects');
    }
    
    // Menghitung total bobot dari pertanyaan di dalam aspek
    public function calculateQuestionWeights()
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions > 0) {
            // Menghitung bobot per pertanyaan agar totalnya 1
            $weightPerQuestion = 1 / $totalQuestions;
            $this->questions->each(function ($question) use ($weightPerQuestion) {
                $question->update(['weight' => $weightPerQuestion]);
            });
        }
    }
}
