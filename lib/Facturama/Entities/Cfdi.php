<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

/**
 * Class Facturama_Cfdi
 */
class Facturama_Cfdi extends Facturama
{
    const PRODUCT_SERVICE = 'Cfdi/';
    function __construct() {
       parent::__construct(self::PRODUCT_SERVICE);
    }
}
