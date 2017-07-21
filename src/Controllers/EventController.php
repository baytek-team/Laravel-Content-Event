<?php

namespace Baytek\Laravel\Content\Types\Events\Controllers;

use Baytek\Laravel\Content\Types\Events\Models\Event;
use Baytek\Laravel\Content\Types\Events\Models\Category;
use Baytek\Laravel\Content\Types\Events\Requests\EventRequest;
use Baytek\Laravel\Content\Types\Events\Scopes\ApprovedEventScope;

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
     * @var Baytek\Laravel\Content\Types\Events\Models\Event
     */
    protected $model = Event::class;
    protected $request = EventRequest::class;

    protected $viewPrefix = 'admin/events';

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'index',
        'create' => 'create',
        'edit' => 'edit',
        'show' => 'show',
        'translate' => 'translate',
    ];

    protected $redirectsKey = 'event';

    /**
     * [__construct description]
     *
     * @return  null
     */
    public function __construct()
    {
        $this->loadViewsFrom(resource_path().'/views', 'event');

        parent::__construct();
    }

    /**
     * Show the index of all content with content type 'event'
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicID = null)
    {
        // if(!is_null($topicID)) {
        //     $this->viewData['index'] = [
        //         'discussions' => Discussion::approved()->childrenOfType(Event::find($topicID)->key, 'discussion')->paginate(),
        //         'filter' => 'active',
        //     ];
        // }
        // else {
        //     $this->viewData['index'] = [
        //         'discussions' => Discussion::approved()->ofType('discussion')->paginate(),
        //         'filter' => 'active',
        //     ];
        // }

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