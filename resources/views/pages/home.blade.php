@extends("layouts.app")

@section("content")
  <h1>Frontier BioPharm</h1>
  <p>
    {{ Auth::user()->name }}
  </p>
@endsection
