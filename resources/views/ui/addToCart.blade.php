@include('ui.layout.master')
@include('ui.layout.header')

<!--Hero Section-->
<div class="hero-section hero-background">
    <h1 class="page-title">Add To Cart</h1>
</div>

<!--Navigation section-->
<div class="container">
    <nav class="biolife-nav">
        <ul>
            <li class="nav-item"><a href="{{ route('frontend.index') }}" class="permal-link">Home</a></li>
            <li class="nav-item"><span class="current-page">ShoppingCart</span></li>
        </ul>
    </nav>
</div>

<div class="page-contain shopping-cart">

    <!-- Main content -->
    <div id="main-content" class="main-content">
        <div class="container">
            <!--Cart Table-->
            <div class="shopping-cart-container">
                <div class="row">
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <h3 class="box-title">Your cart items</h3>
                        <form class="shopping-cart-form" action="" method="post">
                            <table class="shop_table cart-form">
                                <thead>
                                    <tr>
                                        <th class="product-name">Product Name</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_price = 0;
                                    @endphp
                                    @foreach ($cartItems as $cart)
                                        <tr class="cart_item">
                                            <td class="product-thumbnail" data-title="Product Name">
                                                <a class="prd-thumb" href="">
                                                    <figure><img width="113" height="113"
                                                            src="{{ asset('images/' . $cart->product->image) }}"
                                                            alt="shipping cart"></figure>
                                                </a>
                                                <a class="prd-name" href="">{{ $cart->product->title }}</a>
                                                <div class="action">
                                                    <a href="javascript:void(0);" class="remove"
                                                        onclick="confirmDelete({{ $cart->id }})">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $cart->id }}"
                                                        action="{{ route('cart.delete', $cart->id) }}" method="GET"
                                                        style="display: none;">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="product-price" data-title="Price">
                                                <div class="price price-contain">
                                                    <ins><span class="price-amount"><span
                                                                class="currencySymbol">₹</span>{{ number_format($cart->product->total_price, 2) }}</span></ins>

                                                </div>
                                            </td>
                                            <td class="product-quantity" data-title="Quantity">
                                                <div class="quantity-box type1" data-cart-id="{{ $cart->id }}">
                                                    <div class="qty-input">
                                                        <button type="button" class="qty-btn btn-up">
                                                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                        </button>
                                                        <input type="text" name="qty{{ $cart->id }}"
                                                            value="{{ $cart->quantity }}" data-max_value="200"
                                                            data-min_value="1" data-step="1">
                                                        <button type="button" class="qty-btn btn-down">
                                                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="product-subtotal" data-title="Total">
                                                <div class="price price-contain">
                                                    <ins><span class="price-amount">₹<span
                                                                class="totalPrice{{ $cart->id }}">{{ number_format($cart->total_price, 2) }}</span></span></ins>

                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $total_price += $cart->total_price;
                                        @endphp
                                    @endforeach
                                    <tr class="cart_item wrap-buttons">
                                        <td class="wrap-btn-control" colspan="4">
                                            <a href="{{ route('frontend.index') }}" class="btn back-to-shop">Back to
                                                Shop</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="shpcart-subtotal-block">
                            <div class="subtotal-line">
                                <b class="stt-name">Subtotal <span class="sub">({{ count($cartItems) }}
                                        items)</span></b>
                                <span class="stt-price">₹ <span
                                        class="sub_total">{{ number_format($total_price, 2) }}</span></span>
                            </div>

                            {{-- <div class="tax-fee">
                                <p class="title">Est. Taxes & Fees</p>
                                <p class="desc">Based on 56789</p>
                            </div> --}}
                            <div class="btn-checkout">
                                <a href="{{ route('cart.checkout') }}" class="btn checkout">Buy Now</a>
                            </div>
                            <div class="biolife-progress-bar">
                                {{-- <table>
                                    <tr>
                                        <td class="first-position">
                                            <span class="index">$0</span>
                                        </td>
                                        <td class="mid-position">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td class="last-position">
                                            <span class="index">$99</span>
                                        </td>
                                    </tr>
                                </table> --}}
                            </div>
                            {{-- <p class="pickup-info"><b>Free Pickup</b> is available as soon as today More about shipping
                                and pickup</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function confirmDelete(id) {
        var form = document.getElementById('delete-form-' + id);
        if (form) {
            if (confirm('Are you sure you want to delete this product?')) {
                form.submit();
            }
        } else {
            console.error("Form not found for ID " + id);
        }
    }
</script>

<script>
    $(document).ready(function() {
        // Increase quantity
        $(document).on('click', '.btn-up', function(e) {
            e.preventDefault();

            var cartId = $(this).closest('.quantity-box').data('cart-id');

            var currentQuantity = parseInt($(this).siblings('input').val());
            var sub_total = $('.sub_total').text();

            $.ajax({
                url: '{{ route('increase.quantity', ':cartId') }}'.replace(':cartId',
                    cartId), // Replace placeholder
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // Include CSRF token
                    quantity: currentQuantity + 1,
                    sub_total: sub_total
                },
                success: function(response) {
                    if (response.newQuantity) {
                        console.log(response);
                        $('input[name="qty' + cartId + '"]').val(response.newQuantity);
                        $('.totalPrice' + cartId).text(response.newTotalPrice);
                        $('.sub_total').text(response.newsub_total);
                    }
                },

            });
        });


        // Decrease quantity
        $(document).on('click', '.btn-down', function(e) {
            e.preventDefault();
            var cartId = $(this).closest('.quantity-box').data('cart-id');
            console.log(cartId);
            var currentQuantity = parseInt($(this).siblings('input').val());
            var sub_total = $('.sub_total').text();
            $.ajax({
                url: '{{ route('decrease.quantity', ':cartId') }}'.replace(':cartId',
                    cartId),
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: currentQuantity - 1,
                    sub_total: sub_total
                },
                success: function(response) {
                    if (response.newQuantity) {
                        // Update the quantity input
                        $('input[name="qty' + cartId + '"]').val(response.newQuantity);

                        // Update the total price
                        $('.totalPrice' + cartId).text(response.newTotalPrice);
                        $('.sub_total').text(response.newsub_total);
                    }
                },
            });
        });
    });
</script>


@include('ui.layout.footer')
