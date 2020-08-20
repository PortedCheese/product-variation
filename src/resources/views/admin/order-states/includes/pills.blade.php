<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{{ route("admin.order-states.index") }}"
                       class="nav-link{{ $currentRoute === "admin.order-states.index" ? " active" : "" }}">
                        Список
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route("admin.order-states.create") }}"
                       class="nav-link{{ $currentRoute === "admin.order-states.create" ? " active" : "" }}">
                        Добавить
                    </a>
                </li>

                @if (! empty($state))
                    <li class="nav-item">
                        <a href="{{ route("admin.order-states.show", ["state" => $state]) }}"
                           class="nav-link{{ $currentRoute === "admin.order-states.show" ? " active" : "" }}">
                            Просмотр
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route("admin.order-states.edit", ["state" => $state]) }}"
                           class="nav-link{{ $currentRoute === "admin.order-states.edit" ? " active" : "" }}">
                            Редактировать
                        </a>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="btn btn-link nav-link"
                                data-confirm="{{ "delete-form-state-{$state->id}" }}">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                        <confirm-form :id="'{{ "delete-form-state-{$state->id}" }}'">
                            <template>
                                <form action="{{ route('admin.order-states.destroy', ['state' => $state]) }}"
                                      id="delete-form-state-{{ $state->id }}"
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