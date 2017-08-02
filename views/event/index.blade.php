@php
    $dates = [];
    $links = $events->links('pagination.default');
    foreach($events as $event) {
        $date = (new Carbon\Carbon($event->getMeta('event_date')))->format('Y-m-d');
        if(!isset($dates[$date])) {
            $dates[$date] = collect([]);
        }
        $dates[$date]->push($event);
    }
@endphp
@extends('events::event.template')

@section('page.head.menu')
    <div class="ui stackable fluid secondary menu">
        <div class="header item">
            <i class="filter icon"></i>
            {{ ___('Filter By') }}
        </div>
        <a class="item @if($filter && $filter == 'all') active @endif" href="{{ route('event.index') }}">{{ ___('All') }}</a>
        <a class="item @if($filter && $filter == 'upcoming') active @endif" href="{{ route('event.upcoming') }}">{{ ___('Upcoming') }}</a>
        <a class="item @if($filter && $filter == 'past') active @endif" href="{{ route('event.past') }}">{{ ___('Past') }}</a>
        <a class="item @if($filter && $filter == 'featured') active @endif" href="{{ route('event.featured') }}">{{ ___('Featured') }}</a>
        @if(Auth::user()->can('Create Event'))
            <a class="ui item button" href="{{ route('event.create') }}">
                <i class="eye icon"></i>{{ ___('View Categories') }}
            </a>

            <a class="ui item primary button" href="{{ route('event.create') }}">
                <i class="add icon"></i>{{ ___('Add Event') }}
            </a>
        @endif
    </div>
@endsection

@section('outer-content')

    @forelse($dates as $date => $events)
        @php
            if(date('Y-m-d') == $date) $fuzzyClass = 'today';
            else if(date('Y-m-d', strtotime('yesterday')) == $date) $fuzzyClass = 'yesterday';
            else if(date('Y-m-d', strtotime('tomorrow')) == $date) $fuzzyClass = 'tomorrow';
            else $fuzzyClass = '';
        @endphp

        <div class="ui medium soft header">
            {{ ucfirst($fuzzyClass) }}
        </div>

        <div class="ui segment">
            <div class="ui stretched stackable grid">
                <div class="center aligned two wide column date {{$fuzzyClass}}">
                    <div class="ui grid">
                        <div class="middle aligned column">
                            <span class="day">{{ date('j', strtotime($date)) }}</span><br/>
                            <span class="month">{{ date('M', strtotime($date)) }}</span>
                        </div>
                    </div>
                </div>

                <div class="fourteen wide column">
                    @foreach($events as $event)
                        <div class="ui middle aligned padded grid">
                            <div class="fourteen wide column">
                            <a href="{{ route('event.edit', $event->id) }}">
                                <h2 class="ui header">{{ $event->title }}</h2>
                            </a>
                            {!! $event->content !!}
                            </div>
                            <div class="two wide right aligned column">
                                <a class="ui icon basic button" href="{{ route('event.edit', $event->id) }}">
                                    <i class="pencil icon"></i>
                                </a>
                                @button(___(''), [
                                    'method'   => 'delete',
                                    'location' => 'event.destroy',
                                    'type'     => 'route',
                                    'confirm'  => 'Are you sure you want to delete this event?</br>This cannot be undone.',
                                    'class'    => 'ui icon basic button action',
                                    'prepend'  => '<i class="delete icon"></i>',
                                    'model'    => $event,
                                ])
                            </div>
                        </div>

                        @if($events->last() !== $event)
                            <div class="ui divider"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="ui middle aligned padded grid">
            <div class="column">
                <div class="ui center aligned padded grid">
                    <div class="column">
                        <h2>{{ ___('We couldn\'t find anything') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endforelse

    {{ $links }}
@endsection
