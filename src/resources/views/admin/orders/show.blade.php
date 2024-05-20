@extends("admin.layout")

@section("page-title", "{$order->number} - ")

@section('header-title', "{$order->number}")

@section('admin')
    @include("product-variation::admin.orders.includes.pills")

    @include("product-variation::admin.orders.includes.user-info-modal", ['order' => $order])

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                @include("product-variation::admin.orders.includes.state-form", ["order" => $order, "states" => $states])

                <button type="button"
                        class="btn btn-info"
                        data-toggle="modal"
                        data-target="#orderInfo{{ $order->id }}">
                    <i class="fas fa-info"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Артикул</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Итого</th>
                            <th>Описание</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <a href="{{ route("admin.products.show", ["product" => $item->product]) }}" target="_blank">
                                        {{ $item->product->title }}
                                    </a>
                                    <br>
                                    @if ($item->variation->image)
                                        @pic([
                                        "image" => $item->variation->image,
                                        "template" => "small",
                                        "imgClass" => "img-thumbnail",
                                        "grid" => [
                                        "product-show-thumb" => 992,
                                        ],
                                        ])
                                    @elseif ($item->product->cover)
                                        @pic([
                                        "image" => $item->product->cover,
                                        "template" => "small",
                                        "imgClass" => "img-thumbnail",
                                        "grid" => [
                                        "product-show-thumb" => 992,
                                        ],
                                        ])
                                    @endif
                                    @isset($item->specificationsArray)
                                        @foreach($item->specificationsArray as $spec => $value)
                                            <small class="text-muted mr-2">{{ $spec }}: {{ $value }}</small>
                                        @endforeach
                                    @endisset
                                </td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->quantity }} {{ $item->short_measurement }}</td>
                                <td>{{ $item->total }}</td>
                                <td>{{ $item->description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <td colspan="4"></td>
                                <td>{{ $order->total }}</td>
                                <td></td>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection