@extends("admin.layout")

@section("page-title", "Статусы заказов - ")

@section('header-title', "Статусы заказов")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
            </div>
        </div>
    </div>
@endsection