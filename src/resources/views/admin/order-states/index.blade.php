@extends("admin.layout")

@section("page-title", "Статусы заказов - ")

@section('header-title', "Статусы заказов")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Адресная строка</th>
                            @can("settings-management")
                                <th>Ключ</th>
                            @endcan
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($states as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->slug }}</td>
                                @can("settings-management")
                                    <td>{{ $item->key }}</td>
                                @endcan
                                <td>
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group mr-1">
                                            <a href="{{ route("admin.order-states.edit", ["state" => $item]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.order-states.show', ['state' => $item]) }}" class="btn btn-dark">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$item->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <confirm-form :id="'{{ "delete-form-{$item->id}" }}'">
                                        <template>
                                            <form action="{{ route('admin.order-states.destroy', ['state' => $item]) }}"
                                                  id="delete-form-{{ $item->id }}"
                                                  class="btn-group"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                        </template>
                                    </confirm-form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection