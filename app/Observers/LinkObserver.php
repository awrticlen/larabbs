<?php

namespace App\Observers;

use App\Models\Link;

class LinkObserver
{
    public function saved(Link $link): void
    {
        Link::forgetCache();
    }

    public function deleted(Link $link): void
    {
        Link::forgetCache();
    }
}