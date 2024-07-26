<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Order;
use App\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PortedCheese\ProductVariation\Events\CreateNewOrder;
use PortedCheese\ProductVariation\Facades\OrderActions;

class OrderController extends Controller
{
    /**
     * Заказ одного товара.
     *
     * @param Request $request
     * @param ProductVariation $variation
     * @return \Illuminate\Http\JsonResponse
     */
    public function newSingle(Request $request, ProductVariation $variation)
    {
        $this->newSingleValidator($request->all());
        $order = Order::create([
            "user_data" => $request->all(),
        ]);
        OrderActions::addVariationsToOrder($order, [
            $variation->id => 1
        ]);
        if ($addons = $this->getAddonsById($request->get("addons"))) {
            $addonsSets = [];
            foreach ($addons as $item){
                $addonsSets[$variation->id][0][$item->id] = 1;
            }
            OrderActions::addAddonVariationSetsToOrder($order, $addonsSets);
        }
        event(new CreateNewOrder($order));
        return response()
            ->json([
                "success" => true,
                "message" => "Ваш заказ № {$order->number} оформлен",
            ]);
    }

    /**
     *
     * @param array $addonIds
     * @return false|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getAddonsById(Array $addonIds){
        if (! count($addonIds)) return false;
        $addons = ProductVariation::query()->select('id')->whereIn('id',$addonIds)->get();
        if (count($addons) !== count($addonIds)) return false;
        return  $addons;
    }

    /**
     * @param array $data
     */
    protected function newSingleValidator(array $data)
    {
        Validator::make($data, [
            "name" => ["required", "max:250"],
            "email" => ["nullable", "required_without:phone", "email", "max:250"],
            "phone" => ["nullable", "required_without:email", "max:250"],
            "addons" => ["nullable", "array"],
            "addons.*" => ["numeric"],
            "privacy_policy" => ["accepted"],
        ], [
            'name.required' => 'Поле :attribute обязательно для заполнения',
            'name.max' => "Поле :attribute должно быть максимум :min символа",
            'email.required_without' => "Поле :attribute обязательно когда :values не заполнено.",
            'email.email' => "Поле :attribute должно быть валидным e-mail адресом",
            'phone.required_without' => "Поле :attribute обязательно когда :values не заполнено.",
            "privacy_policy.accepted" => "Требуется согласие с политикой конфиденциальности",
            "addons.numeric" => "Ошибка передачи доплнений",
        ], [
            "name" => "Имя",
            "email" => "E-mail",
            "phone" => "Телефон",
            "addons" => "Дополнения",
        ])->validate();
    }
}
