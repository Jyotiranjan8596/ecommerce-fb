 @extends('layouts.master')
 @section('content')
     <div class="card border-0 shadow-sm">
         <div class="card-header">
             Edit Blog
         </div>
         <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
             @csrf
             <div class="card-body">
                 <div class="mb-2">
                     <label for="title">Category*</label>
                     <select name="category_id" class="form-control"  @error('category_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($blog_category as $data )
                            <option value="{{ $data->id }}" {{ ($data->id == $blog->category_id	) ? 'selected':''}}>{{ $data->name}}</option>
                        @endforeach
                    </select>
                     @error('category_id')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Title*</label>
                     <input type="text" id="title" name="title"
                         class="form-control @error('title') is-invalid @enderror"
                         value="{{ old('title', isset($blog) ? $blog->title : '') }}">
                     @error('title')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Description*</label>
                     <textarea name="description" id="Editor" cols="30" rows="3"
                         class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($blog) ? $blog->description : '') }}</textarea>

                     @error('description')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                    <label for="image">Image*</label>
                    <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                    <img src="{{ asset('images/' .$blog->image) }}" alt="Image Preview" style="max-width: 80px; margin-top: 10px;">
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                 <div class="mb-2">
                     <label for="title">Meta Tag*</label>
                     <input type="text" id="meta_tag" name="meta_tag"
                         class="form-control @error('meta_tag') is-invalid @enderror"
                         value="{{ old('meta_tag', isset($blog) ? $blog->meta_tag : '') }}">
                     @error('meta_tag')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
             </div>
             <div class="card-footer">
                 <button class="btn btn-primary me-2" type="submit">Update</button>
                 <a class="btn btn-secondary" href="{{ route('admin.blog.index') }}">
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