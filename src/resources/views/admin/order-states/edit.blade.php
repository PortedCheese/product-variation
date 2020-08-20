@extends("admin.layout")

@section("page-title", "Редактировать {$state->id} - ")

@section('header-title', "Редактировать {$state->id}")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.order-states.update", ["state" => $state]) }}" method="post">
                    @csrf
                    @method("put")

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old("title", $state->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Адресная строка</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old("slug", $state->slug) }}"
                               class="form-control @error("slug") is-invalid @enderror">
                        @error("slug")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @can("management")
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text"
                                   id="key"
                                   name="key"
                                   value="{{ old("key", $state->key) }}"
                                   class="form-control @error("key") is-invalid @enderror">
                            @error("key")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endcan

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection