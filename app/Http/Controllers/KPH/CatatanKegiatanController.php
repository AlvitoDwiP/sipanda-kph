<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Shared\BaseCatatanKegiatanController;

/**
 * Controller catatan kegiatan untuk role KPH.
 * Seluruh logika verifikasi ada di BaseCatatanKegiatanController.
 */
class CatatanKegiatanController extends BaseCatatanKegiatanController
{
    protected function routePrefix(): string
    {
        return 'kph';
    }
}
