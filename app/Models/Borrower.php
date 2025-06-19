<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{   
    use HasFactory;

    protected $fillable = [
        'name',
        'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function detail()
    {
        return $this->hasOne(BorrowerDetail::class);
    }

    public function facilities()
    {
        return $this->hasMany(BorrowerFacility::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

}
