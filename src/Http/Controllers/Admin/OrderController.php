<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Order::class, "order");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = Order::query()
            ->with("state");
        if ($number = $request->get("number")) {
            $collection->where("number", "like", "%$number%");
        }
        if ($email = $request->get("email")) {
            $collection->where("user_data->email", "like", "%$email%");
        }
        if ($state = $request->get("state")) {
            $collection->where("state_id", $state);
        }
        if ($from = $request->get("from")) {
            $collection->where("created_at", ">=", datehelper()->forFilter($from));
        }
        if ($to = $request->get("to")) {
            $collection->where("created_at", "<=", datehelper()->forFilter($to, true));
        }
        $orders = $collection->orderByDesc("created_at")
            ->paginate()
            ->appends($request->input());

        $states = OrderState::query()
            ->select("id", "title")
            ->orderBy("title")
            ->get();

        return view("product-variation::admin.orders.index", compact("request", "orders", "states"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $items = $order->items()->with("product")->get();

        $states = OrderState::query()
            ->select("id", "title")
            ->orderBy("title")
            ->get();

        return view("product-variation::admin.orders.show", compact("order", "items", "states"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $this->updateValidator($request->all(), $order);

        $state = $request->get("state");
        if ($order->state_id !== $state) {
            $order->state_id = $state;
            $order->save();
        }

        return redirect()
            ->back()
            ->with("success", "Статус изменен");
    }

    /**
     * @param array $data
     * @param Order $order
     */
    protected function updateValidator(array $data, Order $order)
    {
        Validator::make($data, [
            "state" => ["required", "exists:order_states,id"],
        ], [], [
            "state" => "Статус",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()
            ->route("admin.orders.index")
            ->with("success", "Заказ удален");
    }
}
