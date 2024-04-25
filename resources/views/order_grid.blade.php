@extends('layouts.app')

@section('content')
<div class="container order_adminGrid">
    <h3>Shopify Orders</h3>

    <div class="row">
        <div class="col">
            <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($orders as $order)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $order['node']['name'] }}</h5>   
                                <p class="card-text">Customer: {{ $order['node']['customer']['firstName']}} {{ $order['node']['customer']['lastName'] }}</p>
                                <p class="card-text">Price: {{ $order['node']['totalPriceSet']['shopMoney']['amount'] }}
                                <small class="text-muted">{{ $order['node']['totalPriceSet']['shopMoney']['currencyCode'] }}</small></p>
                                
                                <!-- Shipping Address -->
                                <h6 class="card-subtitle mb-2 text-muted">Shipping Address</h6>
                                <p class="card-text">
                                    {{ $order['node']['shippingAddress']['address1'] }}<br>
                                    {{ $order['node']['shippingAddress']['city'] }}, {{ $order['node']['shippingAddress']['province'] }}<br>
                                    {{ $order['node']['shippingAddress']['country'] }}, {{ $order['node']['shippingAddress']['zip'] }}
                                </p>
                                
                                <!-- Billing Address -->
                                <h6 class="card-subtitle mb-2 text-muted">Billing Address</h6>
                                <p class="card-text">
                                    {{ $order['node']['billingAddress']['address1'] }}<br>
                                    {{ $order['node']['billingAddress']['city'] }}, {{ $order['node']['billingAddress']['province'] }}<br>
                                    {{ $order['node']['billingAddress']['country'] }}, {{ $order['node']['billingAddress']['zip'] }}
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">{{ $order['node']['createdAt'] }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- End of existing list view with cards -->
            </div>

            </div>
        </div>
    </div>
</div>
@endsection
