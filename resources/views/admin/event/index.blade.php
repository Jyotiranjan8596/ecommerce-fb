@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Event List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.event.create') }}">
                        Add Event
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
                                Event Category
                            </th>
                            <th>
                                Sector
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Image
                            </th>
                            <th>
                                Date From
                            </th>
                            <th>
                                Date To
                            </th>
                            <th>
                                Place
                            </th>


                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $key => $event)
                            <tr>
                                <td>{{ __($key + 1) }}</td>

                                <td>
                                    @if ($event->eventCategory)
                                        {{ $event->eventCategory->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($event->sector)
                                        {{ $event->sector->title }}
                                    @endif
                                </td>
                                <td>
                                    {{ $event->title }}
                                </td>
                                <td>
                                    {!! Str::limit($event->description, 20, '...') !!}
                                </td>
                                <td>
                                    <img src="{{ asset('images/' . $event->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    {{ $event->date_from }}
                                </td>
                                <td>
                                    {{ $event->date_to }}
                                </td>
                                <td>
                                    {{ $event->place }}
                                </td>



                                <td>
                                    <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST"
                                        style="display:inline-block;" id="delete-form-{{ $event->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $event->id }})">
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
                if (confirm('Are you sure you want to delete this Event?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
