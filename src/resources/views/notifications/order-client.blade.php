@component('mail::message')
# Здравствуйте!

На сайте был оформлен заказ {{ $order->number }} на сумму {{ $order->total }} рублей.

@component('mail::table')
| Товар | Количество | Сумма |
| :--- | --------: | ---: |
@foreach ($items as $item)
| {{ $item->title }} ({{ $item->description }}) | {{ $item->quantity }}  {{ $item->measurement }} | {{ $item->total }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
    Просмотр
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent