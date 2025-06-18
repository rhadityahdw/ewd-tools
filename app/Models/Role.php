<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
