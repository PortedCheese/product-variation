@extends("admin.layout")

@section("page-title", "Добавить статус - ")

@section('header-title', "Добавить статус")

@section('admin')
    @include("product-variation::admin.order-states.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.order-states.store") }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old('title') }}"
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
                               value="{{ old('slug') }}"
                               class="form-control @error("slug") is-invalid @enderror">
                        @error("slug")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @can("settings-management")
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text"
                                   id="key"
                                   name="key"
                                   value="{{ old('key') }}"
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
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection