<?php

namespace App\Models\Concerns;

use App\Models\TransactionStatusChange;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTransactionStatusChanges
{
    public function statusChanges(): MorphMany
    {
        return $this->morphMany(TransactionStatusChange::class, 'subject')->latest('id');
    }
}
