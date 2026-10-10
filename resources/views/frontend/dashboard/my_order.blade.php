@extends('frontend.dashboard.layouts.master')

@section('content')
    @php
        $formItems = old('items', $editingOrder?->items ?: [['item' => '', 'brand' => '', 'quantity' => '']]);
    @endphp

    <div class="customer-order-page">
    <div class="card mb-4 order-compose-card">
        <div class="card-header order-page-header">
            <div class="order-page-header__copy">
                <h4 class="mb-1 order-page-title"><strong>{{ $editingOrder ? 'Edit Order' : 'My Order' }}</strong></h4>
                <p class="mb-0">Add the products you need, or upload a photo or PDF of your handwritten list.</p>
            </div>
            <div class="order-page-header__tools">
                <span class="badge rounded-pill px-3 py-2 order-page-header__badge">
                    <i class="fas fa-clipboard-list me-1" aria-hidden="true"></i> Customer orders
                </span>
                <span class="order-page-header__icon" aria-hidden="true"><i class="fas fa-shopping-bag"></i></span>
            </div>
        </div>
        <div class="card-body order-compose-body">
            @if (session('message'))
                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            <form
                action="{{ $editingOrder ? route('user.order.update', $editingOrder) : route('user.order.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($editingOrder)
                    @method('PUT')
                @endif

                @error('order')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="mb-4">
                    <label class="form-label" for="pos-id"><strong>Choose POS</strong></label>
                    <select class="form-select" id="pos-id" name="pos_id" required>
                        <option value="">Select a POS location</option>
                        @foreach ($posLocations as $posLocation)
                            <option value="{{ $posLocation->id }}"
                                @selected((string) old('pos_id', $editingOrder?->pos_id ?: $defaultPosId) === (string) $posLocation->id)>
                                {{ $posLocation->name }}{{ $posLocation->city ? ' - ' . $posLocation->city : '' }}{{ $posLocation->delivery_orders_enabled ? '' : ' (not accepting new delivery orders)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('pos_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @if (! $defaultPosId && $posLocations->where('delivery_orders_enabled', true)->isEmpty())
                        <small class="form-text text-muted">
                            No POS locations are currently accepting delivery orders.
                            @if ($editingOrder?->pos_id)
                                You can still edit this order or choose another available POS.
                            @endif
                        </small>
                    @endif
                </div>

                <div class="table-responsive order-items-wrap">
                    <table class="table table-bordered align-middle order-items-table">
                        <thead>
                            <tr>
                                <th scope="col">Items</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Quantity</th>
                                <th scope="col" aria-label="Actions"></th>
                            </tr>
                        </thead>
                        <tbody id="order-items">
                            @foreach ($formItems as $index => $item)
                                <tr>
                                    <td>
                                        <input class="form-control" name="items[{{ $index }}][item]"
                                            value="{{ $item['item'] ?? '' }}" placeholder="Product name">
                                        @error("items.$index.item")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <input class="form-control" name="items[{{ $index }}][brand]"
                                            value="{{ $item['brand'] ?? '' }}" placeholder="Preferred brand (optional)">
                                    </td>
                                    <td>
                                        <input class="form-control" type="number" min="0.01" step="any"
                                            name="items[{{ $index }}][quantity]"
                                            value="{{ $item['quantity'] ?? '' }}" placeholder="Quantity">
                                        @error("items.$index.quantity")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-danger remove-order-item" type="button"
                                            aria-label="Remove item">&times;</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('items')
                    <div class="text-danger mb-3">{{ $message }}</div>
                @enderror

                <button class="btn btn-outline-primary mb-4" id="add-order-item" type="button">Add another item</button>

                <div class="mb-4">
                    <label class="form-label" for="handwritten-list"><strong>Handwritten list (optional)</strong></label>
                    <input class="form-control" id="handwritten-list" name="handwritten_list" type="file"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <small class="form-text text-muted">Accepted formats: JPG, PNG, or PDF. Maximum file size: 10 MB.</small>
                    @error('handwritten_list')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @if ($editingOrder?->handwritten_list_path)
                        <div class="mt-2">
                            <a href="{{ route('user.order.list.download', $editingOrder) }}">Download current handwritten list</a>
                            <div class="form-check mt-2">
                                <input class="form-check-input" id="remove-handwritten-list" name="remove_handwritten_list"
                                    type="checkbox" value="1">
                                <label class="form-check-label" for="remove-handwritten-list">Remove the current handwritten list</label>
                            </div>
                        </div>
                    @endif
                </div>

                <button class="btn btn-primary" type="submit"
                    @disabled($posLocations->where('delivery_orders_enabled', true)->isEmpty() && ! $editingOrder)>
                    {{ $editingOrder ? 'Save Changes' : 'Place Order' }}
                </button>
                @if ($editingOrder)
                    <a class="btn btn-outline-secondary" href="{{ route('user.order') }}">Cancel</a>
                @endif
            </form>
        </div>
    </div>

    <section class="card order-list-card">
        <div class="card-header order-list-header">
            <div>
                <span class="order-page-eyebrow">Order activity</span>
                <h2 class="mb-1"><strong>Current Orders</strong></h2>
                <small>Orders remain here until delivered. You can edit an order before it is confirmed.</small>
            </div>
            <span class="order-count-badge">{{ $orders->count() }} {{ \Illuminate\Support\Str::plural('order', $orders->count()) }}</span>
        </div>
        <div class="card-body">
            @forelse ($orders as $order)
                <article class="order-entry-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div>
                            <strong class="order-entry-number">Order #{{ $order->id }}</strong>
                            <span class="badge order-status order-status--{{ strtolower($order->status) }} ms-2">{{ $order->status_label }}</span>
                            <div class="small order-entry-pos"><i class="fas fa-store me-1" aria-hidden="true"></i>{{ $order->pos?->name ?? 'Not assigned' }}</div>
                            <div class="small text-muted">Placed {{ $order->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                        <div class="mt-2 mt-md-0">
                            @if ($order->handwritten_list_path)
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('user.order.list.download', $order) }}">Handwritten list</a>
                            @endif
                            @if ($order->isEditable())
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('user.order.edit', $order) }}">Edit order</a>
                            @else
                                <span class="small text-muted">Editing is closed for this order.</span>
                            @endif
                        </div>
                    </div>

                    @if ($order->items)
                        <div class="table-responsive order-items-wrap">
                            <table class="table table-sm table-bordered mb-0 order-items-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Items</th>
                                        <th scope="col">Brand</th>
                                        <th scope="col">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item['item'] }}</td>
                                            <td>{{ $item['brand'] ?: '—' }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-0 text-muted">This order contains an uploaded handwritten list.</p>
                    @endif
                </article>
            @empty
                <div class="order-empty-state">
                    <span aria-hidden="true"><i class="fas fa-box-open"></i></span>
                    <strong>No current orders</strong>
                    <p class="mb-0">Orders you place will appear here until they are delivered.</p>
                </div>
            @endforelse
        </div>
    </section>
    </div>

    <template id="order-item-template">
        <tr>
            <td><input class="form-control" name="items[__INDEX__][item]" placeholder="Product name"></td>
            <td><input class="form-control" name="items[__INDEX__][brand]" placeholder="Preferred brand (optional)"></td>
            <td><input class="form-control" type="number" min="0.01" step="any"
                    name="items[__INDEX__][quantity]" placeholder="Quantity"></td>
            <td><button class="btn btn-outline-danger remove-order-item" type="button"
                    aria-label="Remove item">&times;</button></td>
        </tr>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemRows = document.getElementById('order-items');
            const itemTemplate = document.getElementById('order-item-template');
            let nextItemIndex = {{ count($formItems) ? max(array_keys($formItems)) + 1 : 0 }};

            document.getElementById('add-order-item').addEventListener('click', function () {
                itemRows.insertAdjacentHTML('beforeend', itemTemplate.innerHTML.replaceAll('__INDEX__', nextItemIndex++));
            });

            itemRows.addEventListener('click', function (event) {
                if (event.target.closest('.remove-order-item')) {
                    event.target.closest('tr').remove();
                }
            });

            @if (session('order_placed'))
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Order Placed',
                        text: 'Our Representative will contact you shortly',
                        icon: 'success'
                    });
                }
            @endif
        });
    </script>
@endsection
