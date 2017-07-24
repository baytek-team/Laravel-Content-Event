<?php

namespace Baytek\Laravel\Content\Types\Event\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;

use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy extends GeneralPolicy
{
    public $contentType = 'Event Category';
}
