<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'created_by',
        'status',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
