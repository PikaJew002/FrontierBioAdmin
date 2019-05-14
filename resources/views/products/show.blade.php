@extends("layouts.app")

@section("content")
  <a href="/product_families" class="btn btn-default">Go Back</a>
  <h1>{{$productFamily->name}}</h1>
  @if(count($productFamily->products) > 0)
    @foreach($productFamily->products as $product)
      <div class="well">
        <div class="row">
          <div class="col-md-4 colsm-4">
            <h3><a href="/products/{{$product->id}}"></a></h3>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <p>No products found</p>
  @endif
  <br>
  <a href="/products/create/{{$productFamily->id}}" class="btn btn-default">Add Product</a>
  <hr>
  <a href="/product_families/{{$productFamily->id}}/edit" class="btn btn-default">Edit</a>
  {!! Form::open(['action' => ['productFamiliesController@destroy', $productFamily->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
  {!! Form::close() !!}
@endsection
