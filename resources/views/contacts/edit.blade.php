@extends('layouts.app')

@section('content')
  <h1>Edit Contact</h1>
  <h3><em>{{$contact->customer->name}}</em></h3>
  {!! Form::open(['action' => ['ContactsController@update', $contact->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('prefix', 'Prefix')}}
      {{Form::text('prefix', $contact->prefix, ['class' => 'form-control', 'placeholder' => 'Prefix'])}}
    </div>
    <div class="form-group">
      {{Form::label('first_name', '*First Name')}}
      {{Form::text('first_name', $contact->first_name, ['class' => 'form-control', 'placeholder' => 'First Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('last_name', '*Last Name')}}
      {{Form::text('last_name', $contact->last_name, ['class' => 'form-control', 'placeholder' => 'Last Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('phone', '*Phone')}}
      {{Form::text('phone', $contact->phone, ['class' => 'form-control', 'placeholder' => 'Phone'])}}
    </div>
    <div class="form-group">
      {{Form::label('email', '*Email')}}
      {{Form::text('email', $contact->email, ['class' => 'form-control', 'placeholder' => 'Email'])}}
    </div>
    <div class="form-group">
      {{Form::label('shipping_address', 'Shipping Address')}}
      {{Form::textarea('shipping_address', $contact->shipping_address, ['class' => 'form-control', 'placeholder' => 'Shipping Address'])}}
    </div>
    <div class="form-group">
      {{Form::label('billing_address', 'Billing Address')}}
      {{Form::textarea('billing_address', $contact->billing_address, ['class' => 'form-control', 'placeholder' => 'Billing Address'])}}
    </div>
    <p>
      * Required fields
    </p>
    {{Form::hidden('customer_id', $contact->customer_id)}}
    {{Form::hidden("_method", "PUT")}}
    <div class="form-group">
      {{Form::submit('Submit Changes', ['class' => 'btn btn-primary'])}} <a href="/contacts/{{$contact->id}}" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
