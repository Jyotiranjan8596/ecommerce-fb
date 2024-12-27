@extends('pos.layouts.master')


@section('content')
    <div class="mt-4">
        <h4><b>User List</b></h4>
        <hr>
    </div>
    <form action="{{ route('pos.user.list') }}" method="GET">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="search_by">Search By*</label>
                    <select name="search_by" id="search_by" class="form-control">
                        <option value="">Select the Option</option>
                        <option value="user_id">USER ID</option>
                        <option value="name">Name</option>
                        {{-- <option value="email">Email</option> --}}
                        <option value="mobilenumber">Phone</option>
                    </select>
                </div>
                <div class="col-md-6 mb-4">
                    <label for="search_term">Search Term*</label>
                    <input type="text" id="search_term" name="search_term" class="form-control"
                        placeholder="Enter search term">
                </div>
            </div>
        </div>

        <div class="card-footer text-center mb-4">
            <button class="btn btn-primary me-2" type="submit">FIND USER</button>
            <a class="btn btn-secondary" href="{{ route('pos.index') }}">Back to list</a>
        </div>
    </form>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobilenumber }}</td>
                            <td>
                                <a href="{{ route('pos.wallet.manage', $user->id) }}"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection
