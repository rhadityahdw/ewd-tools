<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspect extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'code', 
        'description'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Fix the relationship to use the correct table
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'aspect_template_versions', 'aspect_version_id', 'template_id')
                    ->join('aspect_versions', 'aspects.id', '=', 'aspect_versions.aspect_id')
                    ->withPivot('weight')
                    ->withTimestamps();
    }
    
    public function calculateQuestionWeights()
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions > 0) {
            $weightPerQuestion = 1 / $totalQuestions;
            $this->questions->each(function ($question) use ($weightPerQuestion) {
                $question->update(['weight' => $weightPerQuestion]);
            });
        }
    }

    public function aspectVersions()
    {
        return $this->hasMany(AspectVersion::class);
    }

    // Helper method to get the latest version
    public function latestVersion()
    {
        return $this->aspectVersions()
                    ->where('effective_from', '<=', now())
                    ->orderBy('version', 'desc')
                    ->first();
    }
}
