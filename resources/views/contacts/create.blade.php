@extends('layouts.app')

@section('content')
  <h1>Add New Contact</h1>
  <h3><em>{{$customer->name}}</em></h3>
  {!! Form::open(['action' => 'ContactsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('prefix', 'Prefix')}}
      {{Form::text('prefix', '', ['class' => 'form-control', 'placeholder' => 'Prefix'])}}
    </div>
    <div class="form-group">
      {{Form::label('first_name', '*First Name')}}
      {{Form::text('first_name', '', ['class' => 'form-control', 'placeholder' => 'First Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('last_name', '*Last Name')}}
      {{Form::text('last_name', '', ['class' => 'form-control', 'placeholder' => 'Last Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('phone', '*Phone')}}
      {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => 'Phone'])}}
    </div>
    <div class="form-group">
      {{Form::label('email', '*Email')}}
      {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email'])}}
    </div>
    <div class="form-group">
      {{Form::label('shipping_address', 'Shipping Address')}}
      {{Form::textarea('shipping_address', '', ['class' => 'form-control', 'placeholder' => 'Shipping Address'])}}
    </div>
    <div class="form-group">
      {{Form::label('billing_address', 'Billing Address')}}
      {{Form::textarea('billing_address', '', ['class' => 'form-control', 'placeholder' => 'Billing Address'])}}
    </div>
    <p>
      * Required fields<br>
    </p>
    {{Form::hidden('customer_id', $customer->id)}}
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/customers/{{$customer->id}}" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
