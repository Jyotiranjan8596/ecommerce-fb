@include('frontend.layout.header')



<section class="py-100">
    <div class="container">
        <div class="d-flex justify-content-center mt-3">
            <div class="content text-center">
                <h2>Sector</h2>
                <p class="breadcrumb">
                    <a href="{{ route('frontend.index') }}" style="color: grey">Home</a> /
                    <span><a href="" class="text--base" style="color: red">Sector</a></span>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- discover datasets by section start -->
<section class="dataset-section">
    <div class="header">
        <h4>Discover Datasets By</h4>
    </div>
    <div class="container">
        <div class="card">

            @foreach ($allSector as $data)
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

        </div>
    </div>
</section>
<!-- discover datasets by section end -->


@include('frontend.layout.footer')
