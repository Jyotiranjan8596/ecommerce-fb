<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('id', 'asc')->paginate();
        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->sub_title = $request->sub_title;
        $banner->description = $request->description;
        $banner->image = $imageName;


        if ($banner->save()) {
            flash()->addSuccess('Banner Successfully Created.');
            return redirect()->route('admin.banner.index');
        }
        flash()->addError('Whoops! Banner create failed!');
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
    public function edit(string $id)
    {
        $banners = Banner::find($id);
        return view('admin.banner.edit', compact('banners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);

        $existingImage = $banner->image;
        if ($request->hasFile('image')) {
            if ($existingImage) {
                $imagePath = public_path('images/' . $existingImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $imageName = $existingImage;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            $banner->image = $imageName;
        }
        $banner->title = $request->title;
        $banner->sub_title = $request->sub_title;
        $banner->description = $request->description;

        if ($banner->save()) {
            flash()->addSuccess('Banner Successfully Updated.');
            return redirect()->route('admin.banner.index');
        }

        flash()->addError('Whoops! Banner Update failed!');
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banners = Banner::find($id);
        if ($banners) {

            $imageName = $banners->image;

            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $banners->delete();
        }
        flash()->addInfo('Banner Deleted Successfully.');
        return back();
    }
}
