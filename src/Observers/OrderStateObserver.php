<?php

namespace PortedCheese\ProductVariation\Observers;

use App\OrderState;

class OrderStateObserver
{
    /**
     * Перед сохранением.
     *
     * @param OrderState $state
     */
    public function creating(OrderState $state)
    {
        $state->fixKey();
    }

    /**
     * Перед обновлением.
     *
     * @param OrderState $state
     */
    public function updating(OrderState $state)
    {
        $state->fixKey(true);
    }
}
