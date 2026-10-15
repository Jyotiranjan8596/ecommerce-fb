<section>
    <footer class="freebazar-site-footer">
        <div class="container-lg">
            <div class="row align-items-start">
                <div class="col-12 col-sm-6 col-xl-3 order-xl-1">
                    <div class="footer-menu">
                        <a href="{{ route('frontend.index') }}" aria-label="FreeBazar home">
                            <img src="{{ asset('assets/images/freebazar-header-logo.png') }}" width="240" height="70"
                                alt="FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations." />
                        </a>
                        @include('partials.social-links')
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 order-xl-3">
                    <div class="footer-menu">
                        <h5 class="widget-title">Quick Links</h5>
                        <ul class="menu-list list-unstyled">
                            <li><a href="{{ route('frontend.offer') }}">Offers</a></li>
                            <li><a href="{{ route('frontend.faq') }}">FAQs</a></li>
                            <li><a href="{{ route('frontend.termCondition') }}">Terms &amp; Conditions</a></li>
                            <li><a href="{{ route('frontend.pos.list') }}">POS List</a></li>
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
</section>

<script>
    const slides = document.querySelector('.slides');
    const slide = document.querySelectorAll('.slide');
    const next = document.getElementById('next');
    const prev = document.getElementById('prev');
    let currentIndex = 0;

    function updateSlidePosition() {
        slides.style.transform = `translateX(-${currentIndex * (100 / (window.innerWidth <= 768 ? 2 : 5))}%)`;
    }

    next.addEventListener('click', () => {
        if (currentIndex < slide.length - (window.innerWidth <= 768 ? 2 : 5)) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateSlidePosition();
    });

    prev.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = slide.length - (window.innerWidth <= 768 ? 2 : 5);
        }
        updateSlidePosition();
    });

    window.addEventListener('resize', updateSlidePosition);
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
