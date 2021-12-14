<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
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
            
            $query = ProductGallery::query();
            
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
                            <form class="inline-block" method="post" action="' . route('dashboard.product.gallery.destroy', $item->id) . '">
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        $a = 'ANCOK';
        return $a;
    }
}
