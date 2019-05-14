@extends('layouts.app')

@section('content')
  <h1>Add New Product</h1>
  {!! Form::open(['action' => 'ProductsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group row">
      {{Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::select('type', ['Liquid' => 'Liquid', 'Solid' => 'Solid'], null, ['class' => 'form-control', 'placeholder' => 'Select a type'])}}
      </div>
    </div>
    <div id="optional">
      <div class="form-group row">
        {{Form::label('concentration', 'Concentration', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-2">
          {{Form::text('concentration', '', ['class' => 'form-control', 'placeholder' => 'Concentration'])}}
        </div>
        <div class="col-sm-2">
          {{Form::text('concentration_units', '', ['class' => 'form-control', 'placeholder' => 'Units'])}}
        </div>
      </div>
      <div class="form-group row">
        {{Form::label('solvent', 'Solvent', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-2">
          {{Form::text('solvent', '', ['class' => 'form-control', 'placeholder' => 'Solvent'])}}
        </div>
      </div>
    </div>
    <div class="form-group row">
      {{Form::label('amount', 'Amount (Mass/Volume)', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::text('amount', '', ['class' => 'form-control', 'placeholder' => 'Mass/Volume'])}}
      </div>
      <div class="col-sm-2">
        {{Form::text('amount_units', '', ['class' => 'form-control', 'placeholder' => 'Amount Units'])}}
      </div>
    </div>
    <div class="form-group row">
      {{Form::label('list_price', 'List Price', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::text('list_price', '', ['class' => 'form-control', 'placeholder' => 'List Price'])}}
      </div>
      <div class="col-sm-2">
        <div class="form-check">
          {{Form::checkbox('inquire_for_price', 'checked', false, ['class' => 'form-check-input'])}}
          {{Form::label('inquire_for_price', 'Inquire for price', ['class' => 'form-check-label'])}}
        </div>
      </div>
    </div>
    {{Form::hidden('family_id', $family_id)}}
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/product_families/{{$family_id}}" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
