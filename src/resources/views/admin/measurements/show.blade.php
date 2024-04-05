@extends("admin.layout")

@section("page-title", "{$measurement->title} - ")

@section('header-title', "{$measurement->title}")

@section('admin')
    @include("product-variation::admin.measurements.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Измерение
            </div>
            <div class="card-body">
                {{ $measurement->title }}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Краткая запись
            </div>
            <div class="card-body">
                {{ $measurement->short }}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Slug
            </div>
            <div class="card-body">
                {{ $measurement->slug }}
            </div>
        </div>
    </div>
@endsection