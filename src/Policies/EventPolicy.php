<?php

namespace Baytek\Laravel\Content\Types\Events\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;

use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends GeneralPolicy
{
    public $contentType = 'Event';
}
