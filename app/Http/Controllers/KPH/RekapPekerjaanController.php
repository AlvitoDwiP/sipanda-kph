<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Shared\BaseRekapPekerjaanController;

/**
 * Controller Rekap Pekerjaan untuk role KPH.
 * Seluruh logika ada di BaseRekapPekerjaanController.
 */
class RekapPekerjaanController extends BaseRekapPekerjaanController
{
    protected function routePrefix(): string
    {
        return 'kph';
    }
}
