@extends('events::event.template')

@section('page.head.menu')
    <div class="ui secondary menu">
        @if(Auth::user()->can('Create Event'))
            <a class="item" href="{{ route('event.create') }}">
                <i class="add icon"></i>{{ ___('Add Event') }}
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="ui text menu">
    <div class="header item">
        <i class="filter icon"></i>
        {{ ___('Filter By') }}
    </div>
    <a class="item @if($filter && $filter == 'all') active @endif" href="{{ route('event.index') }}">{{ ___('All') }}</a>
    <a class="item @if($filter && $filter == 'upcoming') active @endif" href="{{ route('event.upcoming') }}">{{ ___('Upcoming') }}</a>
    <a class="item @if($filter && $filter == 'past') active @endif" href="{{ route('event.past') }}">{{ ___('Past') }}</a>
    <a class="item @if($filter && $filter == 'featured') active @endif" href="{{ route('event.featured') }}">{{ ___('Featured') }}</a>
</div>
<table class="ui selectable table">
    <thead>
        <tr>
            <th class="nine wide">{{ ___('Event Title') }}</th>
            <th>{{ ___('Event Date') }}</th>
            <th class="center aligned collapsing">{{ ___('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($events as $event)
            <tr class="nine wide" data-event-id="{{ $event->id }}">
                <td>{{ str_limit($event->title, 100) }}</td>
                <td>{{ (new Carbon\Carbon($event->getMeta('event_date')))->formatLocalized(___('%B %e, %Y')) }}</td>
                <td class="right aligned collapsing">
                    <div class="ui compact text menu">
                        <a class="item" href="{{ route('event.edit', $event->id) }}">
                            <i class="pencil icon"></i>
                            {{ ___('Edit') }}
                        </a>
                        @button(___('Delete'), [
                            'method' => 'delete',
                            'location' => 'event.destroy',
                            'type' => 'route',
                            'confirm' => 'Are you sure you want to delete this event?</br>This cannot be undone.',
                            'class' => 'item action',
                            'prepend' => '<i class="delete icon"></i>',
                            'model' => $event,
                        ])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    <div class="ui centered">{{ ___('There are no results') }}</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $events->links('pagination.default') }}

@endsection