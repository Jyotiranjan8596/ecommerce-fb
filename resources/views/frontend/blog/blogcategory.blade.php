@include('frontend.layout.header')
<style>
    .py-100 {
        padding-top: 5px;
    }

    .mt-2 {
        margin-bottom: 30px;
    }

    .main-content .card {
        border: none;
        overflow: hidden;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .main-content .card img {
        transition: transform 0.3s ease, filter 0.3s ease;
        border-radius: 10px;
        width: 419px;
        height: 235px;
    }

    .main-content .card:hover img {
        transform: scale(1.05);
        filter: brightness(0.8);
    }

    .main-content .card .content {
        padding: 20px;
    }

    .main-content .card h4 {
        text-align: center;
        font-size: 1.5em;
        color: #797373;
        transition: color 0.3s ease;
    }

    .main-content .card:hover h4 {
        color: #e74c3c;
    }

    .breadcrumb a {
        color: rgb(48, 46, 46);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: #e74c3c;
    }
</style>

<section class="py-100">
    <div class="container">
        <div class="d-flex justify-content-center mt-5">
            <div class="content text-center">
                <h2>Blogs</h2>
                <p class="breadcrumb">
                    <a href="{{ route('frontend.index') }}" style="color: grey">Home</a> /
                    <span><a href="{{ route('frontend.blogcategory') }}" class="text--base"
                            style="color: red">Blogs</a></span>
                </p>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row gy-5 mt-2 main-content">
        @foreach ($allblogCategory as $blogcategory)
            <div
                class="col-lg-4 col-sm-8 d-flex flex-column align-items-center justify-content-center product-item my-3 padding-large">
                <div class="card">
                    <a href="{{ route('blog.view', $blogcategory->id) }}" style="color: black; text-decoration: none;">
                        <img src="{{ asset('images/' . $blogcategory->image) }}" alt="{{ $blogcategory->name }}">
                        <div class="content">
                            <h4>
                                {{ $blogcategory->name }}
                            </h4>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('frontend.layout.footer')
