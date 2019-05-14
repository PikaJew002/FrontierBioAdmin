@extends('layouts.app')

@section('content')
  <h1>Products</h1>
    <a href="/product_families/create" class="btn btn-primary">Add Product Family</a>
    <hr>
    @if(count($productFamilies) > 0)
      @foreach($productFamilies as $productFamily)
        <div class="well">
          <div class="row">
            <div class="col-md-4">
              <h4><a href="/product_families/{{$productFamily->id}}">{{$productFamily->name}}</a></h4>
            </div>
          </div>
        </div>
      @endforeach
      {{$productFamilies->links()}}
    @else
      <p>No product families found</p>
    @endif
@endsection
