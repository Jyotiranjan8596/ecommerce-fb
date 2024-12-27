@include('frontend.layout.header')

<section class="dataset-section">
    <div class="header">
        <h4>Most Viewed High-value Dataset</h4>
        <div class="container">
            <div class="bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content">
                            <div id="home" class="container tab-pane active"><br>
                                <table id="table-data" class="table table-hover table-mc-light-blue table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL No</th>
                                            <th>Product Title</th>
                                            <th>Short Desc.</th>
                                            <th>Price</th>
                                            <th>View Demo</th>
                                            <th>Purchase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product as $key => $products)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $products->title }}</td>
                                                <td>{!! Str::limit($products->description, 20, '...') !!}</td>
                                                <td>₹{{ number_format($products->price, 2) }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModalCenter{{ $products->id }}"><i
                                                            class="fas fa-eye"></i></button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModalCenter{{ $products->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                                                        Product Details</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="table"
                                                                        class="table table-hover table-mc-light-blue">
                                                                        @php
                                                                            $data = json_decode($products->header);
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
                                                </td>
                                                <td>
                                                    <a href="{{ route('cart.add', $products->id) }}">
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fa-solid fa-cart-shopping"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.layout.footer')
