@extends("admin.layout")

@section("page-title", "Редактировать {$measurement->id} - ")

@section('header-title', "Редактировать {$measurement->id}")

@section('admin')
    @include("product-variation::admin.measurements.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.measurements.update", ["measurement" => $measurement]) }}" method="post">
                    @csrf
                    @method("put")

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old("title", $measurement->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short">Краткая запись <span class="text-danger">*</span></label>
                        <input type="text"
                               id="short"
                               name="short"
                               required
                               value="{{ old("short", $measurement->short) }}"
                               class="form-control @error("short") is-invalid @enderror">
                        @error("short")
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
                               value="{{ old("slug", $measurement->slug) }}"
                               class="form-control @error("slug") is-invalid @enderror">
                        @error("slug")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection