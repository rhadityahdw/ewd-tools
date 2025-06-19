<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'aspect_version_id',
        'question_text',
        'weight',
        'max_score',
        'min_score',
        'is_mandatory',
    ];

    protected function casts()
    {
        return [
            'weight' => 'decimal:2',
            'max_score' => 'decimal:2',
            'min_score' => 'decimal:2',
            'is_mandatory' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function aspectVersion()
    {
        return $this->belongsTo(AspectVersion::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
