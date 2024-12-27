@include('ui.layout.master')
@include('ui.layout.header')

<!--Hero Section-->
<div class="hero-section hero-background">
    <h1 class="page-title">Check Out</h1>
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

<div class="page-contain checkout">

    <!-- Main content -->
    <div id="main-content" class="main-content">
        <div class="container sm-margin-top-2px">
            <div class="row">

                <!--Order Summary-->
                <div
                    class="col-lg-6 col-md-6 col-sm-6 col-xs-12 sm-padding-top-48px sm-margin-bottom-0 xs-margin-bottom-15px">
                    <div class="order-summary sm-margin-bottom-80px">
                        <div class="title-block">
                            <h3 class="title">Order Summary</h3>
                        </div>
                        <div class="cart-list-box short-type">
                            <span class="number"></span>
                            <ul class="cart-list">
                                @php
                                    $total_price = 0;
                                @endphp
                                @foreach ($cartItems as $items)
                                    <li class="cart-elem">
                                        <div class="cart-item">
                                            <div class="product-thumb">
                                                <a class="prd-thumb" href="">
                                                    <figure><img src="{{ asset('images/' . $items->product->image) }}"
                                                            width="113" height="113" alt="shop-cart"></figure>
                                                </a>
                                            </div>
                                            <div class="info">
                                                <span class="txt-quantity"
                                                    style="font-size: 12px;">{{ $items->product->sector->title }}</span>
                                                <h4 class="pr-name">{{ $items->product->title }}</h4>
                                                <div class="action">
                                                    <a href="javascript:void(0);" class="remove"
                                                        onclick="confirmDelete({{ $items->id }})">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $items->id }}"
                                                        action="{{ route('cart.delete', $items->id) }}" method="GET"
                                                        style="display: none;">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="price price-contain">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>{{ number_format($items->total_price), 2 }}</span></ins>

                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $total_price += $items->total_price;
                                    @endphp
                                @endforeach

                            </ul>
                            <ul class="subtotal">
                                <li>
                                    <div class="subtotal-line">
                                        <b class="stt-name">Total Price</b>
                                        <span class="stt-price">₹{{ number_format($total_price), 2 }}</span>
                                    </div>
                                </li>
                                {{-- <li>
                                    <div class="subtotal-line">
                                        <b class="stt-name">Shipping</b>
                                        <span class="stt-price">₹20.00</span>
                                    </div>
                                </li> --}}
                                {{-- <li>
                                    <div class="subtotal-line">
                                        <b class="stt-name">Tax</b>
                                        <span class="stt-price">₹0.00</span>
                                    </div>
                                </li> --}}
                                {{-- <li>
                                    <div class="subtotal-line">
                                        <b class="stt-name">total:</b>
                                        <span class="stt-price">₹190.00</span>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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

@include('ui.layout.footer')
