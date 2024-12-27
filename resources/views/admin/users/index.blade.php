@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h3><b>User List</b></h3>
                
                <div class="d-flex flex-column flex-md-row align-items-center gap-2 mt-2 mt-md-0">
                    <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data" class="mb-2 mb-md-0">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control" name="file" accept=".xlsx">
                            <button class="btn btn-info" type="submit">UPLOAD</button>
                        </div>
                    </form>

                    <form action="{{ route('admin.users.index') }}" method="get" class="d-flex">
                        @csrf
                        <input type="text" class="form-control me-2" name="search" placeholder="Search by..." value="{{ request('search') }}">
                        <button class="btn btn-info" type="submit">Search</button>
                    </form>

                    @can('permission_create')
                        <a class="btn btn-success btn-sm text-white" href="{{ route('admin.users.create') }}">Add User</a>
                    @endcan
                    <a class="btn btn-danger btn-sm text-white" href="{{ route('admin.user.export') }}">Export</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>User ID</th>
                                <th>Mobile Number</th>
                                <th>Name</th>
                                <th>Register At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $key => $data)
                                <tr data-entry-id="{{ $data->id }}">
                                    <td>{{ __($user->firstItem() + $key) }}</td>
                                    <td>{{ $data->user_id ?? '' }}</td>
                                    <td>{{ $data->mobilenumber ?? '' }}</td>
                                    <td>{{ $data->name ?? '' }}</td>
                                    <td>{{ $data->created_at->format('d/m/Y') ?? '' }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $data->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $user->links() }}
            </div>
        </div>
    </div>

    <style>
        /* Make table responsive for smaller devices */
        .table-responsive {
            overflow-x: auto;
        }

        /* Adjust layout on smaller screens */
        @media (max-width: 768px) {
            .card-header h3 {
                font-size: 1.25rem;
            }
            .card-header form,
            .card-header .btn {
                width: 100%;
                margin: 5px 0;
            }
            .card-header .input-group {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection
