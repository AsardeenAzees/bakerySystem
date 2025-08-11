@component('mail::message')
# Thanks for your order!

Order **#{{ $order->id }}** total **Rs {{ number_format($order->total,2) }}**

@component('mail::panel')
Status: {{ ucfirst($order->status) }}
@endcomponent

@component('mail::button', ['url' => route('orders.show',$order)])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent