<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CartItemAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public $cartItem;

    public function __construct($cartItem)
    {
        $this->cartItem = $cartItem;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "एउटा आइटम कार्टमा थपियो: {$this->cartItem->menu->name}",
            'link' => route('admin.dashboard')
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("एउटा आइटम कार्टमा थपियो: {$this->cartItem->menu->name}")
                    ->action('ड्यासबोर्डमा जानुहोस्', route('admin.dashboard'));
    }
}
