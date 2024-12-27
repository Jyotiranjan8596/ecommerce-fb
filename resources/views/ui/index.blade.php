@include('ui.layout.master')
@include('ui.layout.header')
<!-- Page Contain -->
<div class="page-contain">

    <!-- Main content -->
    <div id="main-content" class="main-content">

        <!--Block 01: Vertical Menu And Main Slide-->
        <div class="container">

            <div class="row">
                <div class="col-lg-3 col-md-4 hidden-sm hidden-xs">
                    <div class="biolife-vertical-menu none-box-shadow  ">
                        <div class="vertical-menu vertical-category-block always ">
                            <div class="block-title">
                                <span class="menu-icon">
                                    <span class="line-1"></span>
                                    <span class="line-2"></span>
                                    <span class="line-3"></span>
                                </span>
                                <span class="menu-title">Top Categories</span>
                                <span class="angle" data-tgleclass="fa fa-caret-down"><i class="fa fa-caret-up"
                                        aria-hidden="true"></i></span>
                            </div>
                            <div class="wrap-menu">
                                <ul class="menu clone-main-menu">
                                    @foreach ($category as $data)
                                        <li class="menu-item">
                                            <a href="{{ route('frontend.category', $data->id) }}" class="menu-name"
                                                data-title="Fruit & Nut Gifts"><i>{{ $data->title }}</i></a>
                                            {{-- <ul class="sub-menu">
                                                <li class="menu-item" style="color: orange;font-size:18px;">
                                                    <b>{{ $data->title }}</b>
                                                </li>
                                                <li class="menu-item"><a href="#">Omelettes</a></li>
                                                <li class="menu-item"><a href="#">Breakfast Scrambles</a></li>

                                                <li class="menu-item"><a href="#">Griddle</a></li>

                                                <li class="menu-item"><a href="#">Biscuits</a></li>
                                                <li class="menu-item"><a href="#">Seasonal Fruit Plate</a></li>
                                            </ul> --}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 col-xs-12">
                    <div class="main-slide block-slider nav-change hover-main-color type02">
                        <ul class="biolife-carousel"
                            data-slick='{"arrows": true, "dots": false, "slidesMargin": 0, "slidesToShow": 1, "infinite": true, "speed": 800}'>
                            @foreach ($banner as $data)
                                <li>
                                    <div class="slide-contain slider-opt04__layout01 light-version first-slide">
                                        <div class="media"></div>
                                        <div class="text-content">
                                            <i class="first-line">{{ $data->title }}</i>
                                            <h3 class="second-line">{{ $data->sub_title }}</h3>
                                            <p class="third-line">{{ $data->description }}</p>
                                            {{-- <p class="buttons">
                                                <a href="" class="btn btn-bold">Shop now</a>
                                                <a href="#" class="btn btn-thin">View lookbook</a>
                                            </p> --}}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- <!--Block 02: Banners-->
        <div class="banner-block sm-margin-bottom-57px xs-margin-top-80px sm-margin-top-30px">
            <div class="container">
                <ul class="biolife-carousel nav-center-bold nav-none-on-mobile"
                    data-slick='{"rows":1,"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":30,"slidesToShow":3, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 3}},{"breakpoint":992, "settings":{ "slidesToShow": 2}},{"breakpoint":768, "settings":{ "slidesToShow": 2}}, {"breakpoint":500, "settings":{ "slidesToShow": 1}}]}'>
                    <li>
                        <div class="biolife-banner biolife-banner__style-08">
                            <div class="banner-contain">
                                <div class="media">
                                    <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style08.png"
                                            width="193" height="185" alt=""></a>
                                </div>
                                <div class="text-content">
                                    <span class="text1">Sumer Fruit</span>
                                    <b class="text2">100% Pure Natural Fruit Juice</b>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="biolife-banner biolife-banner__style-09">
                            <div class="banner-contain">
                                <div class="media">
                                    <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style09.png"
                                            width="191" height="185" alt=""></a>
                                </div>
                                <div class="text-content">
                                    <span class="text1">California</span>
                                    <b class="text2">Fresh Fruit</b>
                                    <span class="text3">Association</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="biolife-banner biolife-banner__style-10">
                            <div class="banner-contain">
                                <div class="media">
                                    <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style10.png"
                                            width="185" height="185" alt=""></a>
                                </div>
                                <div class="text-content">
                                    <span class="text1">Naturally fresh taste</span>
                                    <p class="text2">With <span>25% Off</span> All Teas</p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div> --}}

        <!--Block 03: Categories-->
        <div class="wrap-category xs-margin-top-20px">
            <div class="container">
                <div class="biolife-title-box style-02 xs-margin-bottom-33px">
                    <h3 class="main-title">Top Categories</h3>
                </div>
                <ul class="biolife-carousel nav-center-bold nav-none-on-mobile"
                    data-slick='{"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":30,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 3}},{"breakpoint":992, "settings":{ "slidesToShow": 3}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":10}}, {"breakpoint":500, "settings":{ "slidesToShow": 1}}]}'>
                    @foreach ($category as $data)
                        <li>
                            <div class="biolife-cat-box-item">
                                <div class="cat-thumb">
                                    <a href="{{ route('frontend.category', $data->id) }}" class="cat-link">
                                        <img src="{{ asset('images/' . $data->image) }}" width="277" height="185"
                                            alt="">
                                    </a>
                                </div>
                                <a class="cat-info" href="{{ route('frontend.category', $data->id) }}">
                                    <h4 class="cat-name">{{ $data->title }}</h4>

                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                {{-- <div class="biolife-service type01 biolife-service__type01 xs-margin-top-60px sm-margin-top-45px">
                    <ul class="services-list">
                        <li>
                            <div class="service-inner color-reverse">
                                <span class="number">1</span>
                                <span class="biolife-icon icon-beer"></span>
                                <a class="srv-name" href="#">full stamped product</a>
                            </div>
                        </li>
                        <li>
                            <div class="service-inner color-reverse">
                                <span class="number">2</span>
                                <span class="biolife-icon icon-schedule"></span>
                                <a class="srv-name" href="#">place and delivery on time</a>
                            </div>
                        </li>
                        <li>
                            <div class="service-inner color-reverse">
                                <span class="number">3</span>
                                <span class="biolife-icon icon-car"></span>
                                <a class="srv-name" href="#">Free shipping in the city</a>
                            </div>
                        </li>
                    </ul>
                </div> --}}
            </div>
        </div>

        <!--Block 04: Product Tab-->
        <div class="product-tab z-index-20 sm-margin-top-59px xs-margin-top-20px">
            <div class="container">
                <div class="biolife-title-box slim-item">
                    <h3 class="main-title">Our Products</h3>
                </div>
                <div class="biolife-tab biolife-tab-contain sm-margin-top-23px">
                    {{-- <div class="tab-head tab-head__sample-layout">
                            <ul class="tabs">
                                <li class="tab-element active">
                                    <a href="#tab01_1st" class="tab-link">Featured</a>
                                </li>
                                <li class="tab-element">
                                    <a href="#tab01_2nd" class="tab-link">Top Rated</a>
                                </li>
                                <li class="tab-element">
                                    <a href="#tab01_3rd" class="tab-link">On Sale</a>
                                </li>
                            </ul>
                        </div> --}}
                    <div class="tab-content">
                        <div id="tab01_1st" class="tab-contain active">
                            <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile eq-height-contain"
                                data-slick='{"rows":1 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":10,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":20 }},{"breakpoint":768, "settings":{ "slidesToShow": 2,"rows":2, "slidesMargin":15 }}]}'>
                                @foreach ($products as $data)
                                    <li class="product-item">
                                        <div class="contain-product layout-default">
                                            <div class="product-thumb">
                                                <a href="{{ route('frontend.product', $data->id) }}"
                                                    class="link-to-product">
                                                    <img src="{{ asset('images/' . $data->image) }}" alt="Vegetables"
                                                        width="270" height="270" class="product-thumnail">
                                                </a>
                                                <a class="lookup btn_call_quickview" href="#"><i
                                                        class="biolife-icon icon-search"></i></a>
                                            </div>
                                            <div class="info">
                                                <b class="categories">{{ $data->sector->title }}</b>
                                                <h4 class="product-title"><a
                                                        href="{{ route('frontend.product', $data->id) }}"
                                                        class="pr-name">{{ $data->title }}</a></h4>
                                                <div class="price ">
                                                    <ins><span class="price-amount"><span
                                                                class="currencySymbol">₹</span>{{ $data->price }}</span></ins>
                                                    <del><span class="price-amount"><span
                                                                class="currencySymbol">₹</span>{{ $data->discount_price }}</span></del>
                                                </div>
                                                <div class="slide-down-box">

                                                    <div class="buttons">
                                                        <a href="#" class="btn wishlist-btn"><i
                                                                class="fa fa-heart" aria-hidden="true"></i></a>
                                                        <a href="{{ route('cart.add', $data->id) }}"
                                                            class="btn add-to-cart-btn"><i class="fa fa-cart-arrow-down"
                                                                aria-hidden="true"></i>add
                                                            to cart</a>
                                                        <a href="#" class="btn compare-btn"><i
                                                                class="fa fa-random" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- <div id="tab01_2nd" class="tab-contain ">
                            <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile eq-height-contain"
                                data-slick='{"rows":1 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":10,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":20 }},{"breakpoint":768, "settings":{ "slidesToShow": 2,"rows":2, "slidesMargin":15 }}]}'>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-20.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">National
                                                    Fresh Fruit</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-02.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Hot Chili
                                                    Peppers Magnetic Salt</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-19.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                    Fairytale</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-03.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Passover
                                                    Cauliflower Kugel</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-07.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">13 Healing
                                                    Powers of Lemons</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-18.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">National
                                                    Fresh Fruit</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-05.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Organic
                                                    Hass Avocado, Large</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-06.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Packham's
                                                    Pears</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-01.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Organic
                                                    Hass Avocado</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-22.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Cherry
                                                    Tomato Seeds</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div id="tab01_3rd" class="tab-contain ">
                            <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile eq-height-contain"
                                data-slick='{"rows":1 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":10,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":20 }},{"breakpoint":768, "settings":{ "slidesToShow": 2,"rows":2, "slidesMargin":15 }}]}'>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-05.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Organic
                                                    Hass Avocado, Large</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-02.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Hot Chili
                                                    Peppers Magnetic Salt</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-01.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Organic
                                                    Hass Avocado</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-06.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Packham's
                                                    Pears</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-07.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">13 Healing
                                                    Powers of Lemons</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-18.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">National
                                                    Fresh Fruit</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-20.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">National
                                                    Fresh Fruit</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-22.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Cherry
                                                    Tomato Seeds</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-19.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                    Fairytale</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="assets/images/products/p-03.jpg" alt="Vegetables"
                                                    width="270" height="270" class="product-thumnail">
                                            </a>
                                            <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a>
                                        </div>
                                        <div class="info">
                                            <b class="categories">Vegetables</b>
                                            <h4 class="product-title"><a href="#" class="pr-name">Passover
                                                    Cauliflower Kugel</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>85.00</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>95.00</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <p class="message">All products are carefully selected to ensure food
                                                    safety.</p>
                                                <div class="buttons">
                                                    <a href="#" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="#" class="btn add-to-cart-btn"><i
                                                            class="fa fa-cart-arrow-down" aria-hidden="true"></i>add
                                                        to cart</a>
                                                    <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <!--Block 05: Banner Promotion-->
        {{-- <div class="banner-promotion-04 xs-margin-top-50px sm-margin-top-40px">
            <div class="biolife-banner promotion4 biolife-banner__promotion4 v2">
                <div class="container">
                    <div class="banner-contain">
                        <div class="media">
                            <div class="img-moving position-1">
                                <a href="#" class="banner-link"><img
                                        src="assets/images/home-04/bn_promotion-child01-2.png" width="780"
                                        height="450" alt="img msv"></a>
                            </div>
                            <div class="img-moving position-2">
                                <img src="assets/images/home-04/bn_promotion-child02-2.png" width="149"
                                    height="139" alt="img msv">
                            </div>
                        </div>
                        <div class="text-content">
                            <span class="sub-line">Special Offer!</span>
                            <b class="first-line">Special discount<br>for all fruit products</b>
                            <div class="biolife-countdown" data-datetime="2020-01-18 00:00 +00:00"></div>
                            <p class="buttons">
                                <a href="#" class="btn btn-bold">See Offer Now!</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!--Block 06: Advance Box-->
        <div class="container z-index-20 xs-margin-top-80px sm-margin-top-0">
            <div class="row">
                <div class="col-lg-12 sm-margin-top-64px">
                    <div class="advance-product-box">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div
                                class="biolife-title-box bold-style biolife-title-box__bold-style xs-margin-top-80px-im sm-margin-top-0-im lg-margin-bottom-26px-im">
                                <h3 class="title" style="text-align: center;">Bestseller Products</h3>
                            </div>
                        </div>
                        <ul class="products-list biolife-carousel nav-top-right nav-main-color nav-none-on-mobile eq-height-contain"
                            data-slick='{"rows":2 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":0,"slidesToShow":3, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin": 20}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin": 15}}]}'>
                            @foreach ($bestseller as $seller)
                                <li class="product-item">
                                    <div class="contain-product layout-default">
                                        <div class="product-thumb">
                                            <a href="#" class="link-to-product">
                                                <img src="{{ asset('images/' . $seller->image) }}" alt="Vegetables"
                                                    width="200" height="200" class="product-thumnail">
                                            </a>
                                            {{-- <a class="lookup btn_call_quickview" href="#"><i
                                                    class="biolife-icon icon-search"></i></a> --}}
                                        </div>
                                        <div class="info">
                                            <b class="categories">{{ $seller->sector->title }}</b>
                                            <h4 class="product-title"><a href="#"
                                                    class="pr-name">{{ $seller->title }}</a></h4>
                                            <div class="price ">
                                                <ins><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>{{ number_format($seller->price, 2) }}</span></ins>
                                                <del><span class="price-amount"><span
                                                            class="currencySymbol">₹</span>{{ $seller->discount_price }}</span></del>
                                            </div>
                                            <div class="slide-down-box">
                                                <div class="buttons">
                                                    <a href="" class="btn wishlist-btn"><i class="fa fa-heart"
                                                            aria-hidden="true"></i></a>
                                                    <a href="{{ route('cart.add', $seller->id) }}"
                                                        class="btn add-to-cart-btn"><i class="fa fa-cart-arrow-down"
                                                            aria-hidden="true"></i>add to cart
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>



        <!--Block 08: Products-->
        {{-- <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xs-12">
                    <div class="advance-product-box">
                        <div
                            class="biolife-title-box bold-style biolife-title-box__bold-style mobile-tiny sm-margin-bottom-36px">
                            <h3 class="title">Top Rated Products</h3>
                        </div>
                        <ul class="products-list vertical-layout products-list__vertical-layout">
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-01.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-02.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-03.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xs-12">
                    <div class="advance-product-box">
                        <div class="biolife-title-box bold-style biolife-title-box__bold-style mobile-tiny">
                            <h3 class="title">Featured Products</h3>
                        </div>
                        <ul class="products-list vertical-layout products-list__vertical-layout">
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-04.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-05.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-06.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xs-12 sm-margin-top-54px md-margin-top-0">
                    <div class="advance-product-box">
                        <div class="biolife-title-box bold-style biolife-title-box__bold-style mobile-tiny">
                            <h3 class="title">Bestseller Products</h3>
                        </div>
                        <ul class="products-list vertical-layout products-list__vertical-layout">
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-07.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-08.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="product-item">
                                <div class="contain-product contain-product__right-info-layout2">
                                    <div class="product-thumb">
                                        <a href="#" class="link-to-product">
                                            <img src="assets/images/home-04/pr-100-09.jpg" alt="Vegetables"
                                                width="100" height="100" class="product-thumnail">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <h4 class="product-title"><a href="#" class="pr-name">Pumpkins
                                                Fairytale</a></h4>
                                        <div class="price ">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>85.00</span></ins>
                                            <del><span class="price-amount"><span
                                                        class="currencySymbol">₹</span>95.00</span></del>
                                        </div>
                                        <div class="rating">
                                            <p class="star-rating"><span class="width-80percent"></span></p>
                                            <span class="review-count">(05 Reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

</div>
@include('ui.layout.footer')
