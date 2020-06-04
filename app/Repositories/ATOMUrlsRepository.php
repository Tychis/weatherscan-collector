<?php

namespace App\Repositories;

use App\Models\XMLSearchURLs;

class ATOMUrlsRepository implements ATOMUrlsInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return XMLSearchURLs::all();
    }
}
