<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function cart()
    {
        //dd(session()->get('product_id'));
        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        // dd($cartItems);
        $totalPrice = $cartItems->sum(fn($item) => $item->product->price);
        return view('ui.addToCart', compact('cartItems', 'totalPrice'));
    }

    public function addToCart($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to log in to add items to the cart.');
        }

        if (Auth::user()->role != 3) {
            return redirect()->back()->with('error', 'Only customers are allowed to add items to the cart.');
        }

        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();
    
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->total_price = $cartItem->quantity * $product->price;
            $cartItem->save();
    
            return redirect()->back()->with('success', 'Product quantity updated in your cart.');
        }
        $cartItem = new Cart;
        $cartItem->user_id = Auth::id();
        $cartItem->product_id = $id;
        $cartItem->status = 0;
        $cartItem->quantity = 1;
        $cartItem->total_price = $product->price;
        $cartItem->save();
    
        return redirect()->route('frontend.cart')->with('success', 'Product added to your cart successfully.');
    }
    


    public function increaseQuantity(Request $request, $id)
    {
        $cartItem = Cart::find($id);

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
            $totalPrice = $cartItem->quantity * $cartItem->product->total_price;

            $cartItem->update([
                'total_price' => $totalPrice
            ]);
            $sub_total = $request->sub_total + $cartItem->product->total_price;
            return response()->json([
                'newQuantity' => $cartItem->quantity,
                'newTotalPrice' => $totalPrice,
                'newsub_total' => $sub_total,
            ]);
        }
        return response()->json(['error' => 'Cart item not found.'], 404);
    }

    public function decreaseQuantity(Request $request, $id)
    {
        $cartItem = Cart::find($id);
        if ($cartItem && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
            $totalPrice = $cartItem->quantity * $cartItem->product->total_price;

            $cartItem->update([
                'total_price' => $totalPrice
            ]);

            $sub_total = $request->sub_total - $cartItem->product->total_price;
            return response()->json([
                'newQuantity' => $cartItem->quantity,
                'newTotalPrice' => $totalPrice,
                'newsub_total' => $sub_total,
            ]);
        }
        return response()->json(['error' => 'Invalid quantity'], 400);
    }

    public function delete($id)
    {
        $cartItem = Cart::find($id);
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Product Deleted Successfully');
        }
        return redirect()->back()->with('error', 'Product not found');
    }
    public function checkOut()
    {
        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        return view('ui.checkout', compact('cartItems'));
    }
}
