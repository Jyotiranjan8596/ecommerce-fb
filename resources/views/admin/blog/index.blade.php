@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Blog List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.blog.create') }}">
                        Add Blog
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
                                Category
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
                                Meta Tag
                            </th>

                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $key => $blog)
                            <tr>
                                <td>{{ __($key + 1) }}</td>

                                <td>
                                    @if ($blog->blogCategory)
                                        {{ $blog->blogCategory->name }}
                                    @endif
                                </td>

                                <td>
                                    {{ $blog->title }}
                                </td>
                                <td>
                                    {!! Str::limit($blog->description, 20, '...') !!}
                                </td>
                                <td>
                                    <img src="{{ asset('images/' . $blog->image) }}" alt=""
                                        style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    {{ $blog->meta_tag }}
                                </td>


                                <td>
                                    <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST"
                                        style="display:inline-block;" id="delete-form-{{ $blog->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $blog->id }})">
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
                if (confirm('Are you sure you want to delete this blog?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    </div>
@endsection
