@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Info Graphics List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.info_graphic.create') }}">
                        Add Info Graphics
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
                                Subsector
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Images
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($info_graphics as $key => $info_graphic)
                            <tr>
                                <td>{{ __($key + 1) }}</td>

                                <td>
                                    @if ($info_graphic->sector)
                                        {{ $info_graphic->sector->title }}
                                    @endif
                                </td>
                                <td>
                                    @if ($info_graphic->subsector)
                                        {{ $info_graphic->subsector->title }}
                                    @endif
                                </td>
                                <td>
                                    {{ $info_graphic->title }}
                                </td>
                                <td>
                                    {!! Str::limit($info_graphic->description, 20, '...') !!}
                                </td>
                                <td>
                                    @if (is_array($info_graphic->images))
                                        @foreach ($info_graphic->images as $image)
                                            <img src="{{ asset('images/' . $image) }}" alt="Image"
                                                style="max-width: 50px; max-height: 50px;">
                                        @endforeach
                                    @else
                                        @php
                                            $images = json_decode($info_graphic->images);
                                        @endphp
                                        @if (is_array($images))
                                            @foreach ($images as $image)
                                                <img src="{{ asset('images/' . $image) }}" alt="Image"
                                                    style="max-width: 50px; max-height: 50px;">
                                            @endforeach
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.info_graphic.edit', $info_graphic->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.info_graphic.destroy', $info_graphic->id) }}"
                                        method="POST" style="display:inline-block;"
                                        id="delete-form-{{ $info_graphic->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $info_graphic->id }})">
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
                if (confirm('Are you sure you want to delete this Info graphic?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
