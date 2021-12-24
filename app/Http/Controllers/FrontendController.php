<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CheckoutRequest;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('galleries')->select('id','name','price','slug')->get();

        return view('pages.frontend.index', ['products' => $products]);
    }
    
    public function detail(Request $request, $slug)
    {
        $product = Product::with('galleries')->where('slug', $slug)->select('id','name','price','slug','description')->firstOrFail();

        $recomendations = Product::with(['galleries:products_id,url'])->select('id','name','slug','price')->get();        

        return view('pages.frontend.details', [
            'product' => $product,
            'recomendations' => $recomendations
        ]);
    }

    public function cart(Request $request)
    {
        $carts = Cart::with(['product:id,name,price','product.galleries:products_id,url'])->where('users_id', auth()->user()->id)->select('id', 'users_id', 'products_id')->get();            
        return view('pages.frontend.cart',['carts' => $carts]);
    }

    public function addCart($id)
    {
        Cart::create([
            'users_id' => auth()->user()->id,
            'products_id' => $id
        ]);

        return redirect()->route('cart');
    }

    public function deleteCart($id)
    {
        $item = Cart::findOrFail($id);
        $item->delete();

        return redirect()->route('cart');
    }

    public function checkout(CheckoutRequest $request)
    {
        $data = $request->all();

        // Get data from cart
        $carts = Cart::with(['product:id,name,price,description'])->where('users_id', auth()->user()->id)->select('id','users_id','products_id')->get();
        
        // Add to transaction data
        $data['users_id'] = auth()->user()->id;
        $data['total_price'] = $carts->sum('product.price');

        // Create transaction
        $transaction = Transaction::create($data);

        // Create transaction item
        foreach ($carts as $cart) {            
            $item['transactions_id'] = $transaction->id;
            $item['users_id'] = $cart->users_id;
            $item['products_id'] = $cart->products_id;
            $item['created_at'] = Carbon::now();
            $item['updated_at'] = Carbon::now();
        }
        DB::table('transaction_items')->insert([$item]);

        // Delete cart after transaction
        Cart::where('users_id', auth()->user()->id)->delete();

        // Configuration        
        Config::$serverKey = config('services.midtrans.serverKey');        
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
        

        // Setup variable midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => 'LUX-' . $transaction->id,
                'gross_amount' => (int) $transaction->total_price
            ],
            'customer_details' => [
                'first_name'       => $transaction->name,
                'email'            => $transaction->email,
                'phone'            => $transaction->phone,
            ],
            'enabled_payments' => ['gopay','bank_transfer'],
            'vtweb' => []
        ];

        // Payment proccess
        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function success(Request $request)
    {
        return view('pages.frontend.success');
    }
}
