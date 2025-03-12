<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!$request->user()->can('view dashboard')){

            return response()->json([
                'Error' => 'Unauthorized Sir Akhuya F7alk'
            ]);
        }
//        start
        $lowStockProducts = Product::where('stock', '<=', 10)->get();
        $admins = User::role('super_admin')->get();

        foreach ($lowStockProducts as $product) {
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }
//        end

        return response()->json([
            'Success' => 'Welcome to Dashboard'
        ]);
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


}
