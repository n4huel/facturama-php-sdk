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
    /**
     * Creates new Cfdi
     * - Sends to Facturama CfdiCreateRequest
     *
     * @access public
     * @param array $order A array containing full Cfdi
     * @return object $result Response array with CfdiCreateResponse
     * @throws Facturama_Exception
     */
    public static function create($order)
    {
        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE;
        $data = Facturama_Util::buildJsonFromArray($order);

        if (empty($data)) {
            throw new Facturama_Exception('Empty message CfdiCreateRequest');
        }

        $result = self::verifyResponse(Facturama_Http::post($pathUrl, $data), 'CfdiCreateResponse');

        return $result;
    }

    /**
     * Retrieves information about the order
     *  - Sends to Facturama CfdiRetrieveRequest
     *
     * @access public
     * @param string $orderId Facturama CfdiId sent back in CfdiCreateResponse
     * @return Facturama_Result $result Response array with CfdiRetrieveResponse
     * @throws Facturama_Exception
     */
    public static function retrieve($orderId)
    {
        if (empty($orderId)) {
            throw new Facturama_Exception('Empty value of orderId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE . $orderId;

        $result = self::verifyResponse(Facturama_Http::get($pathUrl, $pathUrl), 'CfdiRetrieveResponse');

        return $result;
    }

    /**
     * Cancels Cfdi
     * - Sends to Facturama CfdiCancelRequest
     *
     * @access public
     * @param string $orderId Facturama CfdiId sent back in CfdiCreateResponse
     * @return Facturama_Result $result Response array with CfdiCancelResponse
     * @throws Facturama_Exception
     */
    public static function cancel($orderId)
    {
        if (empty($orderId)) {
            throw new Facturama_Exception('Empty value of orderId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE . $orderId;

        $result = self::verifyResponse(Facturama_Http::delete($pathUrl, $pathUrl), 'CfdiCancelResponse');
        return $result;
    }

    /**
     * Updates Cfdi status
     * - Sends to Facturama CfdiStatusUpdateRequest
     *
     * @access public
     * @param string $orderStatus A array containing full CfdiStatus
     * @return Facturama_Result $result Response array with CfdiStatusUpdateResponse
     * @throws Facturama_Exception
     */
    public static function statusUpdate($orderStatusUpdate)
    {
        $data = array();
        if (empty($orderStatusUpdate)) {
            throw new Facturama_Exception('Empty order status data');
        }

        $data = Facturama_Util::buildJsonFromArray($orderStatusUpdate);
        $orderId = $orderStatusUpdate['orderId'];

        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE . $orderId . '/status';

        $result = self::verifyResponse(Facturama_Http::put($pathUrl, $data), 'CfdiStatusUpdateResponse');
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
        // Custom your error here
        // ....
        $data = array();
        $httpStatus = $response['code'];

        $message = Facturama_Util::convertJsonToArray($response['response'], true);
        $data['status'] = $httpStatus;

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
            $data['success'] = false;
        } elseif (isset($message['Message'])) {
            $data['message'] = $message['Message'];
            $data['success'] = false;
            if(isset($message['ModelState'])){
                $data['error'] = array_shift($message['ModelState'])[0];
            }
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
}
