<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;

class ProductObserver
{
    private const STOCK_THRESHOLD = 10;

    public function updated(Product $product): void
    {
        if ($product->stock <= self::STOCK_THRESHOLD) {
            // Notify all admin users
            User::role('super_admin')->each(function ($admin) use ($product) {
                $admin->notify(new LowStockNotification($product));
            });
        }
    }
}
