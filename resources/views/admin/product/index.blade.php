@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h3 class="mb-3 mb-md-0">
                    <b>Product List</b>
                </h3>
                @can('permission_create')
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <a class="btn btn-success btn-sm text-white" href="{{ route('admin.product.create') }}">
                            Add Product
                        </a>
                        <a class="btn btn-danger btn-sm text-white" href="{{ route('admin.product.export') }}">
                            Export
                        </a>
                        <!-- Import Form -->
                        <form action="{{ route('admin.product.import') }}" method="POST" enctype="multipart/form-data"
                            class="d-flex align-items-center">
                            @csrf
                            <input type="file" name="file" class="form-control form-control-sm me-2" style="width: auto;">
                            <button type="submit" class="btn btn-primary btn-sm">Import</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                Sl No.
                            </th>
                            <th>
                                Category
                            </th>
                            {{-- <th>
                                SubCategory
                            </th> --}}
                            <th>
                                Title
                            </th>

                            <th>
                                Description
                            </th>
                            <th>
                                Best Seller
                            </th>
                            <th>
                                Image
                            </th>

                            {{-- <th>
                                Product File
                            </th> --}}
                            <th>
                                Price (Rs.)
                            </th>
                            <th>
                                Discount Price (Rs.)
                            </th>
                            <th>
                                Total Price (Rs.)
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>

                                <td>
                                    @if ($product->sector)
                                        {{ $product->sector->title }}
                                    @endif
                                </td>
                                {{-- <td>
                                    @if ($product->subsector)
                                        {{ $product->subsector->title }}
                                    @endif
                                </td> --}}

                                <td>
                                    {{ $product->title }}
                                </td>
                                <td>
                                    {!! Str::limit($product->description, 20, '...') !!}
                                </td>
                                <td>
                                    {{ $product->bestseller == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    <img src="{{ asset('images/' . $product->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>

                                {{-- <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModalCenter{{ $product->id }}"><i
                                            class="fas fa-eye"></i></button>
    
                                    <div class="modal fade" id="exampleModalCenter{{ $product->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Product Table</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <table id="table" class="table table-hover table-mc-light-blue">
                                                        @php
                                                            $data = json_decode($product->header);
                                                            if ($data != []) {
                                                                $header = $data[0];
                                                            }
                                                            $data = array_slice($data, 1);
                                                        @endphp
                                                        <thead>
                                                            <tr>
                                                                @foreach ($header as $thead)
                                                                    <th>{{ $thead }}</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data as $tdata)
                                                                <tr>
                                                                    @foreach ($tdata as $tdataa)
                                                                        <td>{{ $tdataa }}</td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td> --}}
                                <td>
                                    ₹{{ number_format($product->price, 2) }}
                                </td>
                                <td>₹{{ number_format($product->discount_price, 2) }}</td>
                                <td>₹{{ number_format($product->total_price, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.product.edit', $product->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                                        style="display:inline-block;" id="delete-form-{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $product->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this Product?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
