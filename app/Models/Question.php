<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question_text', 'weight', 'is_mandatory', 'true_score', 'false_score'];

    public function aspect()
    {
        return $this->belongsTo(Aspect::class);
    }

     // Mengatur bobot menjadi desimal (simpan sebagai 1 = 100%)
     public function setWeightAttribute($value)
     {
         $this->attributes['weight'] = $value / 100;  // Simpan dalam format desimal
     }
}
