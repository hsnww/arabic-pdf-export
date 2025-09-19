<?php

namespace ArabicPdfExport\Facades;

use Illuminate\Support\Facades\Facade;

class ArabicPdf extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'arabic-pdf';
    }
}
