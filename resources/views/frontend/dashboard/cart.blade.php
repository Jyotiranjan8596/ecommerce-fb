@include('frontend.dashboard.layouts.header')

<style>
    .row {
        margin-top: 50px;
    }

    .input-group .form-control-sm {
        max-width: 45px;
        font-size: 0.875rem;
    }

    .cart-summary {
        background-color:  #f7e6e9;;
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
        <h2 class="text-center" style="font-family:Georgia, 'Times New Roman', Times, serif;margin-left: -163px;">My Cart</h2>
        <div class="col-md-8">
           
            <table class="table table-border table-responsive">
                <thead style="background-color: #f7e6e9;">
                    <tr>
                        <th>Sl. No.</th>
                        <th>Product Title</th>
                        <th>Product Price</th>                     
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->title ?? 'N/A' }}</td>
                            <td>₹{{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <form id="delete-form{{ $item->id }}" action="{{ route('cart.delete', $item->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button class="btn btn-danger" onclick="confirmDelete({{ $item->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4 ">
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
                        <span style="color: red">₹{{ number_format($totalPrice, 2) }}</span>
                    </span>

                </div>
                <a href="" class="btn btn-info">Proceed to Payment</a>
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
   @include('frontend.dashboard.layouts.footer')
