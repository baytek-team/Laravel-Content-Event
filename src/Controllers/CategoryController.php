<?php

namespace Baytek\Laravel\Content\Types\Event\Controllers;

use Baytek\Laravel\Content\Types\Event\Models\Category;
use Baytek\Laravel\Content\Types\Event\Requests\CategoryRequest;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Events\ContentEvent;

class CategoryController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the events
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = Category::class;

    /**
     * [$viewPrefix description]
     * @var string
     */
    protected $viewPrefix = 'admin';
    /**
     * Namespace from which to load the view
     * @var string
     */
    protected $viewNamespace = 'events';
    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'category.index',
        'create' => 'category.create',
        'edit' => 'category.edit',
        'show' => 'category.index',
    ];

    protected $redirectsKey = 'event.category';

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->viewData['index'] = [
            'categories' => Category::withoutGlobalScopes()
                ->childrenOf('event-category')
                ->orderBy('title', 'asc')
                ->paginate(),
        ];

        return parent::contentIndex();
    }


    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $this->viewData['index'] = [
            'categories' => Category::withoutGlobalScopes()
                ->childrenOfType(Category::find($id)->key, 'event-category')
                ->paginate(),
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
            'parent' => Content::where('contents.key', 'event-category')->get()->first(),
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created events in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->redirects = false;

        $request->merge(['key' => str_slug($request->title)]);

        $category = parent::contentStore($request);
        $category->saveRelation('parent-id', $request->parent_id);
        $category->onBit(Category::APPROVED)->save();

        event(new ContentEvent($category));

        return redirect(route($this->redirectsKey.'.index', $category));
    }

    /**
     * Update a category using the parent method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        return parent::contentUpdate($request, $id);
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return parent::contentEdit($id);
    }

    /**
     * Delete an event category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = $this->bound($id);

        $event->offBit(Category::APPROVED)->onBit(Category::DELETED)->update();

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($event));

        flash('Category Removed')->success();

        return back(); //so we stay on the same filter
    }
}
