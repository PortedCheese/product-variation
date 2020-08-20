<?php


namespace PortedCheese\ProductVariation\Helpers;


use App\Order;
use App\OrderItem;
use App\OrderState;
use App\ProductVariation;

class OrderActionsManager
{
    /**
     * Добавить вариации к заказу.
     *
     * @param Order $order
     * @param array $variationsInfo
     */
    public function addVariationsToOrder(Order $order, array $variationsInfo)
    {
        $ids = array_keys($variationsInfo);
        $orderItems = OrderItem::query()
            ->where("order_id", $order->id)
            ->whereIn("variation_id", $ids)
            ->get();
        foreach ($orderItems as $orderItem) {
            /**
             * @var OrderItem $orderItem
             */
            $id = $orderItem->id;
            $quantity = $variationsInfo[$id];
            unset($variationsInfo[$id]);
            $this->increaseOrderItemQuantity($orderItem, $quantity);
        }

        foreach ($variationsInfo as $id => $quantity) {
            $this->addItemToOrder($order, $id, $quantity);
        }
    }

    /**
     * Увеличить количество позиции заказа.
     *
     * @param OrderItem $orderItem
     * @param int $quantity
     */
    public function increaseOrderItemQuantity(OrderItem $orderItem, int $quantity)
    {
        $orderItem->quantity += $quantity;
        $orderItem->save();
    }

    /**
     * Уменьшить количество позиции заказа.
     *
     * @param OrderItem $orderItem
     * @param int $quantity
     */
    public function decreaseOrderItemQuantity(OrderItem $orderItem, int $quantity)
    {
        if ($orderItem->quantity > $quantity) {
            $orderItem->quantity -= $quantity;
        }
        else {
            $orderItem->quantity = 0;
        }
        $orderItem->save();
    }

    /**
     * Добавить позицию в заказ.
     *
     * @param Order $order
     * @param $variation
     * @param int $quantity
     * @return bool|\Illuminate\Database\Eloquent\Model|OrderItem
     */
    public function addItemToOrder(Order $order, $variation, $quantity = 1)
    {
        if (is_numeric($variation)) {
            try {
                $variation = ProductVariation::findOrFail($variation);
            }
            catch (\Exception $exception) {
                return false;
            }
        }
        $productId = $variation->product_id;

        try {
            $orderItem = $order->items()->create([
                "sku" => $variation->sku,
                "price" => $variation->price,
                "quantity" => $quantity,
                "description" => $variation->description,
                "product_id" => $productId,
                "variation_id" => $variation->id,
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        return $orderItem;
    }

    /**
     * Получить статус заказа Новый
     *
     * @return OrderState
     */
    public function getNewState()
    {
        try {
            $state = OrderState::query()
                ->where("key", "new")
                ->firstOrFail();
        } catch (\Exception $exception) {
            $state = OrderState::create([
                "title" => "Новый",
                "slug" => "new",
                "key" => "new",
            ]);
        }
        return $state;
    }

    /**
     * Найти номер, которого еще нет.
     *
     * @param bool $letter
     * @param int $length
     * @return string
     */
    public function generateUniqueNumber($letter = true, $length = 8)
    {
       $pin = $this->generateRandomPin($letter, $length);
       while (Order::query()->select("id")->where("number", $pin)->count("id")) {
           $pin = $this->generateRandomPin($letter, $length);
       }
       return $pin;
    }

    /**
     * Генерация номера.
     *
     * @param $letter
     * @param $length
     * @return string
     */
    protected function generateRandomPin($letter, $length)
    {
        if ($letter) {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            return $characters[rand(0, strlen($characters) - 1)] . "-" . $this->generateRandomDigits($length);
        }
        else {
            return $this->generateRandomDigits($length);
        }
    }

    /**
     * Случайные числа.
     *
     * @param $count
     * @return string
     */
    protected function generateRandomDigits($count)
    {
        $number = "";
        for ($i = 1; $i <= $count; $i++) {
            $number .= mt_rand(0, 9);
        }
        return $number;
    }
}