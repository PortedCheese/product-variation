@component('mail::message')
# Здравствуйте {{ $order->user_name }}!

Вы оформили заказ {{ $order->number }} на сумму {{ $order->total }} рублей.

@component('mail::table')
| Товар | Количество | Сумма |
| :--- | --------: | ---: |
@foreach ($items as $item)
| {{ $item->title }} ({{ $item->description }}) | {{ $item->quantity }} {{ $item->measurement }} | {{ $item->total }} |
@endforeach
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent