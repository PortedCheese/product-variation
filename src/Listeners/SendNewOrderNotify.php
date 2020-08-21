<?php

namespace PortedCheese\ProductVariation\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use PortedCheese\ProductVariation\Events\CreateNewOrder;
use PortedCheese\ProductVariation\Notifications\NewOrderClient;
use PortedCheese\ProductVariation\Notifications\NewOrderUser;

class SendNewOrderNotify
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateNewOrder $event)
    {
        $order = $event->order;

        $clientEmail = config("product-variation.clientNotifyEmail");
        if ($clientEmail && config("product-variation.enableClientNotification")) {
            $order->notify(new NewOrderClient($order));
        }

        $userEmail = $order->user_email;
        if ($userEmail && config("product-variation.enableUserNotification")) {
            Notification::route("mail", $userEmail)
                ->notify(new NewOrderUser($order));
        }
    }
}
