@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                POS List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.pos_system.create') }}">
                        Add Pos
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                Sl No.
                            </th>
                            <th>
                                NAME
                            </th>
                            <th>
                                POS ID
                            </th>
                            <th>
                                City
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                MobileNumber
                            </th>
                            <th>
                                Transaction %
                            </th>
                            <th>
                                Min-Max Charge
                            </th>
                            <th>
                                STATUS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pos as $key => $data)
                            <tr>
                                <td> {{ __($pos->firstItem() + $key) }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    {{ $data->user_id }}
                                    {{-- @if ($data->user && $data->user->role == 4)
                                        {{ $data->user->user_id }}
                                    @endif --}}
                                </td>
                                <td>{{ $data->city }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->mobilenumber }}</td>
                                <td>
                                    {{ number_format((float) $data->transaction_charge, 2) }}%
                                </td>
                                <td>
                                    Rs{{ number_format((float) $data->min_charge, 2) }}-Rs{{ number_format((float) $data->max_charge, 2) }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.pos_system.edit', $data->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.pos_system.download', ['id' => $data->id, 'name' => $data->name]) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $pos->links() }}
        </div>
        {{-- <div class="card-footer clearfix">
            {{ $sector->links() }}
        </div> --}}
        {{-- <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this Product?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script> --}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
