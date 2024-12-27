@include('frontend.layout.header')
<style>
    .py-100 {
        padding-top: 2px;
    }

    .mt-5 {
        margin-bottom: 2rem;
    }
</style>
<section class="py-100">
    <div class="container">
        <div class="d-flex justify-content-center mt-5">
            <div class="content text-center">
                <h2>{{ $viewevent->title }}</h2>
                <p class="breadcrumb">
                    <a href="{{ route('frontend.index') }}" style="color: grey">Home</a> /
                    <span><a href="" class="text--base" style="color: red">Event Details</a></span>
                </p>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <div class="row gy-5 main-content">
        <div class="col-md-4 mb-5">
            <img src="{{ asset('images/' . $viewevent->image) }}" alt="School Bag" style="width:404px; height: 300px;">
        </div>
        <div class="col-md-8 ">
            <div class="mb-3">
                <p>{!! $viewevent->description !!}</p>
            </div>          
            <div class="mb-3"><b> Start Date 
                    <i class="bi bi-arrow-right"> <b style="color: red">{{ \Carbon\Carbon::parse($viewevent->date_from)->format('jS F Y') }}</b> </i></b>
            </div>
            <div class="mb-3"> <b> End Date  
                <i class="bi bi-arrow-right"> <b style="color: red">{{ \Carbon\Carbon::parse($viewevent->date_to)->format('jS F Y') }}</b> </i></b>
            </div>
            <div class="mb-3">
                <i class="bi bi-geo-alt-fill"> <b style="color: red">{{ $viewevent->place }}</b> </i>
            </div>
        </div>
    </div>
</div>
@include('frontend.layout.footer')
