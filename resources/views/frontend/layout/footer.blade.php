<section>
    <footer>
        <div class="container">
            <div class="foot-inner">
                <ul>
                    <li><a href="">Terms of service</a></li>
                    <li><a href="" class="border-left">Privacy Policy</a></li>
                    <li><a href="" class="border-left">Refund Policy</a></li>
                    <li><a href="" class="border-left">Contact</a></li>
                </ul>
            </div>
            <p class="copy-right-line">
                &copy; Copyright SRDC Pvt Ltd. All rights reserved.
            </p>
        </div>
    </footer>
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
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
