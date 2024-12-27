@extends('pos.layouts.master')
<style>
    .badge-custom {
        display: inline-block;
        padding: 10px;
        background-color: #33adb1;
        color: #fff;
        border-radius: 25px;
        font-size: 24px;
        line-height: 1;
        text-align: center;
    }

    .badge-danger {
        display: inline-block;
        padding: 10px;
        background-color: #f09662;
        color: #fff;
        border-radius: 25px;
        font-size: 24px;
        line-height: 1;
        text-align: center;
    }

    .badge-success {
        display: inline-block;
        padding: 10px;
        background-color: #f381d1;
        color: #fff;
        border-radius: 25px;
        font-size: 24px;
        line-height: 1;
        text-align: center;
    }
</style>
@section('content')
    <div style="margin-top: 20px;">
        <h4><b>Journal List</b></h4>
        <hr>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="p-20">
                    <form action="{{ route('pos.journal') }}" method="get">
                        <div class="form-group row">
                            <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                                <label class="col-form-label">From Date</label>
                                <input class="form-control" type="date" name="start_date" required
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                                <label class="col-form-label">To Date</label>
                                <input class="form-control" type="date" name="end_date" required
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-12 col-sm-4">
                                <input class="form-control btn btn-primary" style="margin-top:35px;" type="submit"
                                    value="Search">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (!$notransactions)
            <div class="row mt-2">
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card card-box shadow p-4">
                        <i class="fa fa-money float-right text-muted"></i>
                        <h6 class="text-muted text-uppercase">Opening Balance</h6>
                        <h2 class="mb-3"><i class="fa fa-inr"></i> 0 </h2>
                        <span class="text-muted" style="font-size: 20px;">Closing Balance</span>
                        <h2 class="badge-custom"> <i class="fa fa-inr"></i> 0 </h2>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-4">
                    <div class="card card-box shadow p-4">
                        <i class="fa fa-money float-right text-muted"></i>
                        <h6 class="text-muted text-uppercase">Transaction Charge</h6>
                        <h2 class="mb-3"><i class="fa fa-inr"></i> 0 </h2>
                        <span class="text-muted" style="font-size: 20px;">Paid By FB Card</span>
                        <h2 class="badge-danger"> <i class="fa fa-inr"> 0 </i></h2>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-4">
                    <div class="card card-box shadow p-4">
                        <i class="fa fa-money float-right text-muted"></i>
                        <h6 class="text-muted text-uppercase">Paid to Free Bazar</h6>
                        <h2 class="mb-3"><i class="fa fa-inr"></i> 0 </h2>
                        <span class="text-muted" style="font-size: 20px;">Received From Freebazar</span>
                        <h2 class="badge-success"> <i class="fa fa-inr"></i> 0 </h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-center">Transaction Details</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="transactions-table" class="table table-hover table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>   
                                        <th>Invoice</th>
                                        <th>Billing Amount</th>
                                        {{-- <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Closing Balance</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($transactions as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->transaction_date)->format('d-m-Y') }}</td>                                           
                                            <td>{{ $data->invoice ?? 'N/A' }}</td>
                                            <td>₹{{ isset($data->billing_amount) ? number_format($data->billing_amount, 2) : 'N/A' }}/-</td>
                                            {{-- <td></td>
                                            <td></td>
                                            <td></td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-4">
                    <div class="alert alert-danger text-center" role="alert">
                        No Record found.
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
