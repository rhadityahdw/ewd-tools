<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowerFacility extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'borrower_facility';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
