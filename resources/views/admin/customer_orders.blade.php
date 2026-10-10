@extends('layouts.master')

@section('content')
    <div class="container-fluid px-2 px-md-3 py-3">
        <section class="dashboard-hero mb-4">
            <span class="dashboard-hero__label"><i class="fa fa-box me-1" aria-hidden="true"></i> Order activity</span>
            <h1 class="dashboard-hero__title">Customer Orders</h1>
            <p class="dashboard-hero__text">Review order details and status updates across POS locations.</p>
            <div class="dashboard-hero__stats">
                <div class="dashboard-hero__stat"><small>Total orders</small><strong>{{ $totalOrders }}</strong></div>
                <div class="dashboard-hero__stat"><small>Awaiting confirmation</small><strong>{{ $pendingOrders }}</strong></div>
                <div class="dashboard-hero__stat"><small>Delivered</small><strong>{{ $deliveredOrders }}</strong></div>
            </div>
        </section>

        <div class="row g-3">
            @forelse ($orders as $order)
                <div class="col-12">
                    <article class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <h2 class="h5 mb-1">Order #{{ $order->id }}</h2>
                                    <div class="text-muted small">
                                        {{ $order->customer?->name ?? 'Customer' }}
                                        <span class="mx-1">&middot;</span>
                                        {{ $order->pos?->name ?? 'POS unavailable' }}
                                        <span class="mx-1">&middot;</span>
                                        {{ $order->created_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                                <span class="badge text-bg-{{ $order->status === \App\Models\CustomerOrder::STATUS_DELIVERED ? 'success' : ($order->status === \App\Models\CustomerOrder::STATUS_PENDING ? 'warning' : 'primary') }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>

                            @if ($order->items)
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead><tr><th>Item</th><th>Brand</th><th>Quantity</th></tr></thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>{{ $item['item'] ?? '' }}</td>
                                                    <td>{{ $item['brand'] ?? '—' }}</td>
                                                    <td>{{ $item['quantity'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @if ($order->handwritten_list_path)
                                <p class="small text-muted mt-2 mb-0">A handwritten list is attached to this order.</p>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border">No customer orders yet.</div></div>
            @endforelse
        </div>
        {{ $orders->links() }}
    </div>
@endsection
