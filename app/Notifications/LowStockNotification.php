<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(private Product $product)
    {
        $this->product = $product;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Critical Stock Alert')
            ->line("Product {$this->product->name} is running low on stock!")
            ->line("Current stock level: {$this->product->stock}")
            ->action('View Product', url("/api/v1/admin/products/{$this->product->id}"));
    }
}
