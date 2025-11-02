<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Balance;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeBalance
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->getOrder();
        $user = $order->{Order::FIELD_USER};
        $balance = $user->{User::FIELD_BALANCE};

        $balance->decrement(Balance::FIELD_AMOUNT, $order->{Order::FIELD_COST});
    }
}
