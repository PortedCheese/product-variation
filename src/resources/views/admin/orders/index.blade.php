@extends("admin.layout")

@section("page-title", "Заказы - ")

@section('header-title', "Заказы")

@section('admin')
    @include("product-variation::admin.orders.includes.pills")
    <div class="col-12 mb-2">
        <div class="card">
            <div class="card-header">
                <form class="d-inline d-xxl-flex" method="get">
                    <label class="sr-only" for="number">Номер</label>
                    <input type="text"
                           class="form-control mb-2 mr-sm-2"
                           name="number"
                           value="{{ $request->get('number', "") }}"
                           id="number"
                           placeholder="Номер">

                    <label class="sr-only" for="email">E-mail</label>
                    <input type="text"
                           class="form-control mb-2 mr-sm-2"
                           name="email"
                           value="{{ $request->has('email', "") }}"
                           id="email"
                           placeholder="E-mail">

                    <label for="state" class="sr-only">Статус</label>
                    <select name="state"
                            id="state"
                            class="custom-select mb-2 mr-sm-2">
                        <option value=""{{ $request->has('state') ? '' : ' selected' }}>-- Статус --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}"{{ $request->get('state') == $state->id ? ' selected' : '' }}>
                                {{ $state->title }}
                            </option>
                        @endforeach
                    </select>

                    <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Создан</span>
                        </div>
                        <input type="date"
                               value="{{ $request->get('from', '') }}"
                               aria-label="Дата от"
                               class="form-control"
                               name="from">
                        <input type="date"
                               value="{{ $request->get('to', '') }}"
                               aria-label="Дата до"
                               class="form-control"
                               name="to">
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Искать</button>
                    <a href="{{ route($currentRoute) }}" class="btn btn-link mb-2">Сбросить</a>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>Имя</th>
                            <th>E-mail</th>
                            <th>Телефон</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                            <th>Создан</th>
                            @canany(["view", "delete"], \App\Order::class)
                                <th>Действия</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $item)
                            <tr>
                                <td>{{ $item->number }}</td>
                                <td>{{ $item->user_name }}</td>
                                <td>
                                    @if ($item->user_email)
                                        <a href="mailto:{{ $item->user_email }}">{{ $item->user_email }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->user_phone)
                                        <a href="tel:{{ $item->user_phone }}">{{ $item->user_phone }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @include("product-variation::admin.orders.includes.state-form", ["order" => $item, "states" => $states])
                                </td>
                                <td>{{ $item->total }}</td>
                                <td>{{ $item->created_human }}</td>
                                @canany(["view", "delete"], $item)
                                    <td>
                                        <div role="toolbar" class="btn-toolbar">
                                            <div class="btn-group mr-1">
                                                @can("view", $item)
                                                    <button type="button"
                                                            class="btn btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#orderInfo{{ $item->id }}">
                                                        <i class="fas fa-info"></i>
                                                    </button>
                                                    <a href="{{ route('admin.orders.show', ['order' => $item]) }}" class="btn btn-dark">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can("delete", $item)
                                                    <button type="button" class="btn btn-danger" data-confirm="{{ "delete-order-form-{$item->id}" }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                        @can("delete", $item)
                                            <confirm-form :id="'{{ "delete-order-form-{$item->id}" }}'">
                                                <template>
                                                    <form action="{{ route('admin.orders.destroy', ['order' => $item]) }}"
                                                          id="delete-order-form-{{ $item->id }}"
                                                          class="btn-group"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                        @can("view", $item)
                                            @include("product-variation::admin.orders.includes.user-info-modal", ['order' => $item])
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($orders->lastPage() > 1)
        <div class="col-12 mb-2">
            <div class="card">
                <div class="card-body">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
@endsection