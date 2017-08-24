<?php

namespace Baytek\Laravel\Content\Types\Event\Models;

use Baytek\Laravel\Content\Types\Event\Scopes\EventScope;
use Baytek\Laravel\Content\Types\Event\Scopes\ApprovedEventScope;

use Baytek\Laravel\Content\Models\Content;

use Carbon\Carbon;

class Event extends Content
{

    /**
    * Content keys that will be saved to the relation tables
    * @var Array
    */
    public $relationships = [
        'content-type' => 'event'
    ];

    public $translatableMetadata = [
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new EventScope);
        static::addGlobalScope(new ApprovedEventScope);
        parent::boot();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getEventDateAttribute()
    {
        return new Carbon($this->getMeta('event_date'));
    }

    /**
     * Scope a query to only include approved events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->whereDate('metadata_order.value', '<', Carbon::today());
    }

    /**
     * Scope a query to only include pending events (require moderation).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->whereDate('metadata_order.value', '>=', Carbon::today());
    }

    /**
     * Scope a query to only include pending events (require moderation).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->withStatus(self::FEATURED);
    }

    /**
     * Scope a query to include events within a specified range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinRange($query, $start, $end)
    {
        return $query->whereDate('metadata_order.value', '>=', $start)
            ->whereDate('metadata_order.value', '<=', $end);
    }

    /**
     * Scope a query to only include deleted events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeleted($query)
    {
        return $query->withStatus(Event::DELETED);
    }

    public function categoryID()
    {
        return $this->relatedBy('category')->pluck('relation_id')->first();
    }
}
