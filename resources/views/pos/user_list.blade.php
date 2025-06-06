@extends('pos.layouts.master')
<!-- Add this in your main layout blade file, like layouts/app.blade.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-dNmIQjY1j5UrU4Gm7QNz+6EmcHivd6i0VdCw8pOfAGg6pY9HJg8uySOIWlKb7Gp3MeR9RmRbS/m4K0lwRXX7Wg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

@section('content')
    <div class="">
        <h4><b>User List</b></h4>
        <hr>
    </div>
    <form action="{{ route('pos.user.list') }}" method="GET">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label for="search_by">Search By*</label>
                    <select name="search_by" id="search_by" class="form-control">
                        <option value="">Select the Option</option>
                        <option value="user_id">USER ID</option>
                        <option value="name">Name</option>
                        {{-- <option value="email">Email</option> --}}
                        <option value="mobilenumber">Phone</option>
                    </select>
                </div>
                <div class="col-md-4 mb-4">
                    <label for="search_term">Search Term*</label>
                    <input type="text" id="search_term" name="search_term" class="form-control"
                        placeholder="Enter search term">
                </div>
                <div class="col-md-4 mb-4">
                    <button class="btn btn-primary me-2" type="submit">FIND USER</button>
                    <a class="btn btn-secondary" href="{{ route('pos.index') }}">Back to list</a>
                </div>
            </div>
        </div>

        {{-- <div class="card-footer text-center mb-4">
            <button class="btn btn-primary me-2" type="submit">FIND USER</button>
            <a class="btn btn-secondary" href="{{ route('pos.index') }}">Back to list</a>
        </div> --}}
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
                                {{-- <a href="{{ route('pos.wallet.manage') }}?user_id={{ $user->user_id }}">
                                    <i class="fas fa-eye"></i></a> --}}
                                <a href="{{ route('pos.wallet.manage') }}?user_id={{ $user->user_id }}"><i
                                        class="fas fa-eye"></i></a>

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
