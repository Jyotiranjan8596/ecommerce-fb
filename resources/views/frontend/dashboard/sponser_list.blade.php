@extends('frontend.dashboard.layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                <h4><b>Sponcer List</b></h4>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Name</th>
                            <th>Picture</th>
                            <th>Mobile Number</th>
                            <th>Sponcer date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponcer as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($data->user)
                                        {{ $data->user->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->user)
                                        @if ($data->user->image)
                                            <img src="{{ asset('images/' . $data->user->image) }}" alt="User Image"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($data->user)
                                        {{ $data->user->mobilenumber }}
                                    @endif
                                </td>
                                <td>{{ $data->created_on }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="card-footer clearfix">
        {{ $users->links() }}
    </div> --}}
    </div>
@endsection
