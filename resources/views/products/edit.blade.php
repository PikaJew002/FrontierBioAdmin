@extends("layouts.app")

@section("content")
  <h1>Edit Product</h1>
  {!! Form::open(['action' => ['ProductsController@update', $product->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group row">
      {{Form::label('type', 'Type', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::select('type', ['Liquid' => 'Liquid', 'Solid' => 'Solid'], $product->type, ['class' => 'form-control'])}}
      </div>
    </div>
    <div id="optional">
      <div class="form-group row">
        {{Form::label('concentration', 'Concentration', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-2">
          {{Form::text('concentration', $product->concentration, ['class' => 'form-control', 'placeholder' => 'Concentration'])}}
        </div>
        <div class="col-sm-2">
          {{Form::text('concentration_units', $product->concentration_units, ['class' => 'form-control', 'placeholder' => 'Units'])}}
        </div>
      </div>
      <div class="form-group row">
        {{Form::label('solvent', 'Solvent', ['class' => 'col-sm-2 col-form-label'])}}
        <div class="col-sm-2">
          {{Form::text('solvent', $product->solvent, ['class' => 'form-control', 'placeholder' => 'Solvent'])}}
        </div>
      </div>
    </div>
    <div class="form-group row">
      {{Form::label('amount', 'Amount (Mass/Volume)', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::text('amount', $product->amount, ['class' => 'form-control', 'placeholder' => 'Mass/Volume'])}}
      </div>
      <div class="col-sm-2">
        {{Form::text('amount_units', $product->amount_units, ['class' => 'form-control', 'placeholder' => 'Amount Units'])}}
      </div>
    </div>
    <div class="form-group row">
      {{Form::label('list_price', 'List Price', ['class' => 'col-sm-2 col-form-label'])}}
      <div class="col-sm-2">
        {{Form::text('list_price', $product->list_price, ['class' => 'form-control', 'placeholder' => 'List Price'])}}
      </div>
      <div class="col-sm-2">
        <div class="form-check">
          @if($product->list_price == 0)
            {{Form::checkbox('inquire_for_price', 'checked', true, ['class' => 'form-check-input'])}}
          @else
            {{Form::checkbox('inquire_for_price', 'checked', false, ['class' => 'form-check-input'])}}
          @endif
          {{Form::label('inquire_for_price', 'Inquire for price', ['class' => 'form-check-label'])}}
        </div>
      </div>
    </div>
    {{Form::hidden('family_id', $product->family_id)}}
    {{Form::hidden('_method', 'PUT')}}
    <div class="form-group">
      {{Form::submit('Submit', ['class' => 'btn btn-primary'])}} <a href="/product_families/{{$product->family_id}}" class="btn btn-default">Cancel</a>
    </div>
  {!! Form::close() !!}
@endsection
