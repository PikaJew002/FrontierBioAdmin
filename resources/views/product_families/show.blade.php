@extends("layouts.app")

@section("content")
  <a href="/product_families" class="btn btn-default">Go Back</a>
  @if($productFamily->short_name != null)
    <h1>{!! $productFamily->compound !!} ({{$productFamily->short_name}})</h1>
  @else
    <h1>{!! $productFamily->compound !!}</h1>
  @endif
  <h3>Identifier: {{$productFamily->compound_id}}</h3>
  @if(count($productFamily->products) > 0)
    <table>
      <div class="row">
        <div class="col-sm-2">
          <h4>Type</h4>
        </div>
        <div class="col-sm-2">
          <h4>Concentration</h4>
        </div>
        <div class="col-sm-2">
          <h4>Solvent</h4>
        </div>
        <div class="col-sm-2">
          <h4>Amount</h4>
        </div>
        <div class="col-sm-2">
          <h4>List Price</h4>
        </div>
      </div>
    @foreach($productFamily->products as $product)
      <div class="row">
        <div class="col-sm-2">
          {{$product->type}}
        </div>
        <div class="col-sm-2">
          @if($product->type == "Liquid")
            {{$product->concentration}} {{$product->concentration_units}}
          @else
            <em>N/A</em>
          @endif
        </div>
        <div class="col-sm-2">
          @if($product->type == "Liquid")
            {{$product->solvent}}
          @else
            <em>N/A</em>
          @endif
        </div>
        <div class="col-sm-2">
          {{$product->amount}} {{$product->amount_units}}
        </div>
        <div class="col-sm-2">
          @if($product->list_price == 0)
            <em>Inquire for price</em>
          @else
            ${{$product->list_price}}
          @endif
        </div>
        <div class="col-sm-2">
          <a href="/products/{{$product->id}}/edit" class="btn btn-default">Edit</a>
          <div class="pull-right">
            {!! Form::open(['action' => ['ProductsController@destroy', $product->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    @endforeach
    </table>
  @else
    <p><em>No products found</em></p>
  @endif
  <br>
  <a href="/products/create/{{$productFamily->id}}" class="btn btn-default">Add Product</a>
  <hr>
  <a href="/product_families/{{$productFamily->id}}/edit" class="btn btn-default">Edit</a>
  {!! Form::open(['action' => ['ProductFamiliesController@destroy', $productFamily->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
  {!! Form::close() !!}
@endsection
