<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama
{
    protected static function build($data)
    {
        $instance = new Facturama_Result();
        $instance->init($data);

        return $instance;
    }

}