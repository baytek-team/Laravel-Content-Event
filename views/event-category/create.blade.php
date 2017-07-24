@extends('event-category::template')

@section('content')
<div class="flex-center position-ref full-height">
    <div class="content">
        <form action="{{route('events.category.store')}}" method="POST" class="ui form">
            {{ csrf_field() }}
            <input type="hidden" id="parent_id" name="parent_id" value="{{ $parent->id }}">

            @include('event-category::form')

            <div class="field actions">
	            <a class="ui button" href="{{ route('events.category.index') }}">{{ ___('Cancel') }}</a>
	            <button type="submit" class="ui right floated primary button">
	            	{{ ___('Create Content') }}
            	</button>
            </div>
        </form>
    </div>
</div>

@endsection