<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\Sector;
use App\Models\SubSector;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->simplePaginate(15);
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $sector = Sector::all();
        $subsector = SubSector::all();
        return view('admin.product.create', compact('sector', 'subsector', 'products'));
    }
    public function export(){
        return Excel::download(new ProductExport,'products.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        Excel::import(new ProductImport, $request->file('file'));
    
        return redirect()->route('admin.product.index')->with('success', 'Products imported successfully!');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // $file = $request->file('product_file');

        // $import = new UsersImport();
        // Excel::import($import, $file);

        // $columns = $import->getColumns();

        // $headers = json_encode($columns);

        $product = new Product();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $product->sector_id = $request->sector_id;
        // $product->subsector_id = $request->subsector_id;
        $product->title = $request->title;
       
        $product->description = $request->description;
        $product->bestseller = $request->input('bestseller');
        $product->image = $imageName;
        // $product->header = $headers;

        // if ($request->hasFile('product_file')) {
        //     $product_file = $request->file('product_file');
        //     $product_file_excel = time() . '.' . $product_file->getClientOriginalExtension();
        //     $product_file->move(public_path('product_files'), $product_file_excel);
        // }
        // $product->product_file = $product_file_excel;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->total_price = $request->total_price;
      

        if ($product->save()) {
            flash()->addSuccess('Product Successfully Created.');
            return redirect()->route('admin.product.index');
        }
        flash()->addError('Whoops! Product create failed!');
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
    public function edit(Product  $product)
    {
        $products = Product::all();
        $sector = Sector::all();
        $subsector = SubSector::all();
        return view('admin.product.edit', compact('product', 'sector', 'subsector', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
         
        if ($request->hasFile('product_file')) {
            $file = $request->file('product_file');
    
            $import = new UsersImport();
            Excel::import($import, $file);

            $columns = $import->getColumns();
            $headers = json_encode($columns);
    
            $existingProductFile = $product->product_file;
            if ($existingProductFile) {
                $productFilePath = public_path('product_files/' . $existingProductFile);
                if (file_exists($productFilePath)) {
                    unlink($productFilePath);
                }
            }
    
            $productFileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('product_files'), $productFileName);
            $product->product_file = $productFileName;
            $product->header = $headers;
        }
          
        if ($request->hasFile('image')) {
            $existingImage = $product->image;
            if ($existingImage) {
                $imagePath = public_path('images/' . $existingImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }
    
        $product->sector_id = $request->sector_id;
        // $product->subsector_id = $request->subsector_id ?? $product->subsector_id;
        $product->title = $request->title;
        
        $product->description = $request->description;
        $product->price = $request->price ?? $product->price;
        $product->discount_price = $request->discount_price;
        $product->total_price = $request->total_price;
        $product->bestseller = $request->input('bestseller');
      
    
        if ($product->save()) {
            flash()->addSuccess('Product Successfully Updated.');
            return redirect()->route('admin.product.index');
        }
    
        flash()->addError('Whoops! Product Update failed!');
        return redirect()->back();
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product  $product)
    {
        if ($product) {

            $imageName = $product->image;
            // Delete the image file 
            if ($imageName) {
                $imagePath = public_path('images/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $product->delete();
        }
        flash()->addInfo('Product Deleted Successfully.');
        return back();
    }

    public function getSubsector(Request $request)
    {
        $getsubsector = SubSector::where('sector_id', $request->sector_id)->get();
        return response()->json($getsubsector);
    }
}
