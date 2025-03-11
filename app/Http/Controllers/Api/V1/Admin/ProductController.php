<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view products')->only('index', 'show');
        $this->middleware('permission:create products')->only('create', 'store');
        $this->middleware('permission:edit products')->only('edit', 'update');
        $this->middleware('permission:delete products')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $produit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $produit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produit)
    {
        //
    }
}
