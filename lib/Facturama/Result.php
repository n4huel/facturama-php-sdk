<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama_Result
{
    private $message = '';
    private $error = '';
    private $error_list = '';

    private $status = '';
    private $success = 0;

    private $request = '';
    private $response = '';
    

    /**
     * @access public
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @access public
     * @param $value
     */
    public function setStatus($value)
    {
        $this->status = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @access public
     * @param $value
     */
    public function setError($value)
    {
        $this->error = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getErrors()
    {
        return $this->error_list;
    }

    /**
     * @access public
     * @param $value
     */
    public function setErrors($value)
    {
        $this->error_list = $value;
    }

    /**
     * @access public
     * @return int
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @access public
     * @param $value
     */
    public function setSuccess($value)
    {
        $this->success = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @access public
     * @param $value
     */
    public function setRequest($value)
    {
        $this->request = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @access public
     * @param $value
     */
    public function setResponse($value)
    {
        $this->response = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @access public
     * @param $value
     */
    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function init($attributes)
    {
        $attributes = Facturama_Util::parseArrayToObject($attributes);

        if (!empty($attributes)) {
            foreach ($attributes as $name => $value) {
                $this->set($name, $value);
            }
        }
    }

    public function set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        if (isset($this->{$name}))
            return $this->name;

        return null;
    }

    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!property_exists($this, $property)) {
                throw new Exception('Property ' . $property . ' not exists');
            }
            switch($matches[1]) {
                case 'get':
                    $this->checkArguments($args, 0, 0, $methodName);
                    return $this->get($property);
                case 'default':
                    throw new Exception('Method ' . $methodName . ' not exists');
            }
        }
    }
}