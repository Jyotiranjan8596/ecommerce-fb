@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Event
        </div>
        <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="mb-2">
                    <label for="title">Event Category </label>
                    <select name="category_id" class="form-control"  @error('category_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($event_category as $data )
                            <option value="{{ $data->id }}">{{ $data->name}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Sector*</label>
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
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image"
                        class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Date From*</label>
                    <input type="date" id="date_from" name="date_from"
                        class="form-control @error('date_from') is-invalid @enderror">
                    @error('date_from')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Date To*</label>
                    <input type="date" id="date_to" name="date_to"
                        class="form-control @error('date_to') is-invalid @enderror">
                    @error('date_to')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Place*</label>
                    <input type="text" id="place" name="place"
                        class="form-control @error('place') is-invalid @enderror">
                    @error('place')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Submit</button>
                <a class="btn btn-secondary" href="{{ route('admin.event.index') }}">
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