@extends("layouts.app")

@section("content")
  <h1>Edit Product Family</h1>
  {!! Form::open(['action' => ['ProductFamiliesController@update', $productFamily->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('compound_id', '*Identifier')}}
      {{Form::text('compound_id', $productFamily->compound_id, ['class' => 'form-control', 'placeholder' => 'Product Family Identifier'])}}
    </div>
    <div class="form-group">
      {{Form::label('compound', '*Compound Name')}}
      {{Form::textarea('compound', $productFamily->compound, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Compound Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('short_name', 'Short Name')}}
      {{Form::text('short_name', $productFamily->short_name, ['class' => 'form-control', 'placeholder' => 'Short Name'])}}
    </div>
    {{Form::hidden('_method', 'PUT')}}
    <p>
      * Required fields
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    </div>
  {!! Form::close() !!}
@endsection
