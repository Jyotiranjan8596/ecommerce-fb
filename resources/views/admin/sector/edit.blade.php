@extends('layouts.master')
@section('content')

    <div class="card border-0 shadow-sm">
        <h4 class="card-header">
           <b> Edit Category</b>
        </h4>
        <form action="{{ route('admin.sector.update', $sector->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', isset($sector) ? $sector->title : '') }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                    <img src="{{ asset('images/' . $sector->image) }}" alt="Image Preview" style="max-width: 80px; margin-top: 10px;">
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
               
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.sector.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
 
