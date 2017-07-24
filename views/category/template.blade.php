@extends('content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="calendar icon"></i>
        <div class="content">
            {{ ___('Categories') }}
            <div class="sub header">{{ ___('Manage the event categories.') }}</div>
        </div>
    </h1>
@endsection
