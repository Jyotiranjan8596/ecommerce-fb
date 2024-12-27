<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInfo_GraphicRequest;
use App\Http\Requests\UpdateInfo_GraphicRequest;
use App\Models\InfoGraphic;
use App\Models\Sector;
use App\Models\SubSector;
use Illuminate\Http\Request;

class InfoGraphicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $info_graphics = InfoGraphic::orderBy('id', 'desc')->paginate('10');

        return view('admin.info_graphic.index', compact('info_graphics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sector = Sector::all();
        $subsector = SubSector::all();
        return view('admin.info_graphic.create', compact('sector','subsector'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfo_GraphicRequest $request)
    {
        $info_graphic = new InfoGraphic();
        $imageNames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }
        }

        $info_graphic->sector_id = $request->sector_id;
        $info_graphic->subsector_id = $request->subsector_id;
        $info_graphic->title = $request->title;
        $info_graphic->description = $request->description;
        $info_graphic->images = json_encode($imageNames); // Ensure this stores JSON

        if ($info_graphic->save()) {
            flash()->addSuccess('Infographic Successfully Created.');
            return redirect()->route('admin.info_graphic.index');
        }

        flash()->addError('Whoops! Infographic create failed!');
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
    public function edit(InfoGraphic  $info_graphic)
    {
        $sector = Sector::all();
        $subsector = SubSector::all();
        return view('admin.info_graphic.edit', compact('info_graphic', 'sector','subsector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInfo_GraphicRequest $request, $id)
    {
        $info_graphic = InfoGraphic::find($id);

        // Decode the existing images
        $existingImages = json_decode($info_graphic->images, true) ?? [];

        $imageNames = $existingImages;

        if ($request->hasFile('images')) {
            // Delete existing images from the public folder
            foreach ($existingImages as $existingImage) {
                $imagePath = public_path('images/' . $existingImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $imageNames = [];
            //upload the image           
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }
        }

        $info_graphic->sector_id = $request->sector_id;
        $info_graphic->subsector_id = $request->subsector_id;
        $info_graphic->title = $request->title;
        $info_graphic->description = $request->description;
        $info_graphic->images = json_encode($imageNames);

        if ($info_graphic->save()) {
            flash()->addSuccess('Infographic Successfully Updated.');
            return redirect()->route('admin.info_graphic.index');
        }

        flash()->addError('Whoops! Infographic update failed!');
        return redirect()->back();
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfoGraphic  $info_graphic)
    {
        if ($info_graphic) {

            $imageName = $info_graphic->images;

            // Delete the image file 
            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $info_graphic->delete();
        }
        flash()->addInfo('Infographic Deleted Successfully.');
        return back();
    }
}
