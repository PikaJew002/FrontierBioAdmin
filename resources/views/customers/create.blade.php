@extends('layouts.app')

@section('content')
  <h1>Add New Customer</h1>
  {!! Form::open(['action' => 'CustomersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('name', '*Name')}}
      {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Customer Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('short_name', 'Short Name')}}
      {{Form::text('short_name', '', ['class' => 'form-control', 'placeholder' => 'Short Name'])}}
    </div>
    <p>
      * Required fields
    </p>
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/customers" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
