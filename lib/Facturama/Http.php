<?php
/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama_Http
{
    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function post($pathUrl, $data)
    {
        $username = Facturama_Configuration::getUserName();
        $password = Facturama_Configuration::getPassword();

        $response = Facturama_HttpCurl::doRequest('POST', $pathUrl, $data, $username, $password);
        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function get($pathUrl, $data)
    {
        $username = Facturama_Configuration::getMerchantPosId();
        $password = Facturama_Configuration::getSignatureKey();

        $response = Facturama_HttpCurl::doRequest('GET', $pathUrl, $data, $username, $password);

        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function put($pathUrl, $data)
    {
        $username = Facturama_Configuration::getMerchantPosId();
        $password = Facturama_Configuration::getSignatureKey();

        $response = Facturama_HttpCurl::doRequest('PUT', $pathUrl, $data, $username, $password);

        return $response;
    }

    /**
     * @param $pathUrl
     * @param $data
     * @return mixed
     */
    public static function delete($pathUrl, $data)
    {
        $username = Facturama_Configuration::getMerchantPosId();
        $password = Facturama_Configuration::getSignatureKey();

        $response = Facturama_HttpCurl::doRequest('DELETE', $pathUrl, $data, $username, $password);

        return $response;
    }

    /**
     *
     *
     * @param $statusCode
     * @param null $message
     * @throws Facturama_Exception
     * @throws Facturama_Exception_Authorization
     * @throws Facturama_Exception_Network
     * @throws Facturama_Exception_ServerMaintenance
     * @throws Facturama_Exception_ServerError
     */
    public static function throwHttpStatusException($statusCode, $message = null)
    {
        switch ($statusCode) {
            default:
                throw new Facturama_Exception_Network('Unexpected HTTP code response', $statusCode);
                break;

            case 400:
                throw new Facturama_Exception($message->getStatus().' - '.$message->getResponse(), $statusCode);
                break;

            case 401:
            case 403:
                throw new Facturama_Exception_Authorization($message->getStatus().' - '.$message->getResponse(), $statusCode);
                break;


            case 404:
                throw new Facturama_Exception_Network('Data indicated in the request is not available in the Facturama system.');
                break;

            case 408:
                throw new Facturama_Exception_ServerError('Request timeout', $statusCode);
                break;

            case 500:
                throw new Facturama_Exception_ServerError('Facturama system is unavailable or your order is not processed.
                Error:
                [' . ($message->getResponse() ? $message->getResponse() : '') . ']', $statusCode);
                break;

            case 503:
                throw new Facturama_Exception_ServerMaintenance('Service unavailable', $statusCode);
                break;
        }
    }
}
