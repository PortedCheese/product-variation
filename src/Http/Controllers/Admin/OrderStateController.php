<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OrderState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view("product-variation::admin.order-states.index", compact("request"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("product-variation::admin.order-states.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());
        $state = OrderState::create($request->all());
        return redirect()
            ->route("admin.order-states.show", ["state" => $state])
            ->with("success", "Статус добавлен");
    }

    /**
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "max:100", "unique:order_states,title"],
            "slug" => ["nullable", "max:100", "unique:order_states,slug"],
            "key" => ["nullable", "max:100", "unique:order_states,key"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
        ])->validate();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderState  $state
     * @return \Illuminate\Http\Response
     */
    public function show(OrderState $state)
    {
        return view("product-variation::admin.order-states.show", compact("state"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderState  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderState $state)
    {
        return view("product-variation::admin.order-states.edit", compact("state"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderState  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderState $state)
    {
        $this->updateValidator($request->all(), $state);
        $state->update($request->all());
        return redirect()
            ->route("admin.order-states.show", ["state" => $state])
            ->with("success", "Статус успешно обновлен");
    }

    protected function updateValidator($data, OrderState $state)
    {
        $id = $state->id;
        Validator::make($data, [
            "title" => ["required", "max:100", "unique:order_states,title,{$id}"],
            "slug" => ["nullable", "max:100", "unique:order_states,slug,{$id}"],
            "key" => ["nullable", "max:100", "unique:order_states,key,{$id}"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OrderState $state
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(OrderState $state)
    {
        $state->delete();
        return redirect()
            ->route("admin.order-states.index")
            ->with("success", "Статус удален");
    }
}
