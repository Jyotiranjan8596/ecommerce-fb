@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="float-start">
                <b>SubCategory List</b>
            </h3>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.subsector.create') }}">
                        Add Subsector
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
                                Sector
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
                        @foreach ($subSectors as $key => $subsector)
                            <tr>
                                <td>{{ __($key + 1) }}</td>

                                <td>
                                    @if ($subsector->sector)
                                        {{ $subsector->sector->title }}
                                    @endif
                                </td>
                                <td>
                                    {{ $subsector->title }}
                                </td>

                                <td>
                                    <img src="{{ asset('images/' . $subsector->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    <a href="{{ route('admin.subsector.edit', $subsector->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.subsector.destroy', $subsector->id) }}" method="POST"
                                        style="display:inline-block;" id="delete-form-{{ $subsector->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $subsector->id }})">
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
                if (confirm('Are you sure you want to delete this SubCategory?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
