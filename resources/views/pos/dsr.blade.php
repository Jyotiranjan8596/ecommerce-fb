@extends('pos.layouts.master')

@section('content')
    <div class="container my-4">
        <h3 class="text-center text-dark mb-4"><b>DAILY SALES REPORT</b></h3>

        <!-- Top Row: File Upload, Search, and Export -->
        <div class="row">
            <!-- File Upload -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('pos.dsr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" name="file" accept=".csv">
                        <button class="btn btn-info" type="submit">UPLOAD DSR</button>
                    </div>
                </form>
            </div>

            <!-- Search -->
            <div class="col-md-4 mb-3">
                <form method="GET" action="{{ route('pos.dsr') }}">
                    <div class="input-group">
                        <input type="number" class="form-control" name="search" placeholder="Search By...">
                        <button class="btn btn-info" type="submit">SEARCH</button>
                    </div>
                </form>
            </div>

            <!-- Export -->
            <div class="col-md-4 mb-3 text-md-end">
                <form method="GET" action="{{ route('pos.dsr.export') }}">
                    <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                    <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                    <button class="btn btn-danger" type="submit">EXPORT</button>
                </form>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row justify-content-end">
            <div class="col-md-4">
                <form method="GET" action="{{ route('pos.dsr') }}">
                    <label for="start_date"><b>From:</b></label>
                    <input type="date" class="form-control mb-2" name="start_date" id="start_date">

                    <label for="end_date"><b>To:</b></label>
                    <input type="date" class="form-control mb-2" name="end_date" id="end_date">

                    <button class="btn btn-info w-100" type="submit">FILTER</button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-4">

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Sl.No</th>
                        {{-- <th>INVOICE</th> --}}
                        <th>POS ID</th>
                        <th>MOBILE</th>
                        <th>NAME</th>
                        <th>BILLING AMOUNT</th>
                        <th>TRANSACTION DATE</th>
                        {{-- <th>INSERT DATE</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($wallets->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center text-danger" style="font-size:22px;">No Data Available</td>
                        </tr>
                    @else
                        @foreach ($wallets as $key => $data)
                            <tr>
                                <td>{{ $wallets->firstItem() + $key }}</td>
                                {{-- <td>{{ $data->invoice }}</td> --}}
                                <td>{{ $data->getPos ? $data->getPos->user_id : '' }}</td>
                                <td>{{ $data->mobilenumber }}</td>
                                <td>{{ optional($data->user)->name }}</td>
                                <td>₹{{ $data->billing_amount ?? 0 }}/-</td>
                                <td>{{ date('d/m/Y', strtotime($data->transaction_date)) }}</td>
                                {{-- <td>{{ date('d-m-Y h:i A', strtotime($data->insert_date)) }}</td> --}}
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $wallets->links() }}
        </div>
    </div>
@endsection
