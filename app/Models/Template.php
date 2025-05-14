<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The aspects that belong to the template.
     */
    public function aspects()
    {
        return $this->belongsToMany(Aspect::class, 'template_aspects')
            ->withPivot('weight')
            ->withTimestamps();
    }
}
