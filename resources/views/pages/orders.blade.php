@extends('layouts.app')

@section('content')
  <orders :options="{'filter': '{{$options['filter']}}', 'ordered_by': '{{$options['ordered_by']}}', 'paginate': '{{$options['paginate']}}', 'from': '{{$options['from']}}'}"></orders>
@endsection
