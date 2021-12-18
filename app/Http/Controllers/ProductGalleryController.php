<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductGalleryRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if ( request()->ajax() ){
            
            $query = ProductGallery::query()->where('products_id', $product->id);
            
            return DataTables::of( $query )                    
                    ->editColumn( 'url', function($item) {
                        return '
                            <img style="max-width:100px;" src="' . Storage::url($item->url) . '" />
                        ';
                    })
                    ->editColumn( 'is_featured', function($item) {
                        return $item->is_featured ? 'Yes' : 'No' ;
                    })
                    ->addColumn('action', function($item) {                        
                        return '
                            <form class="inline-block" method="post" action="' . route('dashboard.gallery.destroy', $item->id) . '">
                                ' . csrf_field('delete') . method_field('delete') . '
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded shadow-lg border-0 ml-1">
                                    Delete    
                                </button>
                            </form>
                        ';
                    })
                    ->rawColumns(['action', 'url'])
                    ->make();
        }

        return view('pages.dashboard.gallery.index', ['product' => $product]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Product $product )
    {        
        return view('pages.dashboard.gallery.create', ['product' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductGalleryRequest $request, Product $product)
    {
        $files = $request->file('files');
        
        if ( $request->hasFile('files') ){            
            foreach ($files as $file => $value) {                
                $data[] = [
                    'products_id' => $product->id,
                    'url' => $value->store('public/product/gallery'),
                    'is_featured' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];                 
            }                  
            DB::table('product_galleries')->insert($data);
        }
        return redirect()->route('dashboard.product.gallery.index', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductGallery $gallery)
    {
        $gallery->delete();

        return redirect()->route('dashboard.product.gallery.index', $gallery->products_id);
    }
}
