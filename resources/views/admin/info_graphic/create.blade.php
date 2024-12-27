@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Info Graphics
        </div>
        <form action="{{ route('admin.info_graphic.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="mb-2">
                    <label for="title">Sector *</label>
                    <select name="sector_id" class="form-control"  @error('sector_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($sector as $data )
                            <option value="{{ $data->id }}">{{ $data->title}}</option>
                        @endforeach
                    </select>
                    @error('sector_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Subsector </label>
                    <select name="subsector_id" class="form-control"  @error('subsector_id') is-invalid @enderror>
                       <option value="">Select</option>
                       @foreach ($subsector as $data )
                           <option value="{{ $data->id }}">{{ $data->title}}</option>
                       @endforeach
                   </select>
                    @error('subsector_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="title"
                        class="form-control @error('title') is-invalid @enderror">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Description*</label>
                    <textarea name="description" id="Editor" cols="30" rows="3"
                        class="form-control @error('description') is-invalid @enderror"></textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="images">Images*</label>
                    <input type="file" id="images" name="images[]" multiple
                        class="form-control @error('images') is-invalid @enderror">

                    @error('images')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Submit</button>
                <a class="btn btn-secondary" href="{{ route('admin.info_graphic.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('Editor');
</script>
@endsection
