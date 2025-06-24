<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspect extends Model
{
    use HasFactory;

    protected $fillable = [
        'code'
    ];

    public function versions()
    {
        return $this->hasMany(AspectVersion::class);
    }
    
    public function latestVersion()
    {
        return $this->hasOne(AspectVersion::class)->latestOfMany();
    }

    public function visibilityRules()
    {
        return $this->morphMany(VisibilityRule::class, 'entity');
    }
}
