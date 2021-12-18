<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        return view('pages.frontend.cart');
    }

    public function success(Request $request)
    {
        return view('pages.frontend.success');
    }
}
