 @extends('layouts.master')
 @section('content')
     <div class="card border-0 shadow-sm">
         <div class="card-header">
             Edit Event 
         </div>
         <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
             @csrf
             <div class="card-body"> 

                 <div class="mb-2">
                     <label for="title">Event Category </label>
                     <select name="category_id" class="form-control"  @error('category_id') is-invalid @enderror>
                        <option value="">Select</option>
                        @foreach ($event_category as $data )
                            <option value="{{ $data->id }}" {{ ($data->id == $event->category_id	) ? 'selected':''}}>{{ $data->name}}</option>
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
                           <option value="{{ $data->id }}" {{ ($data->id == $event->sector_id	) ? 'selected':''}}>{{ $data->title}}</option>
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
                         class="form-control @error('title') is-invalid @enderror"
                         value="{{ old('title', isset($event) ? $event->title : '') }}">
                     @error('title')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Description*</label>
                     <textarea name="description" id="Editor" cols="30" rows="3"
                         class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($event) ? $event->description : '') }}</textarea>
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
                     <img src="{{ asset('images/' . $event->image) }}" alt="Image Preview"
                         style="max-width: 80px; margin-top: 10px;">
                     @error('image')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Date From*</label>
                     <input type="date" id="date_from" name="date_from"
                         class="form-control @error('date_from') is-invalid @enderror"
                         value="{{ old('date_from', isset($event) ? $event->date_from : '') }}">
                     @error('date_from')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Date To*</label>
                     <input type="date" id="date_to" name="date_to"
                         class="form-control @error('date_to') is-invalid @enderror"
                         value="{{ old('date_to', isset($event) ? $event->date_to : '') }}">
                     @error('date_to')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="mb-2">
                     <label for="title">Place*</label>
                     <input type="text" id="place" name="place"
                         class="form-control @error('place') is-invalid @enderror"
                         value="{{ old('place', isset($event) ? $event->place : '') }}">
                     @error('place')
                         <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>
              

             </div>
             <div class="card-footer">
                 <button class="btn btn-primary me-2" type="submit">Update</button>
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