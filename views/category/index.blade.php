@extends('events::category.template')

@section('page.head.menu')
    <div class="ui stackable secondary contextual menu">
        <div class="item">
            @can('Create Event Category')
            <a class="ui primary button" href="{{ route('event.category.create') }}">
                <i class="add icon"></i>{{ ___('Add Category') }}
            </a>
            @endcan
        </div>
    </div>
@endsection

@if(count($categories))
    @section('content')
    {{-- <div class="section"> --}}
        <table class="ui selectable very basic table">
            <thead>
                <tr>
                    <th>{{ ___('Category Name') }}</th>
                    <th class="center aligned collapsing">{{ ___('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr data-category-id="{{ $category->id }}">
                        <td>{{ $category->title }}</td>
                        <td class="right aligned collapsing">
                            <div class="ui compact text menu">
                                @can('Update Event Category')
                                <a class="item" href="{{ route('event.category.edit', $category->id) }}">
                                    <i class="pencil icon"></i>
                                    {{ ___('Edit') }}
                                </a>
                                @endcan
                                @can('Delete Event Category')
                                @button(___('Delete'), [
                                    'method' => 'delete',
                                    'location' => 'event.category.destroy',
                                    'type' => 'route',
                                    'confirm' => 'Are you sure you want to delete this event category?</br>This cannot be undone.',
                                    'class' => 'item',
                                    'prepend' => '<i class="delete icon"></i>',
                                    'model' => $category,
                                ])
                                @endcan
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
    {{-- </div> --}}
    {{ $categories->links('pagination.default') }}

    @endsection
@else
    @section('outer-content')
        <div class="ui middle aligned padded grid no-result">
            <div class="column">
                <div class="ui center aligned padded grid">
                    <div class="column">
                        <h2>{{ ___('We couldn\'t find anything') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
