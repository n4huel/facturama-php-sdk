<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama_Util
{
    /**
     * Function builds Facturama Json Document
     *
     * @param string $data
     * @param string $rootElement
     *
     * @return null|string
     */
    public static function buildJsonFromArray($data, $rootElement = '')
    {
        if (!is_array($data)) {
            return null;
        }

        if (!empty($rootElement)) {
            $data = array($rootElement => $data);
        }
        return json_encode($data);
    }

    /**
     * @param string $data
     * @param bool $assoc
     * @return mixed|null
     */
    public static function convertJsonToArray($data, $assoc = false)
    {
        if (empty($data)) {
            return null;
        }

        return json_decode($data, $assoc);
    }

    /**
     * @param array $array
     * @return bool|stdClass
     */
    public static function parseArrayToObject($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        if (self::isAssocArray($array)){
            $object = new stdClass();
        }
        else{
            $object = array();
        }

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = trim($name);
                if (isset($name)){
                    if (is_numeric($name)){
                        $object[] = self::parseArrayToObject($value);
                    }
                    else{
                        $object->$name = self::parseArrayToObject($value);
                    }
                }
            }
            return $object;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public static function getRequestHeaders()
    {
        if(!function_exists('apache_request_headers')) {
                $headers = array();
                foreach($_SERVER as $key => $value) {
                    if(substr($key, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
                    }
                }
                return $headers;
        }else{
            return apache_request_headers();
        }

    }
    /**
     * @param $array
     * @param string $namespace
     * @return string
     */
    public static function convertArrayToHtmlForm($array, $namespace = "", &$outputFields)
    {
        $i = 0;
        $htmlOutput = "";
        $assoc = self::isAssocArray($array);

        foreach ($array as $key => $value) {

            //Temporary important changes only for order by form method
            $key = self::changeFormFieldFormat($namespace, $key);

            if ($namespace && $assoc) {
                $key = $namespace . '.' . $key;
            } elseif ($namespace && !$assoc) {
                $key = $namespace . '[' . $i++ . ']';
            }

            if (is_array($value)) {
                $htmlOutput .= self::convertArrayToHtmlForm($value, $key, $outputFields);
            } else {
                $htmlOutput .= sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n", $key, $value);
                $outputFields[$key] = $value;
            }
        }
        return $htmlOutput;
    }

    /**
     * @param $arr
     * @return bool
     */
    public static function isAssocArray($arr)
    {
        $arrKeys = array_keys($arr);
        sort($arrKeys, SORT_NUMERIC);
        return $arrKeys !== range(0, count($arr) - 1);
    }

    /**
     * @param $namespace
     * @param $key
     * @return string
     */
    public static function changeFormFieldFormat($namespace, $key)
    {

        if ($key === $namespace && $key[strlen($key) - 1] == 's') {
            return substr($key, 0, -1);
        }
        return $key;
    }

}
