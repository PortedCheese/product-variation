<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{{ route("admin.orders.index") }}"
                       class="nav-link{{ $currentRoute === "admin.orders.index" ? " active" : "" }}">
                        Список
                    </a>
                </li>

                @if (! empty($order))
                    <li class="nav-item">
                        <a href="{{ route("admin.orders.show", ["order" => $order]) }}"
                           class="nav-link{{ $currentRoute === "admin.orders.show" ? " active" : "" }}">
                            Просмотр
                        </a>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="btn btn-link nav-link"
                                data-confirm="{{ "delete-form-order-{$order->id}" }}">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                        <confirm-form :id="'{{ "delete-form-order-{$order->id}" }}'">
                            <template>
                                <form action="{{ route('admin.orders.destroy', ['order' => $order]) }}"
                                      id="delete-form-order-{{ $order->id }}"
                                      class="btn-group"
                                      method="post">
                                    @csrf
                                    @method("delete")
                                </form>
                            </template>
                        </confirm-form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>