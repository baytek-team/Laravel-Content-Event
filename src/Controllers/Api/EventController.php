<?php

namespace Baytek\Laravel\Content\Types\Event\Controllers\Api;

use Baytek\Laravel\Content\Types\Event\Models\Event;
use Baytek\Laravel\Content\Types\Event\Scopes\EventScope;
use Baytek\Laravel\Content\Types\Event\Scopes\ApprovedEventScope;

use Baytek\Laravel\Content\Controllers\ApiController;
use Illuminate\Http\Request;

use Carbon\Carbon;

class EventController extends ApiController
{
	public function all()
    {
    	return Event::withoutGlobalScope(ApprovedEventScope::class)->get();
    }

    public function past()
    {
        return Event::past()
            ->paginate(5);
    }

    public function upcoming()
    {
        return Event::upcoming()
            ->withoutGlobalScope(EventScope::class)
            ->ofType('event')
            ->withContents()
            ->with(['relations', 'relations.relation', 'relations.relationType', 'meta'])
            ->orderByMeta('event_date', 'asc')
            ->paginate(5);
    }

    public function get($event)
    {
        return Event::where('contents.key', $event)
            ->get()
            ->first();
    }

    public function range($start, $end)
    {
        return Event::withinRange(Carbon::createFromFormat('YmdH', $start.'00'), Carbon::createFromFormat('YmdHi', $end.'2359'))
            ->get();
    }

    public function dashboard()
    {
        return Event::upcoming()
            ->withoutGlobalScope(EventScope::class)
            ->ofType('event')
            ->withContents()
            ->with(['relations', 'relations.relation', 'relations.relationType', 'meta'])
            ->orderByMeta('event_date', 'asc')
            ->paginate(3);
    }
}
