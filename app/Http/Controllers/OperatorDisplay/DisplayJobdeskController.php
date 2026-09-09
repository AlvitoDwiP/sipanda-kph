<?php

namespace App\Http\Controllers\OperatorDisplay;

use App\Http\Controllers\Shared\BaseDisplayJobdeskController;

/**
 * Controller Display Job Desk untuk role Operator Display.
 * Seluruh logika ada di BaseDisplayJobdeskController.
 *
 * Sebelumnya menggunakan Admin\DisplayJobdeskController secara langsung
 * (lintas namespace) — sekarang memiliki controller sendiri yang proper.
 */
class DisplayJobdeskController extends BaseDisplayJobdeskController
{
    protected function routePrefix(): string
    {
        return 'operator-display';
    }
}
