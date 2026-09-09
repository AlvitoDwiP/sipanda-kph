<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Shared\BaseDisplayJobdeskController;

/**
 * Controller Display Job Desk untuk role Admin.
 * Seluruh logika ada di BaseDisplayJobdeskController.
 */
class DisplayJobdeskController extends BaseDisplayJobdeskController
{
    protected function routePrefix(): string
    {
        return 'admin';
    }
}
