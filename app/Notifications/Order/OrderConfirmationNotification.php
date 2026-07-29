<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class OrderConfirmationNotification extends BaseNotification
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    protected function buildMailMessage($notifiable): MailMessage
    {
        $itemsHtml = $this->buildOrderItemsHtml();

        return (new MailMessage)
            ->subject("Order Confirmation #{$this->order->order_number}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line(new HtmlString($itemsHtml))
            ->line('**Order Total:** ' . money($this->order->total))
            ->line('**Order Status:** ' . ucfirst($this->order->status))
            ->action('View Order', route('customer.orders.show', $this->order))
            ->line('Thank you for your order!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "Order Confirmation #{$this->order->order_number}",
            'message' => "Your order has been confirmed. Total: " . money($this->order->total),
            'type' => 'order_confirmation',
            'icon' => 'fa-shopping-cart',
            'color' => 'success',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total' => $this->order->total,
            'total_formatted' => money($this->order->total),
            'status' => $this->order->status,
            'action_url' => route('customer.orders.show', $this->order),
            'action_text' => 'View Order',
        ];
    }

    protected function buildOrderItemsHtml(): string
    {
        $html = '**Order Items:**' . "\n\n";
        
        foreach ($this->order->items as $item) {
            $html .= "- {$item->quantity}x {$item->product_name} - " . money($item->total) . "\n";
        }

        return $html;
    }
}