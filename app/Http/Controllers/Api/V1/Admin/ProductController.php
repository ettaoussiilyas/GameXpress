<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Notifications\LowStockNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view products')->only('index', 'show');
//        $this->middleware('permission:create products')->only('create', 'store');
//        $this->middleware('permission:edit products')->only('edit', 'update');
//        $this->middleware('permission:delete products')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('images')->get();
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
        try {
            $validated = $request->validate([
                'name' => 'required|unique:products|max:255',
                'slug' => 'required|unique:products|max:255',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|numeric|min:1',
                'category_id' => 'required|exists:categories,id',
                'images' => 'required|array|min:1',
                'primary_image' => 'required|integer|min:0'
            ]);

            DB::beginTransaction();

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
            ]);

            // Handle images - both CDN URLs and uploaded files
            foreach ($request->images as $index => $image) {
                $imageUrl = '';

                if (is_string($image)) {
                    // It's a CDN URL
                    $imageUrl = $image;
                } elseif ($image instanceof UploadedFile) {
                    // It's an uploaded file
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('products', $filename, 'public');
                    $imageUrl = 'storage/' . $path;
                }

                // Store image record
                $product->images()->create([
                    'image_url' => $imageUrl,
                    'is_primary' => ($index == $request->primary_image)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $product->load('images')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            // Load the product with its images
            return $product->load('images');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch product',
                'message' => $e->getMessage()
            ], 500);
        }
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
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|max:255|unique:products,name,' . $product->id,
                'slug' => 'sometimes|required|max:255|unique:products,slug,' . $product->id,
                'price' => 'sometimes|required|numeric|min:0',
                'stock' => 'sometimes|required|numeric|min:1',
                'category_id' => 'sometimes|required|exists:categories,id',
                'images' => 'sometimes|required|array|min:1',
                'primary_image' => 'required_with:images|integer|min:0'
            ]);

            DB::beginTransaction();

            // Update product
            $product->update([
                'name' => $validated['name'] ?? $product->name,
                'slug' => $validated['slug'] ?? $product->slug,
                'price' => $validated['price'] ?? $product->price,
                'stock' => $validated['stock'] ?? $product->stock,
                'category_id' => $validated['category_id'] ?? $product->category_id,
            ]);

            // handle images updated
            if (isset($request->images)) {
                // Delete old images from storage if they were uploaded files
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists(str_replace('storage/', '', $oldImage->image_url))) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $oldImage->image_url));
                    }
                }

                // delete old image records
                $product->images()->delete();

                // store new images
                foreach ($request->images as $index => $image) {
                    $imageUrl = '';

                    if (is_string($image)) {
                        // if a cdn
                        $imageUrl = $image;
                    } elseif ($image instanceof UploadedFile) {
                        // uploaded file
                        $filename = time() . '_' . $image->getClientOriginalName();
                        $path = $image->storeAs('products', $filename, 'public');
                        $imageUrl = 'storage/' . $path;
                    }

                    // Store image record
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'is_primary' => ($index == $request->primary_image)
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $product->load('images')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete images from storage if they were uploaded files
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists(str_replace('storage/', '', $image->image_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $image->image_url));
                }
            }

            // Delete the product (this will also delete related images due to cascade)
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkLowStock(){

        $lowStockProducts = Product::where('stock', '<=', 10)->get();
//        $admins = User::role('super_admin')->get();
        $admins = User::role(['super_admin', 'product_manager'])->get();

//        $admins = User::whereHas('roles', function ($query) {
//            $query->where('name', [
//                'super_admin',
//                'product_manager'
//            ]);
//        })->get();

        foreach ($lowStockProducts as $product) {
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }
    }

}
