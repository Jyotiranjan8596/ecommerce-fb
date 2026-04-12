@extends('layouts.master')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h3 class="text-center"><b style="color: rgb(8, 7, 20)">WALLET DETAILS</b></h3>

        <div class="row g-3 mb-3">

                {{-- File Upload --}}
                <div class="col-12 col-md-4">
                    <form action="{{ route('admin.wallet.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="form-label small text-muted mb-1">Upload Wallet</label>
                        <div class="input-group">
                            <input type="file" class="form-control form-control-sm" name="file" accept=".xlsx" required>
                            <button class="btn btn-info btn-sm fw-semibold" type="submit">
                                <i class="bi bi-upload me-1"></i> UPLOAD
                            </button>
                        </div>
                        @if ($errors->has('file'))
                            <div class="text-danger mt-1 small">{{ $errors->first('file') }}</div>
                        @endif
                    </form>
                </div>

                {{-- Filters --}}
                <div class="col-12 col-md-6">
                    <form method="POST" action="{{ route('admin.wallet') }}">
                        @csrf
                        <label class="form-label small text-muted mb-1">Filter Records</label>
                        <div class="row g-2">

                            <div class="col-6 col-sm-4 col-md-4">
                                <select name="month" id="month" class="form-select form-select-sm w-100">
                                    <option value="">Month</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-sm-3 col-md-3">
                                <select name="year" id="year" class="form-select form-select-sm w-100">
                                    <option value="">Year</option>
                                    @foreach(range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-sm-3 col-md-3">
                                <select name="type" id="trans_type" class="form-select form-select-sm w-100">
                                    <option value="all">All Types</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                            </div>

                            <div class="col-6 col-sm-2 col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold flex-fill">
                                    <i class="bi bi-funnel me-1"></i> FILTER
                                </button>
                            </div>

                            {{-- <div class="col-12 col-sm-auto">
                                <a href="" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-x-circle me-1"></i> RESET
                                </a>
                            </div> --}}

                        </div>
                    </form>
                </div>

                {{-- Export --}}
                <div class="col-12 col-md-2 d-flex align-items-end justify-content-md-end">
                    <form method="GET" action="{{ route('admin.wallet.export') }}" class="w-100">
                        @csrf
                        <button class="btn btn-danger btn-sm fw-semibold w-100" type="submit">
                            <i class="bi bi-download me-1"></i> EXPORT
                        </button>
                    </form>
                </div>

            </div>
        <hr class="my-4">

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Month</th>
                        <th>USER ID</th>
                        <th>USER NAME</th>
                        <th>MOBILE NUMBER</th>
                        <th>PAYMENT MODE</th>
                        <th>WALLET AMOUNT</th>
                        <th>REWARD POINTS</th>

                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($walletBalance as $key => $data)
                        <tr>
                            <td>{{ $walletBalance->firstItem() + $key }}</td>
                            <td>{{ $data->month }}</td>
                            <td>{{ $data->user_data?->user_id }}</td>
                            <td>{{ $data->user_data?->name }}</td>
                            <td>{{ $data->mobilenumber }}</td>
                            <td>{{ $data->trans_type }}</td>
                            <td>₹{{ $data->rounded_wallet_amount }}/-</td>
                            <td>₹{{ $data->rounded_reward_point }}/-</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $walletBalance->links() }}
        </div>
    </div>
@endsection
