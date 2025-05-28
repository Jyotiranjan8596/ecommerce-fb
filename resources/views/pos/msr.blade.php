@extends('pos.layouts.master')

@section('content')
    <div class="container">
        <h5 class="text-center text-dark font-weight-bold"><b>MONTHLY SALES REPORT</b></h5>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('pos.msr') }}" class="row align-items-center mb-3">


            {{-- Month Filter --}}
            <div class="col-12 col-md-4 mb-2">
                <input type="month" name="month" id="month" class="form-control" value="{{ request('month') }}"
                    required>
            </div>

            {{-- Filter Button --}}
            <div class="col-12 col-md-auto mb-2">
                <button class="btn btn-secondary btn-block" type="submit">FILTER</button>
            </div>
        </form>

        <!-- Export Form -->
        <div class="col-md-12 mb-3 text-end">
            <form method="GET" action="{{ route('pos.msr.export') }}" class="d-inline">
                <input type="hidden" name="month" value="{{ request('month') }}">
                <button class="btn btn-danger" type="submit">EXPORT</button>
            </form>
        </div>


        {{-- Sales Report Table --}}
        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        {{-- <th>MONTH</th> --}}
                        <th>Transaction Date</th>
                        <th>Billing Amount</th>
                        <th>Total Transaction</th>
                        <th>CREDIT</th>
                        <th>DEBIT</th>
                        <th>STATUS</th>
                        {{-- <th>SPONSOR EXPENDITURE</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($monthlySales->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-danger font-weight-bold">No Data Available</td>
                        </tr>
                    @else
                        @foreach ($monthlySales as $key => $data)
                            <tr>
                                <td>{{ $monthlySales->firstItem() + $key }}</td>
                                {{-- <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->transaction_month)->format('F Y') }}
                                </td> --}}
                                <td>{{ $data->dates }}</td>
                                <td>₹{{ $data->total_billing_amount  }}</td>
                                <td>₹{{ $data->total_transactions }}</td>
                                <td>₹{{ $data->credit }}</td>
                                <td>₹{{ $data->debit }}</td>
                                <td>{{ $data->status }}</td>
                                {{-- <td>₹{{ $data->sponsor_expenditure }}/-</td> --}}
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $monthlySales->links() }}
        </div>
    </div>

@endsection
