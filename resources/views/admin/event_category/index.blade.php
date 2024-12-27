@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Event Category List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.event_category.create') }}">
                        Add Event Category
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
                                Name
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
                        @foreach ($event_categories as $key => $event_category)
                            <tr>
                                <td>{{ __($key + 1) }}</td>

                                <td>
                                    {{ $event_category->name }}
                                </td>
                                <td>
                                    <img src="{{ asset('images/' . $event_category->image) }}" alt=""
                                    style="max-width: 50px; max-height: 50px;">
                                </td>

                                <td>
                                    <a href="{{ route('admin.event_category.edit', $event_category->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.event_category.destroy', $event_category->id) }}"
                                        method="POST" style="display:inline-block;"
                                        id="delete-form-{{ $event_category->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $event_category->id }})">
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
                if (confirm('Are you sure you want to delete this Event Category?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
