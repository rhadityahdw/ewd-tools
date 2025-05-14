<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowerDetail extends Model
{
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
        'collectibility' => 'boolean',
        'restructuring' => 'integer',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }
}
