<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $cartItems = Cart::instance('wishlist')->content();
        return view('wishlist', compact('cartItems'));
    }

    public function add_to_wishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price, ['image' => $request->image])->associate('App\Models\Product');
        return redirect()->back();
    }

    

}
