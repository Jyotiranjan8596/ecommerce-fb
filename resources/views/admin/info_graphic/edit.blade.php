 @extends('layouts.master')
 @section('content')
     <div class="card border-0 shadow-sm">
         <div class="card-header">
             Edit Info Graphics
         </div>
         <form action="{{ route('admin.info_graphic.update', $info_graphic->id) }}" method="POST"
             enctype="multipart/form-data">
             @csrf
             <div class="card-body">

                 <div class="mb-2">
                     <label for="title">Sector </label>
                     <select name="sector_id" class="form-control"  @error('sector_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($sector as $data )
                            <option value="{{ $data->id }}" {{ ($data->id == $info_graphic->sector_id	) ? 'selected':''}}>{{ $data->title}}</option>
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
                            <option value="{{ $data->id }}" {{ ($data->id == $info_graphic->subsector_id	) ? 'selected':''}}>{{ $data->title}}</option>
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
                         class="form-control @error('title') is-invalid @enderror"
                         value="{{ old('title', isset($info_graphic) ? $info_graphic->title : '') }}">
                     @error('title')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Description*</label>
                     <textarea name="description" id="Editor" cols="30" rows="3"
                         class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($info_graphic) ? $info_graphic->description : '') }}</textarea>
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
                     @if (is_array($info_graphic->images))
                         @foreach ($info_graphic->images as $image)
                             <img src="{{ asset('images/' . $image) }}" alt="Image Preview"
                                 style="max-width: 80px; margin-top: 10px;">
                         @endforeach
                     @else
                         @php
                             $images = json_decode($info_graphic->images);
                         @endphp
                         @if (is_array($images))
                             @foreach ($images as $image)
                                 <img src="{{ asset('images/' . $image) }}" alt="Image"
                                     style="max-width: 50px; max-height: 50px;">
                             @endforeach
                         @endif
                     @endif
                     @error('images')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror

                 </div>

             </div>
             <div class="card-footer">
                 <button class="btn btn-primary me-2" type="submit">Update</button>
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
