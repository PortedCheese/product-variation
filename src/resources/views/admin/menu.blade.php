@php
    $active = (strstr($currentRoute, ".order-states.") !== false) ||
              (strstr($currentRoute, ".orders.") !== false);
@endphp

@if ($theme == "sb-admin")
    <li class="nav-item {{ $active ? " active" : "" }}">
        <a href="#"
           class="nav-link"
           data-bs-toggle="collapse"
           data-bs-target="#collapse-orders-menu"
           aria-controls="#collapse-orders-menu"
           aria-expanded="{{ $active ? "true" : "false" }}">
            <i class="fas fa-cart-arrow-down"></i>
            <span>Заказы</span>
        </a>

        <div id="collapse-orders-menu"
             class="collapse"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can("viewAny", \App\OrderState::class)
                    <a href="{{ route("admin.order-states.index") }}"
                       class="collapse-item{{ strstr($currentRoute, ".order-states.") !== false ? " active" : "" }}">
                        <span>Статусы заказов</span>
                    </a>
                @endcan
                @can("viewAny", \App\Order::class)
                    <a href="{{ route("admin.orders.index") }}"
                       class="collapse-item{{ strstr($currentRoute, ".orders.") !== false ? " active" : "" }}">
                        <span>Заказы</span>
                    </a>
                @endcan
            </div>
        </div>
    </li>
@else
    <li class="nav-item dropdown">
        <a href="#"
           class="nav-link dropdown-toggle{{ $active ? " active" : "" }}"
           role="button"
           id="orders-menu"
           data-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false">
            <i class="fas fa-cart-arrow-down"></i>
            Заказы
        </a>

        <div class="dropdown-menu" aria-labelledby="orders-menu">
            @can("viewAny", \App\OrderState::class)
                <a href="{{ route("admin.order-states.index") }}"
                   class="dropdown-item">
                    Статусы заказов
                </a>
            @endcan
            @can("viewAny", \App\OrderState::class)
                <a href="{{ route("admin.orders.index") }}"
                   class="dropdown-item">
                    Заказы
                </a>
            @endcan
        </div>
    </li>
@endif