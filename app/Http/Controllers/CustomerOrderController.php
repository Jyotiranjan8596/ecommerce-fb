<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\PosModel;
use App\Support\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class CustomerOrderController extends Controller
{
    public function create()
    {
        return $this->orderPage();
    }

    public function edit(CustomerOrder $order)
    {
        $this->ensureOwnedOrder($order);

        if (! $order->isEditable()) {
            return redirect()->route('user.order')
                ->with('error', 'This order can no longer be edited because it has been confirmed or prepared for dispatch.');
        }

        return $this->orderPage($order);
    }

    public function store(Request $request)
    {
        $validated = $this->validateOrder($request, $request->hasFile('handwritten_list'));
        $items = $this->normalizedItems($validated['items'] ?? []);
        $filePath = $this->storeHandwrittenList($request);
        $posId = (int) $validated['pos_id'];
        $order = null;

        try {
            $order = DB::transaction(function () use ($items, $filePath, $posId) {
                return CustomerOrder::create([
                    'user_id' => auth()->id(),
                    'pos_id' => $posId,
                    'items' => $items,
                    'handwritten_list_path' => $filePath,
                ]);
            });
        } catch (Throwable $exception) {
            if ($filePath !== null) {
                Storage::delete($filePath);
            }

            throw $exception;
        }

        $this->notifyOrderPlaced($order);

        return redirect()->route('user.order')->with('order_placed', true);
    }

    public function update(Request $request, CustomerOrder $order)
    {
        $this->ensureOwnedOrder($order);

        if (! $order->isEditable()) {
            return redirect()->route('user.order')
                ->with('error', 'This order can no longer be edited because it has been confirmed or prepared for dispatch.');
        }

        $previousPosId = (int) $order->pos_id;
        $removeList = $request->boolean('remove_handwritten_list');
        $validated = $this->validateOrder(
            $request,
            $request->hasFile('handwritten_list'),
            $order->handwritten_list_path !== null && ! $removeList,
            $order->pos_id
        );
        $items = $this->normalizedItems($validated['items'] ?? []);
        $newFilePath = $this->storeHandwrittenList($request);
        $posId = (int) $validated['pos_id'];
        $oldFilePath = null;

        try {
            DB::transaction(function () use ($order, $items, $newFilePath, $posId, $removeList, &$oldFilePath) {
                $lockedOrder = CustomerOrder::query()
                    ->where('user_id', auth()->id())
                    ->whereKey($order->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedOrder->isEditable()) {
                    throw ValidationException::withMessages([
                        'order' => 'This order can no longer be edited because it has been confirmed or prepared for dispatch.',
                    ]);
                }

                $lockedOrder->pos_id = $posId;
                $lockedOrder->items = $items;

                if ($newFilePath !== null) {
                    $oldFilePath = $lockedOrder->handwritten_list_path;
                    $lockedOrder->handwritten_list_path = $newFilePath;
                } elseif ($removeList) {
                    $oldFilePath = $lockedOrder->handwritten_list_path;
                    $lockedOrder->handwritten_list_path = null;
                }

                $lockedOrder->save();
            });
        } catch (Throwable $exception) {
            if ($newFilePath !== null) {
                Storage::delete($newFilePath);
            }

            throw $exception;
        }

        if ($oldFilePath !== null && ! Storage::delete($oldFilePath)) {
            Log::warning('Could not remove a replaced customer order list.', ['path' => $oldFilePath]);
        }

        $order->refresh();
        $this->notifyOrderUpdated($order, $previousPosId);

        return redirect()->route('user.order')->with('message', 'Your order has been updated.');
    }

    public function downloadList(CustomerOrder $order)
    {
        $this->ensureOwnedOrder($order);

        abort_unless(
            $order->handwritten_list_path && Storage::exists($order->handwritten_list_path),
            404
        );

        return Storage::download($order->handwritten_list_path);
    }

    public function posIndex()
    {
        $pos = $this->authenticatedPos();
        $orders = CustomerOrder::query()
            ->with('customer')
            ->where('pos_id', $pos->id)
            ->whereRaw('LOWER(status) <> ?', [CustomerOrder::STATUS_DELIVERED])
            ->latest()
            ->get();

        return view('pos.customer_orders', compact('orders', 'pos'));
    }

    public function adminIndex()
    {
        $orders = CustomerOrder::query()
            ->with(['customer', 'pos'])
            ->latest()
            ->paginate(25);
        $totalOrders = CustomerOrder::count();
        $pendingOrders = CustomerOrder::where('status', CustomerOrder::STATUS_PENDING)->count();
        $deliveredOrders = CustomerOrder::where('status', CustomerOrder::STATUS_DELIVERED)->count();

        return view('admin.customer_orders', compact('orders', 'totalOrders', 'pendingOrders', 'deliveredOrders'));
    }

    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $pos = $this->authenticatedPos();
        abort_unless((int) $order->pos_id === (int) $pos->id, 404);

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,ready_to_dispatch,delivered'],
        ]);

        $nextStatus = [
            CustomerOrder::STATUS_PENDING => CustomerOrder::STATUS_CONFIRMED,
            CustomerOrder::STATUS_CONFIRMED => CustomerOrder::STATUS_READY_TO_DISPATCH,
            CustomerOrder::STATUS_READY_TO_DISPATCH => CustomerOrder::STATUS_DELIVERED,
        ];

        $lockedOrder = DB::transaction(function () use ($order, $pos, $validated, $nextStatus) {
            $lockedOrder = CustomerOrder::query()
                ->where('pos_id', $pos->id)
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $currentStatus = strtolower(trim($lockedOrder->status));
            if (($nextStatus[$currentStatus] ?? null) !== $validated['status']) {
                abort(422, 'Orders must move through the status steps in order.');
            }

            $lockedOrder->status = $validated['status'];
            $lockedOrder->save();

            return $lockedOrder;
        });

        $this->notifyStatusChanged($lockedOrder);

        return redirect()->route('pos.customer-orders.index')
            ->with('message', 'Order #' . $lockedOrder->id . ' status updated to ' . $lockedOrder->status_label . '.');
    }

    public function downloadPosList(CustomerOrder $order)
    {
        $pos = $this->authenticatedPos();
        abort_unless((int) $order->pos_id === (int) $pos->id, 404);
        abort_unless(
            $order->handwritten_list_path && Storage::exists($order->handwritten_list_path),
            404
        );

        return Storage::download($order->handwritten_list_path);
    }

    private function orderPage(?CustomerOrder $editingOrder = null)
    {
        $orders = CustomerOrder::query()
            ->where('user_id', auth()->id())
            ->whereRaw('LOWER(status) <> ?', [CustomerOrder::STATUS_DELIVERED])
            ->latest()
            ->get();
        $posQuery = PosModel::query()->where('delivery_orders_enabled', true);
        if ($editingOrder?->pos_id) {
            $posQuery->orWhere('id', $editingOrder->pos_id);
        }
        $posLocations = $posQuery->orderBy('name')->get();
        $defaultPosId = $this->defaultPosId();

        return view('frontend.dashboard.my_order', compact('orders', 'editingOrder', 'posLocations', 'defaultPosId'));
    }

    private function ensureOwnedOrder(CustomerOrder $order): void
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 404);
    }

    /**
     * Notify the customer, the POS owner and all admins that a new order was placed.
     */
    private function notifyOrderPlaced(CustomerOrder $order): void
    {
        $order->loadMissing('customer', 'pos.user');
        $customerName = $order->customer->name ?? 'A customer';
        $posName = $order->pos->name ?? 'your store';

        Notify::customer(
            $order->customer,
            'Order placed successfully',
            'Your order #' . $order->id . ' at ' . $posName . ' has been placed and is awaiting confirmation.',
            'success',
            route('user.order')
        );

        Notify::pos(
            $order->pos->user ?? null,
            'New customer order received',
            $customerName . ' placed order #' . $order->id . '. Please review and confirm.',
            'info',
            route('pos.customer-orders.index')
        );

        Notify::admins(
            'New order placed',
            $customerName . ' placed order #' . $order->id . ' at ' . $posName . '.',
            'info',
            route('admin.customer-orders.index')
        );
    }

    /** Notify the customer, current POS and admins when a pending order is edited. */
    private function notifyOrderUpdated(CustomerOrder $order, int $previousPosId): void
    {
        $order->loadMissing('customer', 'pos.user');
        $customerName = $order->customer->name ?? 'A customer';

        Notify::customer(
            $order->customer,
            'Order updated',
            'Your order #' . $order->id . ' details have been updated.',
            'info',
            route('user.order')
        );

        $posUser = $order->pos->user ?? null;
        Notify::pos(
            $posUser,
            'Customer order updated',
            $customerName . ' updated order #' . $order->id . '. Please review the latest details.',
            'warning',
            route('pos.customer-orders.index')
        );

        if ($previousPosId !== (int) $order->pos_id) {
            $previousPosUser = PosModel::query()->with('user')->find($previousPosId)?->user;
            if ($previousPosUser && (int) $previousPosUser->id !== (int) ($posUser->id ?? 0)) {
                Notify::pos(
                    $previousPosUser,
                    'Order moved to another POS',
                    'Order #' . $order->id . ' was reassigned to another POS.',
                    'warning',
                    route('pos.customer-orders.index')
                );
            }
        }

        Notify::admins(
            'Customer order updated',
            $customerName . ' updated order #' . $order->id . '.',
            'info',
            route('admin.customer-orders.index')
        );
    }

    /**
     * Notify the customer whenever the POS moves their order to the next status.
     */
    private function notifyStatusChanged(CustomerOrder $order): void
    {
        $order->loadMissing('customer', 'pos');

        Notify::customer(
            $order->customer,
            'Order status updated',
            'Your order #' . $order->id . ' is now "' . $order->status_label . '".',
            $order->status === CustomerOrder::STATUS_DELIVERED ? 'success' : 'info',
            route('user.order')
        );

        Notify::pos(
            $order->pos->user ?? null,
            'Order status updated',
            'Order #' . $order->id . ' is now "' . $order->status_label . '".',
            $order->status === CustomerOrder::STATUS_DELIVERED ? 'success' : 'info',
            route('pos.customer-orders.index')
        );

        Notify::admins(
            'Order status updated',
            'Order #' . $order->id . ' is now "' . $order->status_label . '".',
            $order->status === CustomerOrder::STATUS_DELIVERED ? 'success' : 'info',
            route('admin.customer-orders.index')
        );
    }

    private function validateOrder(
        Request $request,
        bool $hasNewList,
        bool $hasExistingList = false,
        ?int $existingOrderPosId = null
    ): array
    {
        $validator = Validator::make($request->all(), [
            'pos_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($existingOrderPosId) {
                    $offersDelivery = PosModel::query()
                        ->whereKey($value)
                        ->where('delivery_orders_enabled', true)
                        ->exists();

                    if (! $offersDelivery && (int) $value !== $existingOrderPosId) {
                        $fail('Choose a POS that currently offers delivery orders.');
                    }
                },
            ],
            'items' => ['nullable', 'array', 'max:50'],
            'items.*.item' => ['nullable', 'string', 'max:255'],
            'items.*.brand' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0.01', 'max:99999'],
            'handwritten_list' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'remove_handwritten_list' => ['nullable', 'boolean'],
        ]);

        $validator->after(function ($validator) use ($request, $hasNewList, $hasExistingList) {
            $hasCompleteItem = false;
            $submittedItems = $request->input('items', []);

            foreach (is_array($submittedItems) ? $submittedItems : [] as $index => $item) {
                if (! is_array($item)) {
                    continue;
                }

                $itemNameValue = $item['item'] ?? null;
                $brandValue = $item['brand'] ?? null;
                $itemName = is_scalar($itemNameValue) ? trim((string) $itemNameValue) : '';
                $brand = is_scalar($brandValue) ? trim((string) $brandValue) : '';
                $quantity = $item['quantity'] ?? null;

                if ($itemName === '' && $brand === '' && ($quantity === null || $quantity === '')) {
                    continue;
                }

                if ($itemName === '') {
                    $validator->errors()->add("items.$index.item", 'Enter the item name for this row.');
                }

                if ($quantity === null || $quantity === '') {
                    $validator->errors()->add("items.$index.quantity", 'Enter a quantity for this row.');
                } else {
                    $hasCompleteItem = $hasCompleteItem || $itemName !== '';
                }
            }

            if (! $hasCompleteItem && ! $hasNewList && ! $hasExistingList) {
                $validator->errors()->add('items', 'Add at least one item or upload a handwritten list.');
            }
        });

        return $validator->validate();
    }

    private function defaultPosId(): ?int
    {
        $preferredPos = PosModel::query()
            ->where('delivery_orders_enabled', true)
            ->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", ['freebazar1'])
            ->value('id');

        return $preferredPos ? (int) $preferredPos : null;
    }

    private function authenticatedPos(): PosModel
    {
        $pos = PosModel::query()
            ->where('user_id', auth()->user()->user_id)
            ->first();

        abort_unless($pos, 403, 'No POS location is associated with this account.');

        return $pos;
    }

    private function normalizedItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => filled($item['item'] ?? null))
            ->map(fn ($item) => [
                'item' => trim($item['item']),
                'brand' => filled($item['brand'] ?? null) ? trim($item['brand']) : null,
                'quantity' => $item['quantity'],
            ])
            ->values()
            ->all();
    }

    private function storeHandwrittenList(Request $request): ?string
    {
        if (! $request->hasFile('handwritten_list')) {
            return null;
        }

        $filePath = $request->file('handwritten_list')->store('customer-order-lists');

        if ($filePath === false) {
            throw new RuntimeException('The handwritten order list could not be saved.');
        }

        return $filePath;
    }
}
