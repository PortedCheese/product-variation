@component('mail::message')
# Здравствуйте!

На сайте был оформлен заказ {{ $order->number }} на сумму {{ $order->total }} рублей.

@component('mail::table')
| Товар | Количество | Сумма |
| :--- | --------: | ---: |
@foreach ($items as $item)
| {{ $item->title }}: {{ $item->description }}. @isset($item->specificationsArray)@foreach($item->specificationsArray as $key => $value) {{ $key }} - {{ $value }}.  @endforeach @endisset | {{ $item->quantity }}  {{ $item->short_measurement }} | {{ $item->total }} |
@foreach($item->orderItemSets as $set)
@foreach($set->addons as $addon)
| - {{ $addon->product->title }}: {{ $addon->description }}. @isset($addon->specificationsArray) @foreach($addon->specificationsArray as $key => $value) {{ $key }} - {{ $value }}.  @endforeach @endisset | {{ $addon->quantity }} {{ $addon->short_measurement }} | {{ $addon->total }} |
@endforeach
@endforeach
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
    Просмотр
@endcomponent

@component('mail::panel')
Это сообщение сформировано автоматически. Не отвечайте на это сообщение. Свяжитесь с клиентом по указанным В ЗАКАЗЕ контактным данным.
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent