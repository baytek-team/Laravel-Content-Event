<?php

namespace Baytek\Laravel\Content\Types\Event\Models;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Types\Event\Models\Resource;
use Baytek\Laravel\Content\Types\Event\Scopes\CategoryScope;

use Cache;

class Category extends Content
{
	/**
	 * Meta keys that the content expects to save
	 * @var Array
	 */
	// protected $meta = [
	// 	'author_id'
	// ];

	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'event-category'
	];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new CategoryScope);

        parent::boot();
    }

    /**
     * Scope a query to only include deleted members.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->withStatus(self::APPROVED);
    }
}
