<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Shared\BasePenugasanController;

/**
 * Controller penugasan untuk role KPH.
 * Seluruh logika bisnis ada di BasePenugasanController.
 */
class PenugasanController extends BasePenugasanController
{
    protected function routePrefix(): string
    {
        return 'kph';
    }

}
