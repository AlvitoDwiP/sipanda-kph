<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Shared\BasePenugasanController;

/**
 * Controller penugasan untuk role Admin.
 * Seluruh logika bisnis ada di BasePenugasanController.
 */
class PenugasanController extends BasePenugasanController
{
    protected function routePrefix(): string
    {
        return 'admin';
    }

}
