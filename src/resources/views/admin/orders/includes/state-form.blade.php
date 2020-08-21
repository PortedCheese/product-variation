@can("update", $order)
    <form action="{{ route('admin.orders.update', ['order' => $order]) }}"
          method="post"
          style="width: 150px">
        @csrf
        @method('put')

        <div class="input-group">
            <select name="state"
                    id="parent"
                    aria-label="Статус"
                    class="custom-select{{ $errors->has('state') ? ' is-invalid' : '' }}">
                @foreach($states as $state)
                    <option value="{{ $state->id }}"{{ $order->state_id == $state->id ? " selected" : "" }}>
                        {{ $state->title }}
                    </option>
                @endforeach
            </select>
            @error("state")
            <div class="invalid-feedback order-last" role="alert">
                {{ $message }}
            </div>
            @enderror
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-success">
                    <i class="far fa-save"></i>
                </button>
            </div>
        </div>
    </form>
@else
    {{ $order->state->title }}
@endcan