@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="float-start">
                <b> Banner List</b>
            </h3>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.banner.create') }}">
                        Add Banner
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
                                ID
                            </th>

                            <th>
                                Image
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                Sub Title
                            </th>
                            <th>
                                Description
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $data)
                            <tr>
                                <td>{{ __($key + 1) }}</td>



                                <td>
                                    <img src="{{ asset('images/' . $data->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    {{ $data->title }}
                                </td>
                                <td>
                                    {{ $data->sub_title }}
                                </td>
                                <td>
                                    {{ $data->description }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.banner.edit',$data->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy',$data->id) }}" method="POST" style="display:inline-block;" id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $data->id }})">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <div class="card-footer clearfix">
            {{ $sector->links() }}
        </div> --}}
        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this Banner?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
