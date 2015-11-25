<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama_Configuration
{
    private static $_availableEnvironment = array('custom', 'secure');

    private static $env = 'secure';
    private static $username = '';
    private static $password = '';

    private static $serviceUrl = '';
    private static $serviceDomain = '';

    const COMPOSER_JSON = "/composer.json";
    const DEFAULT_SDK_VERSION = 'PHP SDK 2.1.4';

    /**
     * @access public
     * @param string $environment
     * @param string $domain
     * @param string $api
     * @param string $version
     * @throws Facturama_Exception_Configuration
     */
    public static function setEnvironment($environment = 'secure', $domain = 'api.facturama.com.mx', $api = 'api/')
    {
        $environment = strtolower($environment);
        $domain = strtolower($domain) . '/';

        if (!in_array($environment, self::$_availableEnvironment)) {
            throw new Facturama_Exception_Configuration($environment . ' - is not valid environment');
        }

        if ($environment == 'secure') {
            self::$env = $environment;
            self::$serviceDomain = $domain;
            self::$serviceUrl = 'https://' . $domain . $api;
        } else if ($environment == 'custom') {
            self::$env = $environment;
            self::$serviceUrl = $domain . $api;
        }
    }

    /**
     * @access public
     * @return string
     */
    public static function getServiceUrl()
    {
        return self::$serviceUrl;
    }

    /**
     * @access public
     * @return string
     */
    public static function getEnvironment()
    {
        return self::$env;
    }

    /**
     * @access public
     * @param string
     */
    public static function setUserName($value)
    {
        self::$username = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getUserName()
    {
        return self::$username;
    }

    /**
     * @access public
     * @param string
     */
    public static function setPassword($value)
    {
        self::$password = trim($value);
    }

    /**
     * @access public
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @return string
     */
    private static function getComposerFilePath()
    {
        return realpath(dirname(__FILE__)) . '/../../' . self::COMPOSER_JSON;
    }
}
