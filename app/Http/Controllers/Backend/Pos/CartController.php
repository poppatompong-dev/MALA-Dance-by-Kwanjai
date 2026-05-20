<?php

namespace App\Http\Controllers\Backend\Pos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\PosCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $cartItems = PosCart::where('user_id', auth()->id())
                ->with('product')
                ->latest('created_at')
                ->get()
                ->map(function ($item) {
                    // Calculate row total for each item
                    $item->row_total = round(($item->quantity * $item->product->discounted_price),2);
                    return $item;
                });
            $total = $cartItems->sum('row_total');
            return response()->json([
                'carts' => $cartItems,
                'total' => round($total, 2)
            ]);
        }
        // clear cart
        PosCart::where('user_id', auth()->id())->delete();
        return view('backend.cart.index');
    }
    public function getProducts(Request $request)
    {

        $products = Product::query()->active()->stocked();
        // Search by name if provided
        $products->when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });

        // Search by barcode if provided
        $products->when($request->barcode, function ($query, $barcode) {
            $query->where('sku', $barcode);
        });
        $products = $products->latest()->paginate(96);
        if (request()->wantsJson()) {
            return ProductResource::collection($products);
        }
    }

    public function store(Request $request)
    {
        // Validate request input
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        $product_id = $request->id;

        // Fetch the product
        $product = Product::find($product_id);

        // Check if the product is active and has sufficient stock
        if (!$product->status) {
            return response()->json(['message' => 'สินค้านี้ยังไม่พร้อมขาย'], 400);
        }

        if ($product->quantity <= 0) {
            return response()->json(['message' => 'สต็อกสินค้าไม่เพียงพอ'], 400);
        }

        // Fetch the cart item for the current user and product
        $cartItem = PosCart::where('user_id', auth()->id())->where('product_id', $product_id)->first();

        if ($cartItem) {
            // If the product is already in the cart, increment the quantity
            if ($cartItem->quantity < $product->quantity) {
                $cartItem->quantity += 1;
                $cartItem->save();
                return response()->json(['message' => 'อัปเดตจำนวนแล้ว', 'quantity' => $cartItem->quantity], 200);
            } else {
                return response()->json(['message' => 'เพิ่มไม่ได้ เพราะถึงจำนวนสต็อกสูงสุดแล้ว'], 400);
            }
        } else {
            // If not in the cart, create a new cart item
            $cart = new PosCart();
            $cart->user_id = auth()->id();
            $cart->product_id = $product_id;
            $cart->quantity = 1;
            $cart->save();
            return response()->json(['message' => 'เพิ่มสินค้าเข้าตะกร้าแล้ว', 'quantity' => 1], 201);
        }
    }

    public function increment(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);

        $cart = PosCart::with('product')->findOrFail($request->id);
        if ($cart->product->quantity <= 0) {
            return response()->json(['message' => 'สต็อกสินค้าไม่เพียงพอ'], 400);
        }
        if ($cart->quantity == $cart->product->quantity) {
            return response()->json(['message' => 'เพิ่มไม่ได้ เพราะถึงจำนวนสต็อกสูงสุดแล้ว'], 400);
        }
        $cart->quantity = $cart->quantity + 1;
        $cart->save();
        return response()->json(['message' => 'อัปเดตตะกร้าเรียบร้อยแล้ว'], 200);
    }
    public function decrement(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);
        $cart = PosCart::findOrFail($request->id);
        if ($cart->quantity <= 1) {
            return response()->json(['message' => 'จำนวนต้องไม่น้อยกว่า 1'], 400);
        }
        $cart->quantity = $cart->quantity - 1;
        $cart->save();
        return response()->json(['message' => 'อัปเดตตะกร้าเรียบร้อยแล้ว'], 200);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);

        $cart = PosCart::findOrFail($request->id);
        $cart->delete();

        return response()->json(['message' => 'ลบสินค้าออกจากตะกร้าแล้ว'], 200);
    }
    public function empty()
    {
        $deletedCount = PosCart::where('user_id', auth()->id())->delete();

        if ($deletedCount > 0) {
            return response()->json(['message' => 'ล้างตะกร้าเรียบร้อยแล้ว'], 200);
        }

        return response()->json(['message' => 'ตะกร้าว่างอยู่แล้ว'], 204);
    }
}
