<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{{ route("admin.measurements.index") }}"
                       class="nav-link{{ $currentRoute === "admin.measurements.index" ? " active" : "" }}">
                        Список
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route("admin.measurements.create") }}"
                       class="nav-link{{ $currentRoute === "admin.measurements.create" ? " active" : "" }}">
                        Добавить
                    </a>
                </li>

                @if (! empty($measurement))
                    <li class="nav-item">
                        <a href="{{ route("admin.measurements.show", ["measurement" => $measurement]) }}"
                           class="nav-link{{ $currentRoute === "admin.measurements.show" ? " active" : "" }}">
                            Просмотр
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route("admin.measurements.edit", ["measurement" => $measurement]) }}"
                           class="nav-link{{ $currentRoute === "admin.measurements.edit" ? " active" : "" }}">
                            Редактировать
                        </a>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="btn btn-link nav-link"
                                data-confirm="{{ "delete-form-measurement-{$measurement->id}" }}">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                        <confirm-form :id="'{{ "delete-form-measurement-{$measurement->id}" }}'">
                            <template>
                                <form action="{{ route('admin.measurements.destroy', ['measurement' => $measurement]) }}"
                                      id="delete-form-measurement-{{ $measurement->id }}"
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