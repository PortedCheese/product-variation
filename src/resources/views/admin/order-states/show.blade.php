@extends("admin.layout")

@section("page-title", "{$state->title} - ")

@section('header-title', "{$state->title}")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Заказы</h5>
            </div>
            <div class="card-body">
                
            </div>
        </div>
    </div>
@endsection