 @extends('layouts.master')
 @section('content')
     <div class="card border-0 shadow-sm">
         <div class="card-header">
             Edit Blog Category
         </div>
         <form action="{{ route('admin.blog_category.update', $blog_category->id) }}" method="POST" enctype="multipart/form-data">
             @csrf
             <div class="card-body">
                
                 <div class="mb-2">
                     <label for="title">Name*</label>
                     <input type="text" id="name" name="name"
                         class="form-control @error('name') is-invalid @enderror"
                         value="{{ old('title', isset($blog_category) ? $blog_category->name : '') }}">
                     @error('name')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                    <img src="{{ asset('images/' . $blog_category->image) }}" alt="Image Preview" style="max-width: 80px; margin-top: 10px;">
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
           
             </div>
             <div class="card-footer">
                 <button class="btn btn-primary me-2" type="submit">Update</button>
                 <a class="btn btn-secondary" href="{{ route('admin.blog_category.index') }}">
                     Back to list
                 </a>
             </div>
         </form>
     </div>
 @endsection
