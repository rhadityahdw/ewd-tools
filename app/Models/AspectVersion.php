<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AspectVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'aspect_id',
        'version',
        'name',
        'code',
        'description',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function aspect()
    {
        return $this->belongsTo(Aspect::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'aspect_template_versions')
                    ->withPivot('weight')
                    ->withTimestamps();
    }

    public function questionVersions()
    {
        return $this->hasMany(QuestionVersion::class);
    }
}
