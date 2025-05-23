<?php
namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Page;
use App\Models\Product;
use App\Models\Sector;

class FrontendController extends Controller
{
    public function index()
    {
        $banner     = Banner::latest()->take(3)->get();
        $category   = Sector::latest()->take(5)->get();
        $products   = Product::all();
        $bestseller = Product::where('bestseller', 1)->get();
        $pages = Page::all();
        // dd($bestseller);
        return view('ui.index', compact('category', 'products', 'banner', 'bestseller', 'pages'));
    }

    public function category($id)
    {
        $category = Sector::find($id);
        $products = Product::where('sector_id', $id)->get();
        return view('ui.categoryList', compact('category', 'products'));
    }
    public function product($id)
    {
        $products = Product::find($id);
        return view('ui.product', compact('products'));
    }
    public function offer() {
        return view('ui.offer');
    }
    //  public function bestseller($id)
    // {
    //     Product::where('sector_id', $id)->where('dataset', 1)->increment('views');
    //     $sectors = Sector::all();
    //     $product = Product::where('sector_id', $id)->where('dataset', 1)->get();
    //     return view('frontend.highvalue', compact('product', 'sectors'));
    // }

    // public function categoryView($id)
    // {
    //     $viewCategory = Sector::find($id);
    //     return view('ui.categoryList', compact('viewCategory'));
    // }

    // public function subsector($id)
    // {
    //     $sector = Sector::find($id);
    //     if (!$sector) {
    //         return redirect()->back();
    //     }
    //     $subSectors = SubSector::where('sector_id', $id)->get();
    //     $products = Product::where('subsector_id', $id)->get();
    //     return view('frontend.subsector', compact('subSectors', 'sector', 'products'));
    // }

    // public function viewSubsector($id)
    // {
    //     $subSector = SubSector::find($id);
    //     if (!$subSector) {
    //         return redirect()->back();
    //     }
    //     $products = Product::where('subsector_id', $id)->get();
    //     $sector = $subSector->sector;
    //     $subSectors = SubSector::where('sector_id', $sector->id)->get();
    //     return view('frontend.subsector', compact('subSectors', 'sector', 'products'));
    // }
    // public function blogCategory()
    // {
    //     $allblogCategory = BlogCategory::all();
    //     return view('frontend.blog.blogcategory', compact('allblogCategory'));
    // }
    // public function view($id)
    // {
    //     $viewblog = BlogCategory::find($id);
    //     $allblog = Blog::where('category_id', $id)->get();
    //     return view('frontend.blog.blog', compact('viewblog', 'allblog'));
    // }
    // public function Readmore($id)
    // {
    //     $view = Blog::find($id);
    //     return view('frontend.blog.readmore', compact('view'));
    // }

    // public function datasetView()
    // {
    //     $product = Product::where('dataset', 1)->orderBy('views', 'desc')->get();
    //     return view('frontend.viewHighvalue', compact('product'));
    // }
    // public function recentlyAdded()
    // {
    //     $products = Product::where('dataset', 1)->orderBy('created_at', 'desc')->get();
    //     return view('frontend.recentaddHighvalue', compact('products'));
    // }
    // public function eventCategory()
    // {
    //     $eventCategory = EventCategory::all();
    //     return view('frontend.event.eventcategory', compact('eventCategory'));
    // }
    // public function eventView($id)
    // {
    //     $viewEventcategory = EventCategory::find($id);
    //     $allevent = Event::where('category_id', $id)->get();
    //     return view('frontend.event.event', compact('viewEventcategory', 'allevent'));
    // }
    // public function eventReadmore($id)
    // {
    //     $viewevent = Event::find($id);
    //     return view('frontend.event.readmore', compact('viewevent'));
    // }
    // public function infographics()
    // {
    //     return view('frontend.infographics');
    // }

    public function page()
    {
        // $pages = Page::find($id); 
        // dd($pages->title);
        // if (!$pages) {
        //     return redirect()->back()->with('error', 'Page not found'); 
        //     // OR show a 404 page: abort(404, "Page not found");
        // }
        return view('ui.page.index');
    }

    public function termCondition() {
        // $user_profile = auth()->user();
        // $userId       = $user_profile->id;
        return view('frontend.dashboard.termcondition');
    }
    

}
