<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id',
        'facility_name',
        'limit',
        'outstanding',
        'interest_rate',
        'principal_arrears',
        'interest_arrears',
        'pdo_days',
        'maturity_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'borrower_id' => 'integer',
        'limit' => 'decimal:2',
        'outstanding' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'principal_arrears' => 'decimal:2',
        'interest_arrears' => 'decimal:2',
        'pdo_days' => 'integer',
        'maturity_date' => 'date',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }
}
