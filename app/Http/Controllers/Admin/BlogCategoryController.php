<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog_categories = BlogCategory::orderBy('id', 'asc')->paginate();
        return view('admin.blog_category.index', compact('blog_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogCategoryRequest $request)
    {
       
        $blog_category = new BlogCategory();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }
      
        $blog_category->name= $request->name;
        $blog_category->image = $imageName;
     

        
        if ($blog_category->save()) {
            flash()->addSuccess('Blog Category Successfully Created.');
            return redirect()->route('admin.blog_category.index');
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
    public function edit(BlogCategory  $blog_category)
    {
        return view('admin.blog_category.edit', compact('blog_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogCategoryRequest $request, $id)
    {
        $blog_category = BlogCategory::find($id);

        $existingImage = $blog_category->image;
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
      
    
        $blog_category->name = $request->name;
        $blog_category->image = $imageName;
   
        if ($blog_category->save()) {
            flash()->addSuccess('Blog Category Successfully Updated.');
            return redirect()->route('admin.blog_category.index');
        }

        flash()->addError('Whoops! Blog Category Update failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory  $blog_category)
    {
        // Blog::where('category_id', $blog_category->id)->delete();
        $blog_category->delete();
        flash()->addInfo('Blog Category Deleted Successfully.');
        return back();
    }
}
