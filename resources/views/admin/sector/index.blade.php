@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="float-start">
               <b> Category List</b>
            </h3>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.sector.create') }}">
                        Add Category
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
                                Title
                            </th>
                            <th>
                                Image
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectors as $key => $sector)
                            <tr>
                                <td>{{ $sectors->firstItem() + $key }}</td>

                                <td>
                                    {{ $sector->title }}
                                </td>

                                <td>
                                    <img src="{{ asset('images/' . $sector->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    <a href="{{ route('admin.sector.edit', $sector->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sector.destroy', $sector->id) }}" method="POST" style="display:inline-block;" id="delete-form-{{ $sector->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $sector->id }})">
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
        
        <div class="card-footer clearfix">
            {{ $sectors->links() }}
        </div>
        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this Category?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
