<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    public function versions()
    {
        return $this->hasMany(QuestionVersion::class);
    }

    public function visibilityRules()
    {
        return $this->morphMany(VisibilityRule::class, 'entity');
    }
}
