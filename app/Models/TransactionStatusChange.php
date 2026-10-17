<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransactionStatusChange extends Model
{
    protected $fillable = [
        'event_type',
        'old_status',
        'new_status',
        'changed_fields',
        'actor_type',
        'actor_id',
        'actor_name',
    ];

    protected $casts = [
        'changed_fields' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getActorLabelAttribute(): string
    {
        $actor = match ($this->actor_type) {
            'admin' => 'Admin',
            'pos' => 'POS',
            'customer' => 'Customer',
            'system' => 'Automatic system',
            default => 'User',
        };

        return $this->actor_name
            ? $actor . ' (' . $this->actor_name . ')'
            : $actor;
    }

    public function getDescriptionAttribute(): string
    {
        return match ($this->event_type) {
            'created' => 'Record created with status ' . $this->formatStatus($this->new_status),
            'status_changed' => 'Status changed from '
                . $this->formatStatus($this->old_status)
                . ' to '
                . $this->formatStatus($this->new_status),
            'edited' => 'Record edited'
                . ($this->changed_fields ? ': ' . implode(', ', $this->changed_fields) : ''),
            'deleted' => 'Record deleted',
            default => 'Record updated',
        };
    }

    private function formatStatus(?string $status): string
    {
        if ($this->subject_type === Wallet::class) {
            return $status === '1' ? 'Verified' : 'Unverified';
        }

        if ($this->subject_type === CustomerOrder::class) {
            return $status
                ? ucwords(str_replace('_', ' ', $status))
                : 'Not set';
        }

        if ($this->subject_type === PaymentSummary::class) {
            return $status ? ucfirst($status) : 'Not set';
        }

        return $status ?? 'Not set';
    }
}
