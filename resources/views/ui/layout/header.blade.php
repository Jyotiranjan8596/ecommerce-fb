   <!-- HEADER -->
   <header id="header" class="header-area style-01 layout-04">
       <div class="header-middle biolife-sticky-object ">
           <div class="container">
               <div class="row">
                   <div class="col-lg-3 col-md-2 col-md-6 col-xs-6">
                       <a href="{{ route('frontend.index') }}" class="biolife-logo"><b
                               style="font-size: 40px;color:orange;">FREE BAZAR</b></a>
                   </div>
                   <div class="col-lg-6 col-md-7 hidden-sm hidden-xs">
                       <div class="primary-menu">
                           <ul class="menu biolife-menu clone-main-menu clone-primary-menu" id="primary-menu"
                               data-menuname="main menu">
                               <li class="menu-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                               @php
                                   $products = DB::table('products')
                                       ->select('id', 'title')
                                       ->latest('id')
                                       ->take(10)
                                       ->get();
                               @endphp
                               <li class="menu-item menu-item-has-children has-child">
                                   <a href="" class="menu-name" data-title="Product">Product</a>
                                   <ul class="sub-menu">
                                       @foreach ($products as $product)
                                           <li class="menu-item"><a
                                                   href="{{ route('frontend.product', $product->id) }}">{{ $product->title }}</a>
                                           </li>
                                       @endforeach
                                   </ul>
                               </li>
                               <li>
                                   @auth
                                       @if (Auth::user()->role == 3)
                                           <a href="{{ route('user.index') }}" style="margin-right: 25px;">Dashboard</a>
                                           <a href="{{ route('logout') }}" style="display: inline;"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                           <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                               class="d-none">
                                               @csrf
                                           </form>
                                       @else
                                           <a class="nav-link" href="{{ route('login') }}">Login /</a>
                                           <a class="nav-link" href="{{ route('register') }}"> Register </a>
                                       @endif
                                   @else
                                       <a class="nav-link" href="{{ route('login') }}">Login /</a>
                                       <a class="nav-link" href="{{ route('register') }}"> Register </a>
                                   @endauth
                               </li>
                               {{-- <li class="menu-item"><a href="contact.html">Contact</a></li> --}}
                           </ul>
                       </div>
                   </div>
                   <div class="col-lg-3 col-md-3 col-md-6 col-xs-6">
                       <div class="biolife-cart-info">
                           {{-- <div class="mobile-search">
                               <a href="javascript:void(0)" class="open-searchbox"><i
                                       class="biolife-icon icon-search"></i></a>
                               <div class="mobile-search-content">
                                   <form action="#" class="form-search" name="mobile-seacrh" method="get">
                                       <a href="#" class="btn-close"><span
                                               class="biolife-icon icon-close-menu"></span></a>
                                       <input type="text" name="s" class="input-text" value=""
                                           placeholder="Search here...">
                                       <select name="category">
                                           <option value="-1" selected>All Categories</option>
                                           <option value="vegetables">Vegetables</option>
                                           <option value="fresh_berries">Fresh Berries</option>
                                           <option value="ocean_foods">Ocean Foods</option>
                                           <option value="butter_eggs">Butter & Eggs</option>
                                           <option value="fastfood">Fastfood</option>
                                           <option value="fresh_meat">Fresh Meat</option>
                                           <option value="fresh_onion">Fresh Onion</option>
                                           <option value="papaya_crisps">Papaya & Crisps</option>
                                           <option value="oatmeal">Oatmeal</option>
                                       </select>
                                       <button type="submit" class="btn-submit">go</button>
                                   </form>
                               </div>
                           </div> --}}
                           {{-- <div class="wishlist-block hidden-sm hidden-xs">
                               <a href="#" class="link-to">
                                   <span class="icon-qty-combine">
                                       <i class="icon-heart-bold biolife-icon"></i>
                                       <span class="qty">4</span>
                                   </span>
                               </a>
                           </div> --}}
                           <div class="minicart-block">
                               <div class="minicart-contain">
                                   <a href="{{ route('frontend.cart') }}" class="link-to">
                                       @php
                                           $cart = App\Models\Cart::where('user_id', Auth::id())->get();
                                       @endphp
                                       <span class="icon-qty-combine">
                                           <i class="icon-cart-mini biolife-icon"></i>
                                           <span class="qty">{{ count($cart) }}</span>
                                       </span>
                                   </a>
                                   {{-- <div class="cart-content">
                                       <div class="cart-inner">
                                           <ul class="products">
                                               <li>
                                                   <div class="minicart-item">
                                                       <div class="thumb">
                                                           <a href="#"><img
                                                                   src="{{ asset('assets/images/minicart/pr-01.jpg') }}"
                                                                   width="90" height="90"
                                                                   alt="National Fresh"></a>
                                                       </div>
                                                       <div class="left-info">
                                                           <div class="product-title"><a href="#"
                                                                   class="product-name">National Fresh Fruit</a></div>
                                                           <div class="price">
                                                               <ins><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>85.00</span></ins>
                                                               <del><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>95.00</span></del>
                                                           </div>
                                                           <div class="qty">
                                                               <label for="cart[id123][qty]">Qty:</label>
                                                               <input type="number" class="input-qty"
                                                                   name="cart[id123][qty]" id="cart[id123][qty]"
                                                                   value="1" disabled>
                                                           </div>
                                                       </div>
                                                       <div class="action">
                                                           <a href="#" class="edit"><i class="fa fa-pencil"
                                                                   aria-hidden="true"></i></a>
                                                           <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                   aria-hidden="true"></i></a>
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="minicart-item">
                                                       <div class="thumb">
                                                           <a href="#"><img
                                                                   src="{{ asset('assets/images/minicart/pr-02.jpg') }}"
                                                                   width="90" height="90"
                                                                   alt="National Fresh"></a>
                                                       </div>
                                                       <div class="left-info">
                                                           <div class="product-title"><a href="#"
                                                                   class="product-name">National Fresh Fruit</a></div>
                                                           <div class="price">
                                                               <ins><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>85.00</span></ins>
                                                               <del><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>95.00</span></del>
                                                           </div>
                                                           <div class="qty">
                                                               <label for="cart[id124][qty]">Qty:</label>
                                                               <input type="number" class="input-qty"
                                                                   name="cart[id124][qty]" id="cart[id124][qty]"
                                                                   value="1" disabled>
                                                           </div>
                                                       </div>
                                                       <div class="action">
                                                           <a href="#" class="edit"><i class="fa fa-pencil"
                                                                   aria-hidden="true"></i></a>
                                                           <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                   aria-hidden="true"></i></a>
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="minicart-item">
                                                       <div class="thumb">
                                                           <a href="#"><img
                                                                   src="{{ asset('assets/images/minicart/pr-03.jpg') }}"
                                                                   width="90" height="90"
                                                                   alt="National Fresh"></a>
                                                       </div>
                                                       <div class="left-info">
                                                           <div class="product-title"><a href="#"
                                                                   class="product-name">National Fresh Fruit</a></div>
                                                           <div class="price">
                                                               <ins><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>85.00</span></ins>
                                                               <del><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>95.00</span></del>
                                                           </div>
                                                           <div class="qty">
                                                               <label for="cart[id125][qty]">Qty:</label>
                                                               <input type="number" class="input-qty"
                                                                   name="cart[id125][qty]" id="cart[id125][qty]"
                                                                   value="1" disabled>
                                                           </div>
                                                       </div>
                                                       <div class="action">
                                                           <a href="#" class="edit"><i class="fa fa-pencil"
                                                                   aria-hidden="true"></i></a>
                                                           <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                   aria-hidden="true"></i></a>
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="minicart-item">
                                                       <div class="thumb">
                                                           <a href="#"><img
                                                                   src="{{ asset('assets/images/minicart/pr-04.jpg') }}"
                                                                   width="90" height="90"
                                                                   alt="National Fresh"></a>
                                                       </div>
                                                       <div class="left-info">
                                                           <div class="product-title"><a href="#"
                                                                   class="product-name">National Fresh Fruit</a></div>
                                                           <div class="price">
                                                               <ins><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>85.00</span></ins>
                                                               <del><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>95.00</span></del>
                                                           </div>
                                                           <div class="qty">
                                                               <label for="cart[id126][qty]">Qty:</label>
                                                               <input type="number" class="input-qty"
                                                                   name="cart[id126][qty]" id="cart[id126][qty]"
                                                                   value="1" disabled>
                                                           </div>
                                                       </div>
                                                       <div class="action">
                                                           <a href="#" class="edit"><i class="fa fa-pencil"
                                                                   aria-hidden="true"></i></a>
                                                           <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                   aria-hidden="true"></i></a>
                                                       </div>
                                                   </div>
                                               </li>
                                               <li>
                                                   <div class="minicart-item">
                                                       <div class="thumb">
                                                           <a href="#"><img
                                                                   src="{{ asset('assets/images/minicart/pr-05.jpg') }}"
                                                                   width="90" height="90"
                                                                   alt="National Fresh"></a>
                                                       </div>
                                                       <div class="left-info">
                                                           <div class="product-title"><a href="#"
                                                                   class="product-name">National Fresh Fruit</a></div>
                                                           <div class="price">
                                                               <ins><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>85.00</span></ins>
                                                               <del><span class="price-amount"><span
                                                                           class="currencySymbol">£</span>95.00</span></del>
                                                           </div>
                                                           <div class="qty">
                                                               <label for="cart[id127][qty]">Qty:</label>
                                                               <input type="number" class="input-qty"
                                                                   name="cart[id127][qty]" id="cart[id127][qty]"
                                                                   value="1" disabled>
                                                           </div>
                                                       </div>
                                                       <div class="action">
                                                           <a href="#" class="edit"><i class="fa fa-pencil"
                                                                   aria-hidden="true"></i></a>
                                                           <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                   aria-hidden="true"></i></a>
                                                       </div>
                                                   </div>
                                               </li>
                                           </ul>
                                           <p class="btn-control">
                                               <a href="#" class="btn view-cart">view cart</a>
                                               <a href="#" class="btn">checkout</a>
                                           </p>
                                       </div>
                                   </div> --}}
                               </div>
                           </div>
                           <div class="mobile-menu-toggle">
                               <a class="btn-toggle" data-object="open-mobile-menu" href="javascript:void(0)">
                                   <span></span>
                                   <span></span>
                                   <span></span>
                               </a>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </header>
