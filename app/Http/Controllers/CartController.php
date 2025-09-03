<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::instance('cart')->content();
        return view('cart', compact('cartItems'));
    }

    public function add_to_cart(Request $request)
    {
        $options = [];
        if ($request->has('selected_image')) {
            $options['selected_image'] = $request->selected_image;
        }

        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price,
            $options
        )->associate('App\Models\Product');

        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::id())
            ->where('isdefault', 1)
            ->first();

        return view('checkout', compact('address'));
    }

    public function setAmountForCheckout()
    {
        if (!(Cart::instance('cart')->count() > 0)) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => floatval(str_replace(',', '', Session::get('discounts')['discount'])),
                'subtotal' => floatval(str_replace(',', '', Session::get('discounts')['subtotal'])),
                'tax'      => floatval(str_replace(',', '', Session::get('discounts')['tax'])),
                'total'    => floatval(str_replace(',', '', Session::get('discounts')['total'])),
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => floatval(str_replace(',', '', Cart::instance('cart')->subtotal())),
                'tax'      => floatval(str_replace(',', '', Cart::instance('cart')->tax())),
                'total'    => floatval(str_replace(',', '', Cart::instance('cart')->total())),
            ]);
        }
    }

    public function place_order(Request $request)
    {
        $user_id = Auth::id();

        // Validate shipping fields
        $request->validate([
            'name'     => 'required|max:100',
            'phone'    => 'required|numeric|digits_between:9,15',
            'zip'      => 'required|numeric|digits_between:4,10',
            'state'    => 'required',
            'city'     => 'required',
            'address'  => 'required',
            'locality' => 'required',
            'landmark' => 'required'
        ]);

        // Fetch or create/update default address
        $address = Address::where('user_id', $user_id)
            ->where('isdefault', true)
            ->first();

        if ($address) {
            // ✅ Update existing address
            $address->update([
                'name'     => $request->name,
                'phone'    => $request->phone,
                'zip'      => $request->zip,
                'state'    => $request->state,
                'city'     => $request->city,
                'address'  => $request->address,
                'locality' => $request->locality,
                'landmark' => $request->landmark,
                'country'  => 'MALAYSIA',
            ]);
        } else {
            // ✅ Create new default address
            $address = Address::create([
                'user_id'  => $user_id,
                'name'     => $request->name,
                'phone'    => $request->phone,
                'zip'      => $request->zip,
                'state'    => $request->state,
                'city'     => $request->city,
                'address'  => $request->address,
                'locality' => $request->locality,
                'landmark' => $request->landmark,
                'country'  => 'MALAYSIA',
                'isdefault' => true,
            ]);
        }

        // Ensure checkout session is set
        $this->setAmountForCheckout();
        $checkout = Session::get('checkout');

        if (!$checkout) {
            return redirect()->route('cart.order.confirmation')
                ->with('error', 'Checkout session expired. Please try again.');
        }

        // Create order
        $order = new Order();
        $order->user_id  = $user_id;
        $order->subtotal = $checkout['subtotal'];
        $order->discount = $checkout['discount'];
        $order->tax      = $checkout['tax'];
        $order->total    = $checkout['total'];
        $order->name     = $address->name;
        $order->phone    = $address->phone;
        $order->locality = $address->locality;
        $order->address  = $address->address;
        $order->city     = $address->city;
        $order->state    = $address->state;
        $order->country  = $address->country;
        $order->landmark = $address->landmark;
        $order->zip      = $address->zip;
        $order->save();

        // Save order items
        foreach (Cart::instance('cart')->content() as $item) {
            $orderitem = new OrderItem();
            $orderitem->product_id = $item->id;
            $orderitem->order_id   = $order->id;
            $orderitem->price      = $item->price;
            $orderitem->quantity   = $item->qty;
            $orderitem->save();
        }

        // Save transaction if COD
        if ($request->mode == "cod") {
            $transaction = new Transaction();
            $transaction->user_id  = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode     = $request->mode;
            $transaction->status   = "pending";
            $transaction->save();
        }

        // Clear session + cart
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');

        // Save order id for confirmation
        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }

    public function order_confirmation()
    {
        $order_id = Session::get('order_id');
        $order = Order::find($order_id);

        return view('order-confirmation', compact('order'));
    }
}
