<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisibilityRule extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id', 
        'description',
        'source_type',
        'source_field',
        'operator',
        'value'
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
