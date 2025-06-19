<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_text', 
        'weight', 
        'max_score', 
        'min_score',
        'is_mandatory', 
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'max_score' => 'decimal:2',
        'min_score' => 'decimal:2',
        'is_mandatory' => 'boolean',
    ];

    public function versions()
    {
        return $this->hasMany(QuestionVersion::class);
    }

    public function aspect()
    {
        return $this->belongsTo(Aspect::class);
    }
}
