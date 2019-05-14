@extends("layouts.app")

@section("content")
  <a href="/customers/{{$contact->customer->id}}" class="btn btn-default">Back to Customer</a>
  <h1>{{$contact->customer->name}}</h1>
  <table class="table">
    <tr>
      <td>Name</td>
      <td>{{$contact->prefix}} {{$contact->first_name}} {{$contact->last_name}}</td>
    </tr>
    <tr>
      <td>Phone</td>
      <td>{{$contact->phone}}</td>
    </tr>
    <tr>
      <td>Email</td>
      <td>{{$contact->email}}</td>
    </tr>
    <tr>
      <td>Shipping Address</td>
      @if($contact->shipping_address != null)
        <td>{{$contact->shipping_address}}</td>
      @else
        <td><em>No Shipping Address</em></td>
      @endif
    </tr>
    <tr>
      <td>Billing Address</td>
      @if($contact->billing_address != null)
        <td>{{$contact->billing_address}}</td>
      @else
        <td><em>No Billing Address</em></td>
      @endif
    </tr>
  </table>
  <hr>
  <a href="/contacts/{{$contact->id}}/edit" class="btn btn-default">Edit</a>
  {!! Form::open(['action' => ['ContactsController@destroy', $contact->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
  {!! Form::close() !!}
@endsection
