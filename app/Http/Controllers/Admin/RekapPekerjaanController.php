<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Shared\BaseRekapPekerjaanController;

/**
 * Controller Rekap Pekerjaan untuk role Admin.
 * Seluruh logika ada di BaseRekapPekerjaanController.
 */
class RekapPekerjaanController extends BaseRekapPekerjaanController
{
    protected function routePrefix(): string
    {
        return 'admin';
    }
}
