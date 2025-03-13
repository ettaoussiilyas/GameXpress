<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Categorie::all();
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
        $validated =  $request->validate ([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:225',
        ]);

        try {
            Categorie::create($validated);
            return response()->json(['sucsess' => 'Categorie Created Successfully']);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(Categorie $category)
    {
        try {
            // For debugging
            Log::info('Categorie Data:', ['categorie' => $category->toArray()]);

            return response()->json([
                'success' => true,
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to show this category',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $category)
    {
//        $category = $category->toArray();
//
//        return viw('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $category)
    {
        $validated =  $request->validate ([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:225',
        ]);
        try {
            $category->update($validated);
            return response()->json(['sucsess' => 'Categorie Updated Successfully']);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $category)  // parameter name should match route model binding
    {
        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete category',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
