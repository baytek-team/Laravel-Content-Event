<div class="two fields">
    <div class="twelve wide field{{ ($errors->has('title') || $errors->has('key')) ? ' error' : '' }}">
        <label for="title">{{ ___('Title') }}</label>
        <input type="text" id="title" name="title" placeholder="{{ ___('Title') }}" value="{{ old('title', $event->title) }}">
    </div>
    <div class="four wide field{{ ($errors->has('event_date') || $errors->has('key')) ? ' error' : '' }}">
        <label for="event_date">{{ ___('Event Date') }}</label>
        <input class="ui daterangepicker" type="text" id="event_date" name="event_date" placeholder="{{ ___('Event Date') }}" value="{{ old('event_date', $event->eventDate->format('Y-m-d h:m A')) }}">
    </div>
</div>

<div class="field{{ $errors->has('content') ? ' error' : '' }}">
    <label for="content">{{ ___('Content') }}</label>
    <textarea id="content" name="content" class="editor" placeholder="{{ ___('Content') }}">{{ old('content', $event->content) }}</textarea>
</div>

<div class="two fields">
    <div class="twelve wide field">
        <label for="category">{{ ___('Category') }}</label>
        <div class="ui fluid dropdown labeled search icon basic button">
            <input type="hidden" name="category" value="{{ old('category', isset($category)?$category:'')}}">
            <i class="search icon"></i>
            <span class="text">{{ ___('Click to choose a category') }}</span>
            <div class="menu transition hidden">
                @foreach($categories as $item)
                    <div class="item" data-value="{{ $item->id }}">{{ $item->title }}</div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="four wide field">
        <div class="ui hidden divider"></div>
        <div class="ui toggle checkbox">
            <input type="checkbox" name="featured" id="featured" value="1" @if (old('featured', isset($featured)?$featured:false)) checked="checked" @endif>
            <label for="featured">Feature This Event</label>
        </div>
    </div>
</div>

@section('head')
<link rel="stylesheet" type="text/css" href="/css/daterangepicker.min.css">
{{-- <script type="text/javascript" src="/js/trix.js"></script> --}}
@endsection

@section('scripts')
<script type="text/javascript" src="/js/daterangepicker.min.js"></script>
<script>
    window.jQuery('.ui.daterangepicker').daterangepicker({
        singleDatePicker: true,
        format: 'YYYY-MM-DD h:mm A',
        timePicker: true,
        timePickerIncrement: 15,
        autoApply: true,
        showCustomRangeLabel: false,
        opens: "center",
    });
</script>
@endsection