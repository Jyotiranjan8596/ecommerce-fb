<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sector;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('id', 'asc')->paginate();
        return view('admin.event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $event_category = EventCategory::all();
        $sector = Sector::all();
        return view('admin.event.create', compact('event_category', 'sector'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event = new Event();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $event->category_id = $request->category_id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->image = $imageName;
        $event->date_from = $request->date_from;
        $event->date_to = $request->date_to;
        $event->place = $request->place;
        $event->sector_id = $request->sector_id;



        if ($event->save()) {
            flash()->addSuccess('Event Successfully Created.');
            return redirect()->route('admin.event.index');
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
    public function edit(Event  $event)
    {
        $event_category = EventCategory::all();
        $sector = Sector::all();
        return view('admin.event.edit', compact('event', 'event_category', 'sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::find($id);
        $existingImage = $event->image;
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



        $event->category_id = $request->category_id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->image = $imageName;
        $event->date_from = $request->date_from;
        $event->date_to = $request->date_to;
        $event->place = $request->place;
        $event->sector_id = $request->sector_id;

        if ($event->save()) {
            flash()->addSuccess('Event Successfully Updated.');
            return redirect()->route('admin.event.index');
        }

        flash()->addError('Whoops! Event Update failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event  $event)
    {
        if ($event) {

            $imageName = $event->image;
            // Delete the image file 
            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $event->delete();
        }
        flash()->addInfo('Event Deleted Successfully.');
        return back();
    }
}
