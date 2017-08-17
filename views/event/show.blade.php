@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="globe icon"></i>
        <div class="content">
            Event Management
            <div class="sub header">Create a Event.</div>
        </div>
    </h1>
@endsection

@section('content')
    <div class="ui stretched grid">
        <div class="two wide column center aligned " style="background:#fbb034; color:white">
       		<div class="ui grid">
	        	<div class="middle aligned column">
		            <span style="font-weight:bold; font-size: 36px;">{{ (new Carbon\Carbon($event->getMeta('event_date')))->format('j') }}</span>
		            <br/>
		            <span style="">{{ (new Carbon\Carbon($event->getMeta('event_date')))->format('M') }}</span>
	            </div>
            </div>
        </div>
        <div class="fourteen wide column">
                <h1>{{ str_limit($event->title, 100) }}</h1>
                {!! $event->content !!}

                @can('Update Event')
                <a class="item" href="{{ route('event.edit', $event->id) }}">
                    <i class="pencil icon"></i>
                    {{ ___('Edit') }}
                </a>
                @endcan
                @can('Delete Event')
                @button(___('Delete'), [
                    'method' => 'delete',
                    'location' => 'event.destroy',
                    'type' => 'route',
                    'confirm' => 'Are you sure you want to delete this event?</br>This cannot be undone.',
                    'class' => 'item action',
                    'prepend' => '<i class="delete icon"></i>',
                    'model' => $event,
                ])
                @endcan
        </div>
    </div>

	{{-- <div id="content" v-html="content">
		{!! $event->content !!}
	</div>

	<div class="ui hidden divider"></div>
	<div class="ui hidden divider"></div>

	<div class="ui horizontal segments">
		<div class="ui segments segment">
		    <div class="ui segment header">
		        Settings
		    </div>
	        <div class="ui segment blue bottom">
	            @php
	    			dump(config('cms.content.event'));
	    		@endphp
	        </div>

	    </div>
	    <div class="ui segments segment">
		    <div class="ui segment header">
		        Meta Data
		    </div>

		    <div class="ui segment orange bottom">
		        @php
					dump($event->meta);
				@endphp
		    </div>
		</div>

	    <div class="ui segments segment">
		    <div class="ui segment header">
		        Revisions
		    </div>

		    <div class="ui segment green bottom">
		        @php
					dump($event->revisions);
				@endphp
		    </div>
		</div>
	</div>
 --}}

@endsection