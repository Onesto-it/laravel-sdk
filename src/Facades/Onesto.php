<?php

namespace OnestoIt\Sdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|null createInvoiceManually(array $data)
 * @method static array|null createInvoiceFromPIVA(array $data)
 *
 * @see \OnestoIt\Sdk\Onesto
 */
class Onesto extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'onesto';
    }
}
