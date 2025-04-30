@extends('ui.layout.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">🔥 Today's Offers on FreeBazar</h2>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <!-- Product Card 1 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 1">
                <div class="card-body">
                    <h5 class="card-title">Wireless Earbuds</h5>
                    <p class="card-text text-success fw-bold">₹1,299</p>
                </div>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 2">
                <div class="card-body">
                    <h5 class="card-title">Smart Watch</h5>
                    <p class="card-text text-success fw-bold">₹2,499</p>
                </div>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product 3">
                <div class="card-body">
                    <h5 class="card-title">Bluetooth Speaker</h5>
                    <p class="card-text text-success fw-bold">₹999</p>
                </div>
            </div>
        </div>
        
        <!-- Add more cards as needed -->
    </div>
</div>
@endsection
