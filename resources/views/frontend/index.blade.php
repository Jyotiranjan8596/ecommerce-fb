@include('frontend.layout.header')
<!-- nav bar end here -->
<!-- hero section start here -->
<section class="hero-section">
    <div class="hero-bot">
        <div class="container">
            <div class="hero-inner">
                <div class="card">
                    <div class="card-top-section">
                        <div class="search-area">
                            <input type="text" placeholder="Search Catalog/Resources" />
                            <button>Search</button>
                        </div>
                        <div class="small-btns">
                            <button class="active">Trending Data</button>
                            <button>Economy</button>
                            <button>Agriculture</button>
                            <button>Population</button>
                            <button>Market</button>
                        </div>
                    </div>
                    <div class="card-bottom-section">
                        <div class="row">
                            <div class="col-lg-4 col-md-3 col-sm-12 left-over">
                                <img src="{{ asset('frontend/img/bar-chart_8872249.png') }}" class="img-fluid"
                                    alt="" />
                                <h4>OVERVIEW</h4>
                            </div>
                            <div class="col-lg-8 col-md-9 col-sm-12 right-over">
                                <!-- row 1 for details data -->
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>620,707</h5>
                                        <a href="">Resources</a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>12,705</h5>
                                        <a href="">catalog</a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>10.26M</h5>
                                        <a href="">times downloaded</a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>620,707</h5>
                                        <a href="">categories</a>
                                    </div>
                                </div>
                                <!-- row 2 for details data -->
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>3,212</h5>
                                        <a href="">Visualzations</a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>178</h5>
                                        <a href="">sourced webservices/api<sub>s</sub></a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>34.84M</h5>
                                        <a href="">times viewed</a>
                                    </div>
                                    <div class="col-md-3 col-sm-6 single-detail">
                                        <h5>240,060</h5>
                                        <a href="">api<sub>s</sub></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero section end here -->

<!-- discover datasets by section start -->
<section class="dataset-section">
    <div class="header">
        <h4>Discover Datasets By</h4>
    </div>
    <div class="container">
        <div class="card">
            @foreach ($allSector->take(7) as $data)
                <!-- single card start here -->
                <a href="{{ route('frontend.subsector', $data->id) }}" style="text-decoration: none">
                    <div class="sicover-card">
                        <div class="discover-single-card">
                            <img src="{{ asset('images/' . $data->image) }}" alt="Economy" />
                            <h6 style="text-decoration: none;">{{ $data->title }}</h6>
                        </div>
                    </div>
                </a>
            @endforeach

            <div class="sicover-card">
                <div class="discover-single-card">
                    <a href="{{ route('frontend.sector') }}" style="text-decoration: none">
                        <h6>View More <i class="bi bi-arrow-right"></i></h6>
                    </a>

                </div>
            </div>
            <!-- single card end here -->

        </div>
    </div>
</section>
<!-- discover datasets by section end -->

<!-- engagement through data section start  -->
<section class="engagement-section">
    <div class="header">
        <h4>Enagement Through Data</h4>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-12 col-12">
                <div class="left-engage">
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Accusantium provident fugiat odio qui voluptatem praesentium
                        officia maxime necessitatibus corrupti voluptatum architecto
                        doloremque sequi iure animi voluptates molestias
                    </p>
                    <img src="{{ asset('frontend/img/enagement.jpg') }}" alt="engagement" />
                </div>
            </div>
            <div class="col-md-7 col-sm-12 col-12">
                <div class="right-engage">
                    <div class="row">
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                <h5>community</h5>
                                <ul class="custom-bullet">
                                    <li>Smartcities community</li>
                                    <li>DAta Goc Community</li>
                                </ul>
                                <button class="share-btn">
                                    {{-- <i class="bi bi-share-fill"></i> --}}
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- card end heree -->
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                <h5>Apps</h5>
                                <ul class="custom-bullet">
                                    <li>Smartcities community</li>
                                    <li>DAta Goc Community</li>
                                </ul>
                                <button class="share-btn">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- card end heree -->
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{ route('frontend.infographics') }}" style="color: black">
                                    <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                    <h5>infographics</h5>
                                    <ul class="custom-bullet">
                                        @foreach ($infoGraphics as $info)
                                            <li><a href="" style="color: black">
                                                    {{ $info->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button class="share-btn">
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <!-- card end heree -->
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{ route('frontend.blogcategory') }}" style="color: black">
                                    <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                    <h5>blogs</h5>

                                    <ul class="custom-bullet">
                                        @foreach ($allblog as $blog)
                                            <li>
                                                <a href="{{ route('blog.readmore', $blog->id) }}"
                                                    style="color: black;">
                                                    {{ $blog->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <button class="share-btn">
                                        <a href="{{ route('frontend.blogcategory') }}" style="color: black">
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <!-- card end heree -->
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{ route('highvalue.dataset', $data->id) }}" style="color: black">
                                    <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                    <h5>high value datasets</h5>
                                    <ul class="custom-bullet">
                                        @foreach ($dataset as $data)
                                            <li><a href="{{ route('highvalue.dataset', $data->id) }}"
                                                    style="color: black">
                                                    {{ $data->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button class="share-btn">
                                        <a href="{{ route('highvalue.dataset', $data->id) }}" style="color: black;">
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <!-- card end heree -->
                        <!-- card start heree -->
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{ route('frontend.eventCategory') }}"style="color: black">
                                    <img src="{{ asset('frontend/img/edu.jpg') }}" alt="" />
                                    <h5>events</h5>
                                    <ul class="custom-bullet">
                                        @foreach ($events as $event)
                                            <li>
                                                <a href="{{ route('event.readmore', $event->id) }}"
                                                    style="color: black">
                                                    {{ $event->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button class="share-btn">
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <!-- card end heree -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- engagement through data section end  -->

<!-- fourcard section start -->
<section class="four-card-section">
    <div class="container">
        <div class="row">
            <!-- card start -->
            <div class="col-md-3 col-sm-12">
                <a href="{{ route('highvalue.recentlyAdded') }}">
                    <div class="card gray">
                        <img src="{{ asset('frontend/img/management.png') }}" alt="" />
                        <h6>Recently Added Datasets</h6>
                    </div>
                </a>
            </div>
            <!-- card end -->
            <!-- card start -->
            <div class="col-md-3 col-sm-12 ">
                <a href="{{ route('highvalue.datasetview') }}">
                    <div class="card orange">
                        <img src="{{ asset('frontend/img/management.png') }}" alt="" />
                        <h6>Most Viewed Datasets</h6>
                    </div>
                </a>
            </div>
            <!-- card end -->
            <!-- card start -->
            <div class="col-md-3 col-sm-12 ">
                <a href="">
                    <div class="card green">
                        <img src="{{ asset('frontend/img/management.png') }}" alt="" />
                        <h6>Most Purchaed Dataset</h6>
                    </div>
                </a>
            </div>
            <!-- card end -->
            <!-- card start -->
            <div class="col-md-3 col-sm-12 ">
                <a href="{{ route('highvalue.dataset', $data->id) }}">
                    <div class="card blue">
                        <img src="{{ asset('frontend/img/management.png') }}" alt="" />
                        <h6>High Values Datasets</h6>
                    </div>
                </a>
            </div>
            <!-- card end -->
        </div>
    </div>
</section>
<!-- fourcard section end -->

<!-- slider section start -->

<section class="slider-section">
    <div class="header">
        <h5>STATE WISE DATA</h5>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="slider">
                <div class="slides">
                    <div class="slide">
                        <a href="page1.html">ODISHA</a>
                    </div>
                    <div class="slide">
                        <a href="page2.html">ASAM</a>
                    </div>
                    <div class="slide">
                        <a href="page3.html">BIHAR</a>
                    </div>
                    <div class="slide">
                        <a href="page4.html">KARNATKA</a>
                    </div>
                    <div class="slide">
                        <a href="page5.html">JHARKHAND</a>
                    </div>
                    <div class="slide">
                        <a href="page5.html">ASAM</a>
                    </div>
                    <div class="slide">
                        <a href="page5.html">ODISHA</a>
                    </div>
                    <div class="slide">
                        <a href="page5.html">KERLA</a>
                    </div>
                </div>
                <div class="controls">
                    <button class="control" id="prev">&lt;</button>
                    <button class="control" id="next">&gt;</button>
                </div>
            </div>
        </div>
    </div>
</section>

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
</body>

</html>
