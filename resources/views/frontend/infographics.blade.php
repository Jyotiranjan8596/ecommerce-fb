@include('frontend.layout.header')

<section class="py-100">
    <div class="container">
        <div class="d-flex justify-content-center mt-3">
            <div class="content text-center">
                <h2>Infographics</h2>
                <p class="breadcrumb">
                    <a href="{{ route('frontend.index') }}" style="color: grey">Home</a> /
                    <span><a href="" class="text--base" style="color: red">Infographics</a></span>
                </p>
            </div>
        </div>
    </div>
</section>

@include('frontend.layout.footer')