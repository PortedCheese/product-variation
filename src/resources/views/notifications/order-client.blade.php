@component('mail::message')
# Здравствуйте!

На сайте был оформлен заказ {{ $order->number }} на сумму {{ $order->total }} рублей.

@component('mail::table')
| Товар | Количество | Сумма |
| :--- | --------: | ---: |
@foreach ($items as $item)
| {{ $item->title }}: {{ $item->description }}. @isset($item->specificationsArray)@foreach($item->specificationsArray as $key => $value) {{ $key }} - {{ $value }}.  @endforeach @endisset | {{ $item->quantity }}  {{ $item->short_measurement }} | {{ $item->total }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
    Просмотр
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent