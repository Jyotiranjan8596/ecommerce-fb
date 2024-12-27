@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <h4 class="card-header">
            <b> Edit Banner</b>
        </h4>
        <form action="{{ route('admin.banner.update',$banners->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image"
                        class="form-control @error('image') is-invalid @enderror">
                    <img src="{{ asset('images/' . $banners->image) }}" alt="Image Preview"
                        style="max-width: 80px; margin-top: 10px;">
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="title">Title*</label>
                    <input type="text" id="title" name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', isset($banners) ? $banners->title : '') }}">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="sub_title">Sub Title*</label>
                    <input type="text" id="sub_title" name="sub_title"
                        class="form-control @error('sub_title') is-invalid @enderror"
                        value="{{ old('sub_title', isset($banners) ? $banners->sub_title : '') }}">
                    @error('sub_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="description">Description*</label>
                        <textarea name="description" class="form-control" id="description" cols="20" rows="3">{{$banners->description  }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
                <a class="btn btn-secondary" href="{{ route('admin.banner.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection
