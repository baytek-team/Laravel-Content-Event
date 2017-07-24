@extends('event-category::template')

@section('page.head.menu')
    <div class="ui secondary menu">
        <a class="item" href="{{ route('events.category.create') }}">
            <i class="add icon"></i>{{ ___('Add Category') }}
        </a>
    </div>
@endsection

@section('content')
    <table class="ui selectable table">
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
                            {{-- <a class="item" href="{{ route('events.category.events.index', $category->id) }}">
                                <i class="file text icon"></i>
                                {{ ___('Resources') }}
                            </a> --}}
                            <a class="item" href="{{ route('events.category.edit', $category->id) }}">
                                <i class="pencil icon"></i>
                                {{ ___('Edit') }}
                            </a>
                            <a href="{{ route('events.category.destroy', $category->id) }}" class="item">
                                <i class="delete icon"></i>
                                {{ ___('Delete') }}
                            </a>
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

{{ $categories->links('pagination.default') }}

@endsection