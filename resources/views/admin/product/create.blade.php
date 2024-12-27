@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <h4 class="card-header"><b> Create Product</b></h4>
        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="mb-2">
                    <label for="sector">Category*</label>
                    <select name="sector_id" id="sector" class="form-control @error('sector_id') is-invalid @enderror">
                        <option value="">Select a Category</option>
                        @foreach ($sector as $data)
                            <option value="{{ $data->id }}">{{ $data->title }}</option>
                        @endforeach
                    </select>
                    @error('sector_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div class="mb-2">
                    <label for="subsector">SubCategory*</label>
                    <select name="subsector_id" id="getSubsector"
                        class="form-control @error('subsector_id') is-invalid @enderror">
                        <option value="">Select a SubCategory</option>
                    </select>
                    @error('subsector_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}

                <div class="mb-2">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="title"
                        class="form-control @error('title') is-invalid @enderror">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div class="mb-2">
                    <label for="title">Meta Tag*</label>
                    <input type="text" id="meta_tag" name="meta_tag"
                        class="form-control @error('meta_tag') is-invalid @enderror">
                    @error('meta_tag')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}
                <div class="mb-2">
                    <label for="title">Short Description*</label>
                    <textarea name="description" id="Editor" cols="30" rows="3"
                        class="form-control @error('description') is-invalid @enderror"></textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="status">Is BestSeller Product ?*</label>
                    <div class="form-check">
                        <input class="form-check-input @error('bestseller') is-invalid @enderror" type="radio"
                            name="bestseller" id="bestsellerYes" value="1">
                        <label class="form-check-label" for="bestsellerYes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('bestseller') is-invalid @enderror" type="radio"
                            name="bestseller" id="bestsellerNo" value="0">
                        <label class="form-check-label" for="bestsellerNo">
                            No
                        </label>
                    </div>
                    @error('bestseller')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image"
                        class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- <div class="mb-2">
                    <label for="title">Product File(Excel)*</label>
                    <input type="file" id="product_file" name="product_file"
                        class="form-control @error('product_file') is-invalid @enderror" accept=".xlsx,.xls">
                    @error('product_file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}
                <div class="mb-2">
                    <label for="price">Price*</label>
                    <input type="number" id="price" step="0.01" name="price"
                        class="form-control @error('price') is-invalid @enderror" oninput="calculateTotalPrice()">
                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="discount_price">Discount Price*</label>
                    <input type="number" id="discount_price" step="0.01" name="discount_price"
                        class="form-control @error('discount_price') is-invalid @enderror" oninput="calculateTotalPrice()">
                    @error('discount_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="total_price">Total Price*</label>
                    <input type="number" id="total_price" step="0.01" name="total_price"
                        class="form-control @error('total_price') is-invalid @enderror" readonly>
                    @error('total_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Submit</button>
                <a class="btn btn-secondary" href="{{ route('admin.product.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('Editor');
    </script>
    <script>
        function calculateTotalPrice() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const discountPrice = parseFloat(document.getElementById('discount_price').value) || 0;
            const totalPrice = price - discountPrice;
            document.getElementById('total_price').value = totalPrice.toFixed(2); 
        }
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).on('change', '#sector', function() {
            var sector_id = $(this).val();
            $.ajax({
                type: "POST",
                url: '{{ route('admin.getSubsector') }}',
                data: {
                    sector_id: sector_id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    $('#getSubsector').empty();
                    $('#getSubsector').html('<option value="">Select</option>');
                    $.each(res, function(key, value) {
                        $('#getSubsector').append('<option value="' + value.id +
                            '">' + value.title + '</option>');
                    });
                },

            });
        });
    });
</script>
