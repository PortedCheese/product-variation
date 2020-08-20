<?php


namespace PortedCheese\ProductVariation\Helpers;


use App\Order;
use App\OrderState;

class OrderActionsManager
{
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