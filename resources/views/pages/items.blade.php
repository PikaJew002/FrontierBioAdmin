@extends('layouts.app')

@section('content')
  <items :options="{'filter': '{{$options['filter']}}', 'ordered_by': '{{$options['ordered_by']}}', 'paginate': '{{$options['paginate']}}', 'from': '{{$options['from']}}'}"></items>
@endsection
