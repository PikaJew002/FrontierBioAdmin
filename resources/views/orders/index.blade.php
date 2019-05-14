@extends('layouts.app')

@section('content')
  <h1>Orders</h1>
    @if(count($orders) > 0)
      @foreach($orders as $order)
        <div class="well">
          <div class="row">
            <div class="col-md-4 colsm-4">
              {{$order->contact()->customer()->name}}: {{$order->contact()->first_name}} {{$order->contact()->last_name}}
            </div>
            <div class="col-md-8 colsm-8">
              <h3><a href="/posts/{{$order->id}}">View Order Details</a></h3>
              <small>Ordered on {{$order->created_at}}</small>
            </div>
          </div>
        </div>
      @endforeach
      {{$posts->links()}}
    @else
      <p>No orders found</p>
    @endif
@endsection
