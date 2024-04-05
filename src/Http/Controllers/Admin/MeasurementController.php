<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $measurements = Measurement::query()
            ->orderBy("title")
            ->get();
        return view("product-variation::admin.measurements.index", compact("request", "measurements"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("product-variation::admin.measurements.create");
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
        $measurement = Measurement::create($request->all());
        return redirect()
            ->route("admin.measurements.show", ["measurement" => $measurement])
            ->with("success", "Измерение добавлено");
    }

    /**
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "max:100", "unique:measurements,title"],
            "slug" => ["nullable", "max:100", "unique:measurements,slug"],
            "short" => ["nullable", "max:100", "unique:measurements,short"],
        ], [], [
            "title" => "Измереник",
            "slug" => "Адресная строка",
            "short" => "Краткая запись",
        ])->validate();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Measurement $measurement)
    {
        return view("product-variation::admin.measurements.show", compact("measurement"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function edit(Measurement $measurement)
    {
        return view("product-variation::admin.measurements.edit", compact("measurement"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Measurement $measurement)
    {
        $this->updateValidator($request->all(), $measurement);
        $measurement->update($request->all());

        return redirect()
            ->route("admin.measurements.show", ["measurement" => $measurement])
            ->with("success", "Измерение успешно обновлено");
    }

    protected function updateValidator($data, Measurement $measurement)
    {
        $id = $measurement->id;
        Validator::make($data, [
            "title" => ["required", "max:100", "unique:measurements,title,{$id}"],
            "slug" => ["nullable", "max:100", "unique:measurements,slug,{$id}"],
            "short" => ["nullable", "max:100", "unique:measurements,short,{$id}"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
            "short" => "Краткая запись",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Measurement $measurement
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Measurement $measurement)
    {
        if (count($measurement->variations) > 0)
            return redirect()
                ->route("admin.measurements.index")
                ->with("danger", "Измерение не может быть удалено: есть вариации");

        $measurement->delete();
        return redirect()
            ->route("admin.measurements.index")
            ->with("success", "Измерение удалено");
    }
}
