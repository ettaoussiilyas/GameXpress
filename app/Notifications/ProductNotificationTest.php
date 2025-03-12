<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProductNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_notification_is_sent()
    {
        Notification::fake();

        // Create a super admin user
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        // Create a product with low stock
        $product = Product::factory()->create(['stock' => 5]);

        // Update product to trigger observer
        $product->stock = 3;
        $product->save();

        Notification::assertSentTo(
            $admin,
            LowStockNotification::class,
            function ($notification) use ($product) {
                return $notification->product->id === $product->id;
            }
        );
    }
}