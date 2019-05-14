@extends('layouts.app')

@section('content')
  <h1>Customers</h1>
    <a href="/customers/create" class="btn btn-primary">Add Customer</a>
    <hr>
    @if(count($customers) > 0)
      @foreach($customers as $customer)
        <div class="well">
          <div class="row">
            <div class="col-md-4">
              @if($customer->short_name != null)
                <h4><a href="/customers/{{$customer->id}}">{{$customer->name}} ({{$customer->short_name}})</a></h4>
              @else
                <h4><a href="/customers/{{$customer->id}}">{{$customer->name}}</a></h4>
              @endif
            </div>
          </div>
        </div>
      @endforeach
      {{$customers->links()}}
    @else
      <p>No orders found</p>
    @endif
@endsection
