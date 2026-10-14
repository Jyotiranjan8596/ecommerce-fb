<!-- footer section  -->
<footer class="freebazar-site-footer">
    <div class="container-lg">
        <div class="row align-items-start">
            <div class="col-12 col-sm-6 col-xl-3 order-xl-1">
                <div class="footer-menu">
                    <img src="{{ asset('assets/images/freebazar-header-logo.png') }}" width="240" height="70"
                        alt="FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations." />
                    @include('partials.social-links')
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 order-xl-3">
                <div class="footer-menu">
                    <h5 class="widget-title">Quick Links</h5>
                    <ul class="menu-list list-unstyled">
                        <li class="menu-item">
                            <a href="{{ route('frontend.offer') }}">Offers</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('frontend.faq') }}">FAQs</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('frontend.termCondition') }}">Terms &amp; Conditions</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('frontend.pos.list') }}">POS List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 order-xl-2">
                <div class="footer-menu">
                    <h5 class="widget-title">Customer Service</h5>
                    <p>
                        <a href="tel:9090660606">+91 9090660606</a><br>
                        <a href="mailto:freebazaretail@gmail.com">freebazaretail@gmail.com</a>
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 order-xl-4">
                <div class="footer-menu">
                    <h5 class="widget-title">Address</h5>
                    <p>
                        1625(B), Nilakantha Nagar,<br>
                        Nayapalli, lane no 15, 751012<br>
                        Near Unit 8, DAV School<br>
                        Bhubaneswar, Odisha
                    </p>

                </div>
            </div>
        </div>
    </div>
</footer>
<div id="footer-bottom">
    <div class="container-lg">
        <div class="row">
            <div class="col-12 copyright">
                <p>©2018 - 2026 FREEBAZAR. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
