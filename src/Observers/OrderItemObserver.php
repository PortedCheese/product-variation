<?php

namespace PortedCheese\ProductVariation\Observers;

use App\OrderItem;
use PortedCheese\ProductVariation\Models\ProductVariation;

class OrderItemObserver
{
    /**
     * Перед сохранением.
     *
     * @param OrderItem $orderItem
     */
    public function creating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
        $product = $orderItem->product;
        $orderItem->title = $product->title;
        $this->addMeasurementToItem($orderItem);
    }

    /**
     * Перед обновлением.
     *
     * @param OrderItem $orderItem
     */
    public function updating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
        $this->addMeasurementToItem($orderItem);
    }

    /**
     * Добавить измерение для вариации.
     *
     * @param OrderItem $orderItem
     */
    protected function addMeasurementToItem(OrderItem $orderItem)
    {
        $measurement = $this->getVariationShortMeasurement($orderItem->variation_id);

        if ($measurement) {
            $orderItem->measurement = $measurement;
        }
        else {
            $orderItem->measurement = null;
        }
    }

    /**
     * Измерение вариации.
     *
     * @param $variationId
     * @return string|bool
     */
    protected function getVariationShortMeasurement($variationId)
    {
        try {
            $variation = ProductVariation::query()
                ->select("measurement_id")
                ->where("id", $variationId)
                ->firstOrFail();
            return $variation->short_measurement;
        }
        catch (\Exception $exception) {
            return false;
        }
    }
}
