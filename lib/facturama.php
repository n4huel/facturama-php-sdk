<?php

/**
 * Facturama Standard Library
 * ver. 1.0
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://facturama.mx
*/

define('FACTURAMA_LIBRARY', true);

include_once('Facturama/Util.php');
include_once('Facturama/Exception.php');
include_once('Facturama/Facturama.php');

include_once('Facturama/Result.php');
include_once('Facturama/Configuration.php');

include_once('Facturama/Entities/Product.php');
include_once('Facturama/Entities/Cfdi.php');

require_once('Facturama/Http.php');
require_once('Facturama/HttpCurl.php');
