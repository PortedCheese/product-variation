<div class="modal fade"
     id="orderInfo{{ $order->id }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="orderInfo{{ $order->id }}Label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderInfo{{ $order->id }}Label">Информация</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                @php($attr = \App\Order::getAttributesForRender())
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Аттрибут</th>
                            <th>Значение</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order->user_data as $key => $value)
                            @if (! empty($attr[$key]))
                                <tr>
                                    <td>{{ $attr[$key] }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>