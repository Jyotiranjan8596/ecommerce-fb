@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                <h4><b>List of POS</b></h4>
            </div>
            <!-- Search form -->
            <div class="float-end">
                <form action="{{ route('user.pos.list') }}" method="GET">
                    <input type="text" name="search" placeholder="Search by City or ZIP" value="{{ request()->search }}" class="form-control">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>POS Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>ZIP</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($pos->count() > 0)
                        @foreach ($pos as $key => $data)
                        <tr>
                            <td> {{ __($pos->firstItem() + $key) }}</td> 
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->address }}</td>
                            <td>{{ $data->city }}</td>
                            <td>{{ $data->zip }}</td>                     
                            <td>{{ $data->mobilenumber }}</td>                     
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No POS found.</td>
                        </tr>
                    @endif
                </tbody>
                </table>
            </div>
        </div>  
        <div class="d-flex justify-content-center">
            {{ $pos->links() }}
        </div>  
    </div>
@endsection

