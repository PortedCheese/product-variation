@component('mail::message')
# Здравствуйте {{ $order->user_name }}!

Вы оформили заказ {{ $order->number }} на сумму {{ $order->total }} рублей.

@component('mail::table')
| Товар | Количество | Сумма |
| :--- | --------: | ---: |
@foreach ($items as $item)
| {{ $item->title }}: {{ $item->description }}. @isset($item->specificationsArray) @foreach($item->specificationsArray as $key => $value) {{ $key }} - {{ $value }}.  @endforeach @endisset | {{ $item->quantity }} {{ $item->short_measurement }} | {{ $item->total }} |
@foreach($item->orderItemSets as $set)
@foreach($set->addons as $addon)
| + {{ $addon->product->title }}: {{ $addon->description }}. @isset($addon->specificationsArray) @foreach($addon->specificationsArray as $key => $value) {{ $key }} - {{ $value }}.  @endforeach @endisset | {{ $addon->quantity }} {{ $addon->short_measurement }} | {{ $addon->total }} |
@endforeach
@endforeach
@endforeach
@endcomponent
@component('mail::panel')
Не отвечайте на это сообщение. При возникновении вопросов по заказу позвоните по телефону, указанному на сайте <a href="{{ config("app.url") }}">{{ config('app.name') }}</a>
@endcomponent
С уважением,<br>
{{ config('app.name') }}
@endcomponent