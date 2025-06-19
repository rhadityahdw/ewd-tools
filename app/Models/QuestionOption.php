<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_version_id',
        'option_text',
        'score'
    ];

    protected function casts()
    {
        return [
            'score' => 'decimal:2'
        ];
    }

    public function questionVersion()
    {
        return $this->belongsTo(QuestionVersion::class);
    }
}
