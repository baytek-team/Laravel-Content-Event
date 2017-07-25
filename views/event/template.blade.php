@extends('content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="calendar icon"></i>
        <div class="content">
            {{ ___('Events') }}
            <div class="sub header">{{ ___('Manage the events of the system.') }}</div>
        </div>
    </h1>
@endsection
