@include('frontend.layout.header')

<style>
    .row {
        margin-left: 20px;
        margin-right: 20px;
    }

    .input-group .form-control-sm {
        max-width: 45px;
        font-size: 0.875rem;
    }

    .cart-summary {
        background-color: #dbe4ec;
        padding: 15px;
        border-radius: 5px;
        /* margin-top: 20px;
        margin-left: 50px; */

    }

    .cart-summary h3 {
        margin-bottom: 20px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    .cart-summary .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 23px;

    }

    .summary-item span {
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    .cart-summary .btn-primary {
        width: 100%;
    }
</style>
@section('content')
    <div class="row mb-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h1 class="text-center" style="font-family:Georgia, 'Times New Roman', Times, serif;">My Cart</h1>
        <div class="col-lg-9">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Product Title</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->title ?? 'N/A' }}</td>
                            <td>₹{{ number_format($item->product->price, 2) }}</td>
                            <td class="d-flex align-items-center">
                                <!-- Decrease Quantity Button -->
                                <form action="{{ route('cart.decrease', $item->product_id) }}" method="POST" class="me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info">-</button>
                                </form>
                                <!-- Quantity Display -->
                                <span>{{ $item->quantity }}</span>
                                <!-- Increase Quantity Button -->
                                <form action="{{ route('cart.increase', $item->product_id) }}" method="POST"
                                    class="ms-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info">+</button>
                                </form>
                            </td>
                            <!-- Display Total Price -->
                            <td>₹{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <!-- Remove Button -->
                            <td>
                                <form id="delete-form{{ $item->id }}" action="{{ route('cart.delete', $item->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-3 ">
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <hr>
                <div class="summary-item">
                    <span>Total Items:
                        <span style="color: red"> @php
                            $cart_count = count(App\Models\Cart::where('user_id', Auth::user()->id)->get());
                        @endphp
                            {{ $cart_count }}</span>
                    </span>
                </div>
                <div class="summary-item">
                    <span>Total Price:
                        <span style="color: red">₹{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                    </span>

                </div>
                <a href="" class="btn btn-success">Proceed to Payment</a>
            </div>
        </div>

    </div>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('delete-form' + id).submit();
            }
        }
    </script>
    @include('frontend.layout.footer')
