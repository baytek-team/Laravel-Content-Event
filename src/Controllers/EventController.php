<?php

namespace Baytek\Laravel\Content\Types\Event\Controllers;

use Baytek\Laravel\Content\Types\Event\Models\Event;
use Baytek\Laravel\Content\Types\Event\Models\Category;
use Baytek\Laravel\Content\Types\Event\Requests\EventRequest;
use Baytek\Laravel\Content\Types\Event\Scopes\ApprovedEventScope;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Events\ContentEvent;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;
use View;

class EventController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the event
     *
     * @var Baytek\Laravel\Content\Types\Event\Models\Event
     */
    protected $model = Event::class;
    protected $request = EventRequest::class;

    protected $viewPrefix = 'admin';

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'event.index',
        'create' => 'event.create',
        'edit' => 'event.edit',
        'show' => 'event.show',
        'translate' => 'event.translate',
    ];

    protected $redirectsKey = 'event';

    /**
     * Show the index of all content with content type 'event'
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicID = null)
    {
        $this->viewData['index'] = [
            'events' => Event::paginate(),
            'filter' => 'all',
        ];

        return parent::contentIndex();
    }

    /**
     * Show the index of all content with content type 'events'
     *
     * @return \Illuminate\Http\Response
     */
    public function past()
    {
        $this->viewData['index'] = [
            'events' => Event::past()->paginate(),
            'filter' => 'past',
        ];

        return parent::contentIndex();
    }

    /**
     * Show the index of all content with content type 'events'
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming()
    {
        $this->viewData['index'] = [
            'events' => Event::upcoming()->paginate(),
            'filter' => 'upcoming',
        ];

        return parent::contentIndex();
    }

    /**
     * Show the index of all featured events
     *
     * @return \Illuminate\Http\Response
     */
    public function featured()
    {
        $this->viewData['index'] = [
            'events' => Event::featured()->paginate(),
            'filter' => 'featured',
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['create'] = [
            'categories' => Category::all(),
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['key' => str_slug((new Carbon($request->event_date))->toDateString().' '.$request->title)]);

        Validator::make(
            $request->all(),
            (new $this->request)->rules(),
            (new $this->request)->messages()
        )->validate();

        $this->redirects = false;

        $event = parent::contentStore($request);
        $event->saveRelation('category', $request->category);
        $event->saveRelation('parent-id', content_id('content-type/event'));
        $event->saveMetadata('event_date', (new Carbon($request->event_date))->toDateTimeString());

        $event->onBit(Event::APPROVED)->update();

        //If featured, add the featured status bit
        if ($request->featured) {
            $event->onBit(Event::FEATURED)->update();
        }

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($event));

        return redirect(route('event.edit', $event->id));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $this->viewData['show'] = [
            'event' => $event,
            'categories' => Category::all(),
        ];

        return parent::contentShow($event);
    }

    /**
     * Update a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->merge(['key' => str_slug((new Carbon($request->event_date))->toDateString().' '.$request->title)]);

        Validator::make(
            $request->all(),
            (new $this->request)->rules(),
            (new $this->request)->messages()
        )->validate();

        $this->redirects = false;

        $event = parent::contentUpdate($request, $id);
        $event->removeRelationByType('category');
        $event->saveRelation('category', $request->category);
        $event->saveMetadata('event_date', (new Carbon($request->event_date))->toDateTimeString());

        //Update the featured status
        if ($request->featured && !$event->hasStatus(Event::FEATURED)) {
            $event->onBit(Event::FEATURED)->update();
        }
        else if (!$request->featured && $event->hasStatus(Event::FEATURED)) {
            $event->offBit(Event::FEATURED)->update();
        }

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($event));

        return redirect(route('event.edit', $event->id));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = $this->bound($id);

        $this->viewData['edit'] = [
            'category' => $event->categoryID(),
            'categories' => Category::all(),
            'featured' => $event->hasStatus(Event::FEATURED),
        ];

        return parent::contentEdit($id);
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = $this->bound($id);

        $event->offBit(Event::APPROVED)->onBit(Event::DELETED)->update();

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($event));

        return back(); //so we stay on the same filter

        // return redirect(route('event.index'));
    }
}