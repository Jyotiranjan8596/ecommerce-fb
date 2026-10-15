<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_READY_TO_DISPATCH = 'ready_to_dispatch';

    public const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'user_id',
        'pos_id',
        'items',
        'handwritten_list_path',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function pos()
    {
        return $this->belongsTo(PosModel::class, 'pos_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isEditable(): bool
    {
        return strtolower(trim($this->status)) === self::STATUS_PENDING;
    }

    public function getStatusLabelAttribute(): string
    {
        return match (strtolower(trim($this->status))) {
            self::STATUS_PENDING => 'Order Placed',
            self::STATUS_CONFIRMED => 'Order Confirmed',
            self::STATUS_READY_TO_DISPATCH => 'Ready to Despatch',
            self::STATUS_DELIVERED => 'Delivered',
            default => ucwords(str_replace('_', ' ', $this->status)),
        };
    }
}
