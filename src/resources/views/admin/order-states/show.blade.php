@extends("admin.layout")

@section("page-title", "{$state->title} - ")

@section('header-title', "{$state->title}")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card-columns">
            @foreach ($orders as $item)
                @can("view", $item)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <a href="{{ route("admin.orders.show", ["order" => $item]) }}">
                                    {{ $item->number }}
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div>{{ $item->created_human }}</div>
                            <div>{{ $item->user_name }}</div>
                            <div>{{ $item->total }}</div>
                        </div>
                    </div>
                @endcan
            @endforeach
        </div>
    </div>
    @if ($orders->lastPage() > 1)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
@endsection