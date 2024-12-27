<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::orderBy('id', 'asc')->paginate();
        return view('admin.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blog_category = BlogCategory::all();
        return view('admin.blog.create', compact('blog_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {

        $blog = new Blog();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->meta_tag = $request->meta_tag;
        $blog->image = $imageName;


        if ($blog->save()) {
            flash()->addSuccess('Blog Successfully Created.');
            return redirect()->route('admin.blog.index');
        }
        flash()->addError('Whoops! Blog create failed!');
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
    public function edit(Blog  $blog)
    {
        $blog_category = BlogCategory::all();
        return view('admin.blog.edit', compact('blog', 'blog_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::find($id);
        $existingImage = $blog->image;
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

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->meta_tag = $request->meta_tag;
        $blog->image = $imageName;


        if ($blog->save()) {
            flash()->addSuccess('Blog Successfully Updated.');
            return redirect()->route('admin.blog.index');
        }

        flash()->addError('Whoops! Blog Update failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog  $blog)
    {
        $blog->delete();
        flash()->addInfo('Blog Deleted Successfully.');
        return back();
    }
}
