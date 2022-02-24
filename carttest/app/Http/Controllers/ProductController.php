<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\cart;
  
class ProductController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $products = Product::all();
        $carts = DB::select('select * from carts');
        return view('products', compact('products'));
        return view('layout',['carts'=>$carts],['products'=>$products]);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cart()
    {
        //Try to input transaction
        $carts = DB::select('select * from carts');
        $total = DB::select(DB::raw("
        SELECT SUM(`quantity` * `price`) AS Total_price
        FROM `carts`
        "));
        return view('cart',['carts'=>$carts]);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        //Try to input transaction
            $product = Product::findOrFail($id);
            $cart = Cart::find($id);
            if(isset($cart)) {
                DB::table('carts')
                ->where('name', $product['name'])
                ->update(['quantity' => $cart['quantity']+1]);
            } else {
                $cart = new Cart([
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image
                ]);
                $cart->save();
            }
            $product['stock']=(int)$product['stock']-1 ;
            $product->save();
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request,$id)
    {
        //Try to input transaction
        if($request->id && $request->quantity){
            $product = Product::findOrFail($request->id);
            $cart = Cart::find($request->id);
            DB::table('carts')
            ->where('name', $product['name'])
            ->update(['quantity' => $request->quantity]);
            if(($request->quantity) >= ($cart['quantity'])){
                $product['stock']=(int)$product['stock']+($request->quantity) ;
                $product->save();

            }else{
                $product['stock']=(int)$product['stock']-($request->quantity) ;
                $product->save();
            }

        };
        session()->flash('success', 'Cart updated successfully');
        return redirect()->back();
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        //Try to input transaction
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        if($request->id) {
            $cart = Cart::find($id);
            if(isset($cart)) {
                $product = Product::find($request->id);
                $product['stock']=(int)$product['stock']+(int)$cart["quantity"] ;
                DB::delete('delete from cart where id = ?',[$request->id]);
            }
            session()->flash('success', 'Product removed successfully');
            }
        }
    }
}