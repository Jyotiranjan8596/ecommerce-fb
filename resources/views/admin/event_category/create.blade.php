@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            Create Event Category
        </div>
        <form action="{{ route('admin.event_category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="mb-2">
                    <label for="title">Name*</label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Image*</label>
                    <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Submit</button>
                <a class="btn btn-secondary" href="{{ route('admin.event_category.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
