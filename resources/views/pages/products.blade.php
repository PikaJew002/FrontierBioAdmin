@extends('layouts.app')

@section('content')
  <products :options="{'filter': '{{$options['filter']}}', 'ordered_by': '{{$options['ordered_by']}}', 'paginate': '{{$options['paginate']}}', 'from': '{{$options['from']}}'}"></products>
@endsection
