@extends('layouts.master')
@section('content')

    <div class="card border-0 shadow-sm">
        <h4 class="card-header">
           <b> Create SubCategory</b>
        </h4>
        <form action="{{ route('admin.subsector.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="mb-2">
                    <label for="title">Sector*</label>
                    <select name="sector_id" class="form-control"  @error('sector_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($sector as $data )
                            <option value="{{ $data->id }}">{{ $data->title }}</option>
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
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror">
                    @error('title')
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
                <a class="btn btn-secondary" href="{{ route('admin.subsector.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

