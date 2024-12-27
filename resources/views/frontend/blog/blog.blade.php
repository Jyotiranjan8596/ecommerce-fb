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
                <h2>{{ $viewblog->name }}</h2>
                <p class="breadcrumb">
                    <a href="{{ route('frontend.index') }}" style="color: grey">Home</a> /
                    <span><a href="" class="text--base" style="color: red">Blog Details</a></span>
                </p>
            </div>
        </div>
    </div>
</section>
<div class="container">
    @foreach ($allblog as $blog)
        <div class="row gy-5 main-content">
            <div class="col-md-4 mb-5">
                <img src="{{ asset('images/' . $blog->image) }}" alt="School Bag" style="width:404px; height: 300px;">
            </div>
            <div class="col-md-8 ">
                <h4>{{ $blog->title }}</h4>
                <hr>
                <div class="mb-3">
                    <p>{!! Str::limit($blog->description, 400, '.....') !!}</p>
                </div>
                <div>
                    <a href="{{ route('blog.readmore', $blog->id) }}">
                        <button type="submit" class="btn btn-danger">ReadMore</button>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@include('frontend.layout.footer')
