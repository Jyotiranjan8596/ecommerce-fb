@extends('layouts.master')

@section('content')
<div class="container py-1">
    
    <!-- Offer Form Card -->
    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Offer</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="productTitle" class="form-label">Product Title</label>
                    <input type="text" class="form-control" id="productTitle" placeholder="Enter product name">
                </div>

                <div class="mb-3">
                    <label for="productPrice" class="form-label">Price (₹)</label>
                    <input type="number" class="form-control" id="productPrice" placeholder="Enter price">
                </div>

                <div class="mb-3">
                    <label for="productImage" class="form-label">Product Image</label>
                    <input class="form-control" type="file" id="productImage">
                </div>

                <button type="submit" class="btn btn-success">Add Offer</button>
            </form>
        </div>
    </div>

    <!-- Offer List Table -->
    <div class="card shadow">
        <div class="card-header bg-dark text-dark">
            <h5 class="mb-0">All Offer Products</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Image</th>
                            <th>Title</th>
                            <th>Price (₹)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Row 1 -->
                        <tr>
                            <td>1</td>
                            <td><img src="https://via.placeholder.com/80x60" alt="Product" class="img-thumbnail" style="max-width: 80px;"></td>
                            <td>Wireless Earbuds</td>
                            <td>₹1,299</td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>

                        <!-- Sample Row 2 -->
                        <tr>
                            <td>2</td>
                            <td><img src="https://via.placeholder.com/80x60" alt="Product" class="img-thumbnail" style="max-width: 80px;"></td>
                            <td>Smart Watch</td>
                            <td>₹2,499</td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>

                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
