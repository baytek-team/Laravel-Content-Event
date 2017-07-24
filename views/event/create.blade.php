@extends('admin.events.event.template')


@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <form action="{{route('event.store')}}" method="POST" class="ui form">
                {{ csrf_field() }}

                @include('admin.events.event.form')
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>

                <div class="field actions">
    	            <a class="ui button" href="{{ route('event.index') }}">{{ ___('Cancel') }}</a>
    	            <button type="submit" class="ui right floated primary button">
    	            	{{ ___('Create') }}
                	</button>
                </div>
            </form>
        </div>
    </div>
@endsection