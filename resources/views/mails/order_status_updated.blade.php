@component('mail::message')
# Order Update

Your order **#{{ $order->id }}** is now **{{ ucfirst($order->status) }}**.

@component('mail::button', ['url' => route('orders.show',$order)])
Track Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent