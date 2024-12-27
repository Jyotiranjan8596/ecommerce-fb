<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubsectorRequest;
use App\Http\Requests\UpdateSubsectorRequest;
use App\Models\Sector;
use App\Models\SubSector;
use Illuminate\Http\Request;
use Mockery\Matcher\Subset;

class SubSectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subSectors = SubSector::orderBy('id', 'asc')->paginate();

        return view('admin.subsector.index', compact('subSectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sector = Sector::all();
        return view('admin.subsector.create', compact('sector'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubsectorRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $subSector = new SubSector();
        $subSector->sector_id = $request->sector_id;
        $subSector->title = $request->title;
        $subSector->image = $imageName;



        if ($subSector->save()) {
            flash()->addSuccess('SubCategory Successfully Created.');
            return redirect()->route('admin.subsector.index');
        }
        flash()->addError('Whoops! SubCategory create failed!');
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
    public function edit(SubSector  $subsector)
    {
        $sector = Sector::all();
        return view('admin.subsector.edit', compact('subsector', 'sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubsectorRequest $request, $id)
    {
        $subsector = SubSector::find($id);

        $existingImage = $subsector->image;
        if ($request->hasFile('image')) {
            // Delete existing image from the public folder
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

            $subsector->image = $imageName;
        }
        $subsector->sector_id = $request->sector_id;
        $subsector->title = $request->title;


        if ($subsector->save()) {
            flash()->addSuccess('SubCategory Successfully Updated.');
            return redirect()->route('admin.subsector.index');
        }

        flash()->addError('Whoops! SubCategory Update failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubSector  $subsector)
    {
        if ($subsector) {

            $imageName = $subsector->image;

            // Delete the image file 
            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $subsector->delete();
        }
        flash()->addInfo('SubCategory Deleted Successfully.');
        return back();
    }
}
