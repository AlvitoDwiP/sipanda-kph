<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Shared\BaseDisplayJobdeskController;

/**
 * Controller Display Job Desk untuk role KPH.
 * Seluruh logika ada di BaseDisplayJobdeskController.
 */
class DisplayJobdeskController extends BaseDisplayJobdeskController
{
    protected function routePrefix(): string
    {
        return 'kph';
    }
}
