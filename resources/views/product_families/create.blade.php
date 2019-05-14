@extends('layouts.app')

@section('content')
  <h1>Add New Product Family</h1>
  {!! Form::open(['action' => 'ProductFamiliesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('compound_id', '*Identifier')}}
      {{Form::text('compound_id', '', ['class' => 'form-control', 'placeholder' => 'Product Family Identifier'])}}
    </div>
    <div class="form-group">
      {{Form::label('compound', '*Compound Name')}}
      {{Form::textarea('compound', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Compound Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('short_name', 'Short Name')}}
      {{Form::text('short_name', '', ['class' => 'form-control', 'placeholder' => 'Short Name'])}}
    </div>
    <p>
      * Required fields
    </p>
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/product_families" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
