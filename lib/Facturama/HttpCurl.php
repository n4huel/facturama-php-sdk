<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama_HttpCurl
{
    /**
     * @var
     */
    static $headers;

    /**
     * @param $requestType
     * @param string $pathUrl
     * @param $data
     * @param $user
     * @param $password
     * @return mixed
     * @throws Facturama_Exception_Configuration
     * @throws Facturama_Exception_Network
     * @throws Facturama_Exception_Authorization
     */
    public static function doRequest($requestType, $pathUrl, $data, $user, $password)
    {
        if (empty($pathUrl))
            throw new Facturama_Exception_Configuration('The endpoint is empty');

        if (empty($user)) {
            throw new Facturama_Exception_Configuration('PosId is empty');
        }

        if (empty($password)) {
            throw new Facturama_Exception_Configuration('SignatureKey is empty');
        }

        $userNameAndPassword = $user.":".$password;

        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );

        $ch = curl_init($pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'Facturama_HttpCurl::readHeader');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $userNameAndPassword);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        var_dump($data);
        $response = curl_exec($ch);
  

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($response === false) {
            throw new Facturama_Exception_Network(curl_error($ch));
        }
        curl_close($ch);

        return array('code' => $httpStatus, 'response' => trim($response));
    }

    /**
     * @param resource $ch
     * @param string $header
     * @return int
     */
    public static function readHeader($ch, $header)
    {
        if( preg_match('/([^:]+): (.+)/m', $header, $match) ) {
            self::$headers[$match[1]] = trim($match[2]);
        }
        return strlen($header);
    }

}
