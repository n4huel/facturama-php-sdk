<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

class Facturama
{
	private $url_service = '';

	function __construct($url) {
    	$this->url_service = $url;
    }

    /**
     * Creates new 
     * - Sends to Facturama CreateRequest
     *
     * @access public
     * @param array $request A array containing full 
     * @return object $result Response array with CreateResponse
     * @throws Facturama_Exception
     */
    public function create($request)
    {
        $pathUrl = Facturama_Configuration::getServiceUrl() . $this->url_service;
        $data = Facturama_Util::buildJsonFromArray($request);
        if (empty($data)) {
            throw new Facturama_Exception('Empty message CreateRequest');
        }
        $result = self::verifyResponse(Facturama_Http::post($pathUrl, $data), 'CreateResponse');
        return $result;
    }

    /**
     * Retrieves information about the request
     *  - Sends to Facturama RetrieveRequest
     *
     * @access public
     * @param string $requestId Facturama Id sent back in CreateResponse
     * @return Facturama_Result $result Response array with RetrieveResponse
     * @throws Facturama_Exception
     */
    public function retrieve($requestId)
    {
        if (empty($requestId)) {
            throw new Facturama_Exception('Empty value of requestId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . $this->url_service . $requestId;

        $result = self::verifyResponse(Facturama_Http::get($pathUrl, $pathUrl), 'RetrieveResponse');

        return $result;
    }

    /**
     * Cancels 
     * - Sends to Facturama CancelRequest
     *
     * @access public
     * @param string $requestId Facturama Id sent back in CreateResponse
     * @return Facturama_Result $result Response array with CancelResponse
     * @throws Facturama_Exception
     */
    public function delete($requestId)
    {
        if (empty($requestId)) {
            throw new Facturama_Exception('Empty value of requestId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . $this->url_service . $requestId;

        $result = self::verifyResponse(Facturama_Http::delete($pathUrl, $pathUrl), 'CancelResponse');
        return $result;
    }

    /**
     * Updates  status
     * - Sends to Facturama StatusUpdateRequest
     *
     * @access public
     * @param string $requestStatus A array containing full Status
     * @return Facturama_Result $result Response array with StatusUpdateResponse
     * @throws Facturama_Exception
     */
    public function update($requestStatusUpdate)
    {
        $data = array();
        if (empty($requestStatusUpdate)) {
            throw new Facturama_Exception('Empty request status data');
        }

        $data = Facturama_Util::buildJsonFromArray($requestStatusUpdate);
        $requestId = $requestStatusUpdate['requestId'];

        $pathUrl = Facturama_Configuration::getServiceUrl() . $this->url_service . $requestId . '/status';

        $result = self::verifyResponse(Facturama_Http::put($pathUrl, $data), 'UpdateResponse');
        return $result;
    }

    /**
     * Verify response from Facturama
     *
     * @param string $response
     * @param string $messageName
     * @return null|Facturama_Result
     */
    public static function verifyResponse($response, $messageName)
    {
        $data = array();
        $httpStatus = $response['code'];

        $message = Facturama_Util::convertJsonToArray($response['response'], true);
        $data['status'] = $httpStatus;
        $data['response'] = '';
        $data['request'] = '';
        $data['error'] = '';
        $data['errors'] = [];
        $data['message'] = '';

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
            $data['success'] = false;
        } elseif (isset($message['Message'])) {
             if(isset($message['ModelState'])){
                $errors = [];
                foreach ($message['ModelState'] as $attr_errors) {
                    foreach ($attr_errors as $error) {
                        array_push($errors, $error);    
                    }
                }
                $data['error'] = $errors[0];
                $data['error_list'] = $errors;
            }
            $data['message'] = $message['Message'];
            $data['success'] = false;
        } elseif (isset($message)) {
            $data['response'] = $message;
            $data['success'] = true;
        }

        $result = self::build($data);
        if ($httpStatus == 200 || $httpStatus == 201 || $httpStatus == 422 || 
            $httpStatus == 301 || $httpStatus == 302 || $httpStatus == 400 || $httpStatus == 404)
        {
            return $result;
        } else {
            Facturama_Http::throwHttpStatusException($httpStatus, $result);
        }
        return null;
    }

    protected static function build($data)
    {
        $instance = new Facturama_Result();
        $instance->init($data);
        return $instance;
    }
}