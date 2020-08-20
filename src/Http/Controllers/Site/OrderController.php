<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Order;
use App\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PortedCheese\ProductVariation\Events\CreateNewOrder;

class OrderController extends Controller
{
    public function newSingle(Request $request, ProductVariation $variation)
    {
        $this->newSingleValidator($request->all());
        $order = Order::create([
            "user_data" => $request->all(),
        ]);
        // TODO: Add variation
        event(new CreateNewOrder($order));
        return response()
            ->json([
                "success" => true,
                "message" => "Ваш заказ № {$order->number} оформлен",
            ]);
    }

    protected function newSingleValidator(array $data)
    {
        Validator::make($data, [
            "name" => ["required", "max:250"],
            "email" => ["nullable", "required_without:phone", "email", "max:250"],
            "phone" => ["nullable", "required_without:email", "max:250"],
        ], [
            'name.required' => 'Поле :attribute обязательно для заполнения',
            'name.max' => "Поле :attribute должно быть максимум :min символа",
            'email.required_without' => "Поле :attribute обязательно когда :values не заполнено.",
            'email.email' => "Поле :attribute должно быть валидным e-mail адресом",
            'phone.required_without' => "Поле :attribute обязательно когда :values не заполнено.",
        ], [
            "name" => "Имя",
            "email" => "E-mail",
            "phone" => "Телефон",
        ])->validate();
    }
}
