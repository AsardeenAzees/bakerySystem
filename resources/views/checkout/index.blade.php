@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-3">Checkout</h1>

  <div class="row">
    <div class="col-md-7">
      <form action="{{ route('checkout.place') }}" method="post" class="card card-body mb-3">
        @csrf
        <h5>Delivery Address</h5>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="address_mode" id="addr_existing" value="existing" checked>
          <label class="form-check-label" for="addr_existing">Use an existing address</label>
        </div>
        <div class="mb-3">
          <select name="address_id" class="form-select">
            @foreach($addresses as $a)
              <option value="{{ $a->id }}">{{ $a->line1 }}, {{ $a->city }} {{ $a->is_default ? '(default)' : '' }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="address_mode" id="addr_new" value="new">
          <label class="form-check-label" for="addr_new">Add a new address</label>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-md-8">
            <input name="line1" class="form-control" placeholder="Line 1">
          </div>
          <div class="col-md-4">
            <input name="line2" class="form-control" placeholder="Line 2 (optional)">
          </div>
          <div class="col-md-4">
            <input name="city" class="form-control" placeholder="City">
          </div>
          <div class="col-md-4">
            <input name="state" class="form-control" placeholder="State">
          </div>
          <div class="col-md-4">
            <input name="postal_code" class="form-control" placeholder="Postal code">
          </div>
        </div>

        <h5>Payment</h5>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cod" checked>
          <label class="form-check-label" for="pm_cod">Cash on Delivery</label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="payment_method" id="pm_stripe" value="stripe">
          <label class="form-check-label" for="pm_stripe">Pay by Card (Stripe — Test)</label>
        </div>

        <div class="text-end mt-3">
          <button class="btn btn-success">Place Order</button>
        </div>
      </form>
    </div>

    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Order Summary</div>
        <div class="card-body">
          @foreach($items as $i)
            <div class="d-flex justify-content-between">
              <div>{{ $i['name'] }} × {{ $i['qty'] }}</div>
              <div>Rs {{ number_format($i['price']*$i['qty'],2) }}</div>
            </div>
          @endforeach
          <hr>
          <div class="d-flex justify-content-between">
            <div>Subtotal</div><div>Rs {{ number_format($subtotal,2) }}</div>
          </div>
          <div class="d-flex justify-content-between">
            <div>Delivery</div><div>Rs {{ number_format($deliveryFee,2) }}</div>
          </div>
          <div class="d-flex justify-content-between">
            <div>Discount</div><div>Rs {{ number_format($discount,2) }}</div>
          </div>
          <hr>
          <div class="d-flex justify-content-between fw-bold">
            <div>Total</div><div>Rs {{ number_format($total,2) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
