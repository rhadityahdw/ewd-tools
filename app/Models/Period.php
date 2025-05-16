<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'created_by',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_ENDED = 'ended';
    const STATUS_EXPIRED = 'expired';

    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE,
            self::STATUS_ENDED,
            self::STATUS_EXPIRED,
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get is expired attribute.
     *
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date && now()->gt($this->end_date) && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get is active attribute.
     * 
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get remaining time attribute.
     *
     * @return \DateInterval
     */
    public function getRemainingTimeAttribute(): ?string
    {
        if (!$this->end_date) {
            return null;
        }

        $diff = now()->diff($this->end_date);
        return $diff->format('%a hari, %h jam, %i menit');
    }

    public function markAsEnded(): void
    {
        $this->status = self::STATUS_ENDED;
        $this->start_date = now();
        $this->save();
    }

    public function markAsExpired(): void
    {
        $this->status = self::STATUS_EXPIRED;
        $this->save();
    }

    public function markAsActive(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->start_date = now();
        $this->save();
    }

    public function markAsDraft(): void
    {
        $this->status = self::STATUS_DRAFT;
        $this->save();
    }
}
