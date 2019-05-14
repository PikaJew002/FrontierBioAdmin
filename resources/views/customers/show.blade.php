@extends("layouts.app")

@section("content")
  <a href="/customers" class="btn btn-default">Go Back</a>
  @if($customer->short_name != null)
    <h1>{{$customer->name}} ({{$customer->short_name}})</h1>
  @else
    <h1>{{$customer->name}}</h1>
  @endif
  @if(count($customer->contacts) > 0)
    @foreach($customer->contacts as $contact)
      <div class="well">
        <div class="row">
          <div class="col-md-4 colsm-4">
            <h3><a href="/contacts/{{$contact->id}}">{{$contact->prefix}} {{$contact->first_name}} {{$contact->last_name}}</a></h3>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <p>No contacts found</p>
  @endif
  <br>
  <a href="/contacts/create/{{$customer->id}}" class="btn btn-default">Add Contact</a>
  <hr>
  <a href="/customers/{{$customer->id}}/edit" class="btn btn-default">Edit</a>
  {!! Form::open(['action' => ['CustomersController@destroy', $customer->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
  {!! Form::close() !!}
@endsection
