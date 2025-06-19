<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id',
        'borrower_group',
        'purpose',
        'economic_sector',
        'business_field',
        'borrower_business',
        'collectibility',
        'restructuring',
    ];

    protected $casts = [
        'collectibility' => 'integer',
        'restructuring' => 'boolean',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }
}
