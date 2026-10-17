<?php

namespace App\Observers;

use App\Models\TransactionStatusChange;
use App\Support\Notify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionStatusObserver
{
    public function created(Model $model): void
    {
        $status = $model->getAttribute('status');
        if (! array_key_exists('status', $model->getAttributes())) {
            $status = $model->newQuery()->whereKey($model->getKey())->value('status');
        }

        $this->record($model, 'created', null, $status, ['status']);
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        $changedFields = array_values(array_diff(
            array_keys($changes),
            [$model->getUpdatedAtColumn()]
        ));

        if ($changedFields === []) {
            return;
        }

        $statusChanged = in_array('status', $changedFields, true);
        $this->record(
            $model,
            $statusChanged ? 'status_changed' : 'edited',
            $statusChanged ? $model->getRawOriginal('status') : null,
            $statusChanged ? $model->getAttribute('status') : null,
            $changedFields
        );
    }

    public function deleting(Model $model): void
    {
        $this->record(
            $model,
            'deleted',
            $model->getAttribute('status'),
            null,
            ['deleted']
        );
    }

    private function record(
        Model $model,
        string $eventType,
        mixed $oldStatus,
        mixed $newStatus,
        array $changedFields
    ): void {
        $actor = Auth::user();
        $actorType = match ((int) ($actor?->role ?? 0)) {
            Notify::ROLE_ADMIN => 'admin',
            Notify::ROLE_POS => 'pos',
            Notify::ROLE_CUSTOMER => 'customer',
            default => $actor ? 'user' : 'system',
        };

        $model->statusChanges()->create([
            'event_type' => $eventType,
            'old_status' => $this->normalizeStatus($oldStatus),
            'new_status' => $this->normalizeStatus($newStatus),
            'changed_fields' => $changedFields,
            'actor_type' => $actorType,
            'actor_id' => $actor?->getKey(),
            'actor_name' => $actor?->name,
        ]);
    }

    private function normalizeStatus(mixed $status): ?string
    {
        return $status === null ? null : (string) $status;
    }
}
