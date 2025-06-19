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
    
    // Remove the incorrect relationship
    // public function aspects()
    // {
    //     return $this->belongsToMany(Aspect::class, 'template_aspects');
    // }

    public function aspectVersions()
    {
        return $this->belongsToMany(AspectVersion::class, 'aspect_template_versions')
                    ->withPivot('weight')
                    ->withTimestamps();
    }

    // Add a helper method to get aspects through aspect versions
    public function aspects()
    {
        return $this->belongsToMany(Aspect::class, 'aspect_template_versions', 'template_id', 'aspect_version_id')
                    ->join('aspect_versions', 'aspects.id', '=', 'aspect_versions.aspect_id')
                    ->withPivot('weight')
                    ->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
