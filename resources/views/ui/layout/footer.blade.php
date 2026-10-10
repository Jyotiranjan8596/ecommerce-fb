<!-- footer section  -->
<footer class="py-5 freebazar-site-footer">
    <div class="container-lg">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-menu">
                    <img src="{{ asset('assets/images/freebazar-header-logo.png') }}" width="240" height="70"
                        alt="FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations." />
                    <div class="social-links mt-3">
                        <ul class="d-flex list-unstyled gap-2">
                            <li>
                                <a href="#" class="btn btn-outline-light">
                                    <svg width="16" height="16">
                                        <use xlink:href="#facebook"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-outline-light">
                                    <svg width="16" height="16">
                                        <use xlink:href="#twitter"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-outline-light">
                                    <svg width="16" height="16">
                                        <use xlink:href="#youtube"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/freebazaretail/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light" aria-label="Instagram">
                                    <svg width="16" height="16">
                                        <use xlink:href="#instagram"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-outline-light">
                                    <svg width="16" height="16">
                                        <use xlink:href="#amazon"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-2 col-sm-6">
                <div class="footer-menu">
                    <h5 class="widget-title">Pages</h5>
                    <ul class="menu-list list-unstyled">

                        <li class="menu-item">
                            <a href="{{ route('page.aboutus') }}" class="nav-link">About us</a>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0);" onclick="comingSoon()" class="nav-link">Mission </a>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0);" onclick="comingSoon()" class="nav-link">Vision</a>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0);" onclick="comingSoon()" class="nav-link">Concept</a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="footer-menu">
                    <h5 class="widget-title">Quick Links</h5>
                    <ul class="menu-list list-unstyled">
                        <li class="menu-item">
                            <a href="javascript:void(0);" onclick="comingSoon()" class="nav-link">Offers</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('frontend.faq') }}" class="nav-link">FAQs</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{route('frontend.termCondition')}}" class="nav-link">Terms & Conditions</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('frontend.pos.list') }}" class="nav-link">POS List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-menu">
                    <h5 class="widget-title">Customer Service</h5>
                    <p>
                        <a href="tel:9090660606">+91 9090660606</a><br>
                        <a href="mailto:freebazaretail@gmail.com">freebazaretail@gmail.com</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="footer-menu">
                    <h5 class="widget-title">Address</h5>
                    <p>
                        1625(B),Nilakantha Nagar, <br> Nayapalli, lane no 15, 751012 <br> Near Unit 8, DAV School <br>
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
            <div class="col-md-6 copyright">
                <p>©2018 - 2026 FREEBAZAR. All rights reserved.</p>
            </div>
            {{-- <div class="col-md-6 credit-link text-start text-md-end">
          <p>
            HTML Template by
            <a href="https://templatesjungle.com/">TemplatesJungle</a>
            Distributed By <a href="https://themewagon.com">ThemeWagon</a>
          </p>
        </div> --}}
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
