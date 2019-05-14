@extends('layouts.app')

@section('content')
  <customers :options="{'filter': '{{$options['filter']}}', 'ordered_by': '{{$options['ordered_by']}}', 'paginate': '{{$options['paginate']}}'}"></customers>
@endsection
