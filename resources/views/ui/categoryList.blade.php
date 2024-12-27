@include('ui.layout.master')
@include('ui.layout.header')



<!--Hero Section-->
<div class="hero-section hero-background">
    <h1 class="page-title">{{ $category->title }}</h1>
</div>

<!--Navigation section-->
<div class="container">
    <nav class="biolife-nav">
        <ul>
            <li class="nav-item"><a href="{{ route('frontend.index') }}" class="permal-link">Home</a></li>
            <li class="nav-item"><span class="current-page">{{ $category->title }}</span></li>
        </ul>
    </nav>
</div>

<div class="page-contain category-page no-sidebar">
    <div class="container">
        <div class="row">
            <!-- Main content -->
            <div id="main-content" class="main-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="block-item recently-products-cat md-margin-bottom-39">
                    <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile"
                        data-slick='{"rows":1,"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":0,"slidesToShow":5, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 3}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin": 10}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":10 }}]}'>
                        @foreach ($products as $data)
                            <li class="product-item">
                                <div class="contain-product layout-02">
                                    <div class="product-thumb">
                                        <a href="" class="link-to-product">
                                            <img src="{{ asset('images/' . $data->image) }}" alt="dd"
                                                width="270" height="270" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href=""
                                                class="pr-name">{{ $data->title }}</a>
                                        </h4>
                                        <div class="price">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>{{ $data->price }}</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>{{ $data->discount_price }}</span></del>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="product-category list-style">
                    <hr>

                    {{-- <div id="top-functions-area" class="top-functions-area">
                        <div class="flt-item to-left group-on-mobile">
                            <span class="flt-title">Refine</span>
                            <a href="" class="icon-for-mobile">
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                            <div class="wrap-selectors">
                                <form action="" name="frm-refine" method="get">
                                    <span class="title-for-mobile">Refine Products By</span>
                                    <div data-title="Price:" class="selector-item">
                                        <select name="price" class="selector">
                                            <option value="all">Price</option>
                                            <option value="class-1st">Less than 5$</option>
                                            <option value="class-2nd">$5-10$</option>
                                            <option value="class-3rd">$10-20$</option>
                                            <option value="class-4th">$20-45$</option>
                                            <option value="class-5th">$45-100$</option>
                                            <option value="class-6th">$100-150$</option>
                                            <option value="class-7th">More than 150$</option>
                                        </select>
                                    </div>
                                    <div data-title="Brand:" class="selector-item">
                                        <select name="brad" class="selector">
                                            <option value="all">Top brands</option>
                                            <option value="br2">Brand first</option>
                                            <option value="br3">Brand second</option>
                                            <option value="br4">Brand third</option>
                                            <option value="br5">Brand fourth</option>
                                            <option value="br6">Brand fiveth</option>
                                        </select>
                                    </div>
                                    <div data-title="Avalability:" class="selector-item">
                                        <select name="ability" class="selector">
                                            <option value="all">Availability</option>
                                            <option value="vl2">Availability 1</option>
                                            <option value="vl3">Availability 2</option>
                                            <option value="vl4">Availability 3</option>
                                            <option value="vl5">Availability 4</option>
                                            <option value="vl6">Availability 5</option>
                                        </select>
                                    </div>
                                    <p class="btn-for-mobile"><button type="submit" class="btn-submit">Go</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <div class="flt-item to-right">
                            <span class="flt-title">Sort</span>
                            <div class="wrap-selectors">
                                <div class="selector-item orderby-selector">
                                    <select name="orderby" class="orderby" aria-label="Shop order">
                                        <option value="menu_order" selected="selected">Default sorting</option>
                                        <option value="popularity">popularity</option>
                                        <option value="rating">average rating</option>
                                        <option value="date">newness</option>
                                        <option value="price">price: low to high</option>
                                        <option value="price-desc">price: high to low</option>
                                    </select>
                                </div>
                                <div class="selector-item viewmode-selector">
                                    <a href="category-grid-left-sidebar.html" class="viewmode grid-mode"><i
                                            class="biolife-icon icon-grid"></i></a>
                                    <a href="category-list-left-sidebar.html" class="viewmode detail-mode active"><i
                                            class="biolife-icon icon-list"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <ul class="products-list">
                            @foreach ($products as $data)
                                <li class="product-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="contain-product pr-detail-layout">
                                        <div class="product-thumb">
                                            <a href="" class="link-to-product">
                                                <img src="{{ asset('images/' . $data->image) }}" alt="dd"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                        </div>
                                        <div class="info">

                                            <h4 class="product-title"><a href=""
                                                    class="pr-name">{{ $data->title }}</a></h4>
                                            <p class="excerpt">{!! $data->description !!}</p>
                                            <div class="price">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>{{ $data->price }}</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>{{ $data->discount_price }}</span></del>
                                            </div>
                                            <div class="buttons">
                                                <a href="" class="btn wishlist-btn"><i class="fa fa-heart"
                                                        aria-hidden="true"></i></a>
                                                <a href="{{ route('cart.add', $data->id) }}"
                                                    class="btn add-to-cart-btn">add to cart</a>
                                                <a href="" class="btn compare-btn"><i class="fa fa-random"
                                                        aria-hidden="true"></i></a>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>

                    {{-- <div class="biolife-panigations-block">
                        <ul class="panigation-contain">
                            <li><span class="current-page">1</span></li>
                            <li><a href="" class="link-page">2</a></li>
                            <li><a href="" class="link-page">3</a></li>
                            <li><span class="sep">....</span></li>
                            <li><a href="" class="link-page">20</a></li>
                            <li><a href="" class="link-page next"><i class="fa fa-angle-right"
                                        aria-hidden="true"></i></a></li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@include('ui.layout.footer')
