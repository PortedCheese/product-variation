<?php


namespace PortedCheese\ProductVariation\Helpers;


use App\Order;
use App\OrderItem;
use App\OrderItemSet;
use App\OrderState;
use App\ProductVariation;

class OrderActionsManager
{
    /**
     * Пересчитать сумму заказа.
     *
     * @param Order $order
     */
    public function recalculateOrderTotal(Order $order)
    {
        $total = 0;
        $items = $order->items()->select("total")->get();
        foreach ($items as $item) {
            $total += $item->total;
        }
        $order->total = $total;
        $order->save();
    }
    /**
     * Добавить вариации к заказу.
     *
     * @param Order $order
     * @param array $variationsInfo
     */
    public function addVariationsToOrder(Order $order, array $variationsInfo)
    {
//        $ids = array_keys($variationsInfo);
//        $orderItems = OrderItem::query()
//            ->where("order_id", $order->id)
//            ->whereIn("variation_id", $ids)
//            ->get();
//        foreach ($orderItems as $orderItem) {
//            /**
//             * @var OrderItem $orderItem
//             */
//            $id = $orderItem->id;
//            $quantity = $variationsInfo[$id];
//            unset($variationsInfo[$id]);
//            $this->increaseOrderItemQuantity($orderItem, $quantity);
//        }

        foreach ($variationsInfo as $id => $quantity) {
            $this->addItemToOrder($order, $id, $quantity);
        }

        $this->recalculateOrderTotal($order);
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
     * @param OrderItemSet $orderItemSet
     * @return bool|\Illuminate\Database\Eloquent\Model|OrderItem
     */
    public function addItemToOrder(Order $order, $variation, int $quantity = 1, $itemSet = null) : OrderItem|bool
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
        // характеристики
        $specifications = null;
        if ($variation->specifications){
            foreach ($variation->specificationsArray as $key => $item){
                $specifications[$item->title] = $item->value;
            }
        }

        try {
            $orderItem = $order->items()->create([
                "sku" => $variation->sku,
                "price" => $variation->price,
                "quantity" => $quantity,
                "description" => $variation->description,
                "product_id" => $productId,
                "variation_id" => $variation->id,
                "specifications" =>  json_encode($specifications)
            ]);
            if (isset($itemSet)){
                $orderItem->orderItemSet()->associate($itemSet);
                $orderItem->save();
            }
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

    /**
     * Добавить дополнения к заказу.
     *
     * @param Order $order
     * @param array $addonVariationSetsInfo
     * @return void
     */
    public function addAddonVariationSetsToOrder(Order $order, array $addonVariationSetsInfo)
    {
        $orderItems = $order->items()->whereNull('order_item_set_id')->get();

        foreach ($orderItems as $orderItem){
            if (isset($addonVariationSetsInfo[$orderItem->variation_id])){
                foreach ($addonVariationSetsInfo[$orderItem->variation_id] as $key => $set){
                    // создать сет и добавить дополнения

                    $itemSet = $this->createAddonSet($orderItem);
                    foreach ($set as $variation => $quantity){
                        $this->addItemToOrder($order, $variation, $quantity, $itemSet);
                    }
                    $this->recalculateOrderTotal($order);
                }
            }
        }
    }

    /**
     * Создать заказ по корзине
     *
     * @param Order $order
     * @param array $cartInfo
     * @return void
     */
    public function makeOrderFromCart(Order $order, array $cartInfo){
        foreach ($cartInfo as $cartItem){
            $orderItem = $this->addItemToOrder($order, $cartItem->variation->id, $cartItem->quantity);
            if (count($cartItem->addons)){
                $orderItemSet = $this->createAddonSet($orderItem);
                foreach ($cartItem->addons as $addonData){
                    $this->addItemToOrder($order, $addonData->variation, $addonData->quantity, $orderItemSet);
                }
            }
        }
        $this->recalculateOrderTotal($order);
    }

    /**
     * Создать сеть дополнений
     *
     * @param OrderItem $orderItem
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    protected function createAddonSet( OrderItem $orderItem){
        try {
            $set = $orderItem->orderItemSets()->create([ "variation_id" => $orderItem->variation->id]);
            return $set;
        }
        catch (\Exception $exception) {
            return false;
        }

    }
}