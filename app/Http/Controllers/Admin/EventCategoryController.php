<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventCategoryRequest;
use App\Http\Requests\UpdateEventCategoryRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sector;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event_categories = EventCategory::orderBy('id', 'asc')->paginate();
        return view('admin.event_category.index', compact('event_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.event_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventCategoryRequest $request)
    {
       
        $event_category = new EventCategory();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }
      
        $event_category->name= $request->name;
        $event_category->image = $imageName;

        
        if ($event_category->save()) {
            flash()->addSuccess('Event Category Successfully Created.');
            return redirect()->route('admin.event_category.index');
            // toastr()->success('Event Category Successfully Created.');
            // return redirect()->route('admin.event_category.index');
        }
        flash()->addError('Whoops! Event create failed!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventCategory  $event_category)
    {
        return view('admin.event_category.edit', compact('event_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventCategoryRequest $request, $id)
    {
        $event_category = EventCategory::find($id);
      
        $existingImage = $event_category->image;
        // Delete existing image from the public folder
        if ($request->hasFile('image')) {
            if ($existingImage) {
                $imagePath = public_path('images/' . $existingImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        // Initialize new image name
        $imageName = $existingImage;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }
        $event_category->name = $request->name;
        $event_category->image = $imageName;
   
        if ($event_category->save()) {
            flash()->addSuccess('Event Category Successfully Updated.');
            return redirect()->route('admin.event_category.index');
        }

        flash()->addError('Whoops! Event Category Update failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventCategory  $event_category)
    {
        // Event::where('category_id',$event_category->id)->delete(); 
        $event_category->delete();
        flash()->addInfo('Event Category Deleted Successfully.');
        return back();
    }
}
