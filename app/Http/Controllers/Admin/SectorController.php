<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;
use App\Models\Sector;
use App\Models\SubSector;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectors = Sector::orderBy('id', 'asc')->simplePaginate(15);
        return view('admin.sector.index', compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.sector.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectorRequest $request)
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $sector = new Sector();
        $sector->title = $request->title;
        $sector->image = $imageName;


        if ($sector->save()) {
            flash()->addSuccess('Category Successfully Created.');
            return redirect()->route('admin.sector.index');
        }
        flash()->addError('Whoops! Category create failed!');
        return redirect()->back();
    }

    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sector $sector)
    {
        return view('admin.sector.edit', compact('sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectorRequest $request, $id)
    {
        $sector = Sector::find($id);


        $existingImage = $sector->image;
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

            $sector->image = $imageName;
        }
        $sector->title = $request->title;

        if ($sector->save()) {
            flash()->addSuccess('Category Successfully Updated.');
            return redirect()->route('admin.sector.index');
        }

        flash()->addError('Whoops! Category Update failed!');
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sector $sector)
    {
        if ($sector) {

            $imageName = $sector->image;

            // Delete the image file 
            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // SubSector::where('sector_id', $sector->id)->delete();
            $sector->delete();
        }
        flash()->addInfo('Category Deleted Successfully.');
        return back();
    }
}
