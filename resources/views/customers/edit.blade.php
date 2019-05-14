@extends("layouts.app")

@section("content")
  <h1>Edit Customer</h1>
  {!! Form::open(['action' => ['CustomersController@update', $customer->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
      {{Form::label('name', '*Name')}}
      {{Form::text('name', $customer->name, ['class' => 'form-control', 'placeholder' => 'Customer Name'])}}
    </div>
    <div class="form-group">
      {{Form::label('short_name', 'Short Name')}}
      {{Form::text('short_name', $customer->short_name, ['class' => 'form-control', 'placeholder' => 'Short Name'])}}
    </div>
    * Required fields<br>
    {{Form::hidden('_method', 'PUT')}}
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/customers/{{$customer->id}}" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
