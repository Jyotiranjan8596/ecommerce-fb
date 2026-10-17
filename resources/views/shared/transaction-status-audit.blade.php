@php
    $auditEntries = $statusChanges ?? collect();
@endphp

<div class="small text-muted mt-1 transaction-status-audit">
    @if ($auditEntries->isEmpty())
        <span>Actor history unavailable for this older record.</span>
    @else
        @php($latestStatusEvent = $auditEntries->first(fn ($entry) => in_array($entry->event_type, ['created', 'status_changed'], true)))
        @if ($latestStatusEvent)
            <span>
                {{ $latestStatusEvent->event_type === 'created' ? 'Recorded' : 'Status changed' }}
                by {{ $latestStatusEvent->actor_label }}
                · {{ $latestStatusEvent->created_at?->format('d-m-Y h:i A') }}
            </span>
        @endif

        @if ($auditEntries->count() > 1 || $auditEntries->first()?->event_type === 'edited')
            <details class="mt-1">
                <summary>Change history ({{ $auditEntries->count() }})</summary>
                <ul class="mb-0 ps-3">
                    @foreach ($auditEntries as $entry)
                        <li>
                            {{ $entry->description }} by {{ $entry->actor_label }}
                            · {{ $entry->created_at?->format('d-m-Y h:i A') }}
                        </li>
                    @endforeach
                </ul>
            </details>
        @endif
    @endif
</div>
