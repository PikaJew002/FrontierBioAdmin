@extends('layouts.app')

@section('content')
  <h1>Product Families</h1>
    <a href="/product_families/create" class="btn btn-primary">Add Product Family</a>
    <hr>
    @if(count($productFamilies) > 0)
      @foreach($productFamilies as $productFamily)
        <div class="well">
          <div class="row">
            <a href="/product_families/{{$productFamily->id}}">
              <div class="col-md-2">
                <h4>{{$productFamily->compound_id}}</h4>
              </div>
              <div class="col-md-6">
                <h4>{!! $productFamily->compound !!}</h4>
              </div>
            </a>
          </div>
        </div>
      @endforeach
      {{$productFamilies->links()}}
    @else
      <p>No product families found</p>
    @endif
@endsection
