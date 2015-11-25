<?php

/**
 * Facturama Standard Library
 *
 * @copyright  Copyright (c) 2011-2015 Facturama
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 */

/**
 * Class Facturama_Product
 */
class Facturama_Product extends Facturama
{
    const PRODUCT_SERVICE = 'Product/';
    /**
     * Creates new Product
     * - Sends to Facturama ProductCreateRequest
     *
     * @access public
     * @param array $order A array containing full Product
     * @return object $result Response array with ProductCreateResponse
     * @throws Facturama_Exception
     */
    public static function create($order)
    {
        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE;
        $data = Facturama_Util::buildJsonFromArray($order);

        if (empty($data)) {
            throw new Facturama_Exception('Empty message ProductCreateRequest');
        }

        $result = self::verifyResponse(Facturama_Http::post($pathUrl, $data), 'ProductCreateResponse');

        return $result;
    }

    /**
     * Retrieves information about the order
     *  - Sends to Facturama ProductRetrieveRequest
     *
     * @access public
     * @param string $orderId Facturama ProductId sent back in ProductCreateResponse
     * @return Facturama_Result $result Response array with ProductRetrieveResponse
     * @throws Facturama_Exception
     */
    public static function retrieve($orderId)
    {
        if (empty($orderId)) {
            throw new Facturama_Exception('Empty value of orderId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE . $orderId;

        $result = self::verifyResponse(Facturama_Http::get($pathUrl, $pathUrl), 'ProductRetrieveResponse');

        return $result;
    }

    /**
     * Cancels Product
     * - Sends to Facturama ProductCancelRequest
     *
     * @access public
     * @param string $orderId Facturama ProductId sent back in ProductCreateResponse
     * @return Facturama_Result $result Response array with ProductCancelResponse
     * @throws Facturama_Exception
     */
    public static function cancel($orderId)
    {
        if (empty($orderId)) {
            throw new Facturama_Exception('Empty value of orderId');
        }

        $pathUrl = Facturama_Configuration::getServiceUrl() . self::PRODUCT_SERVICE . $orderId;

        $result = self::verifyResponse(Facturama_Http::delete($pathUrl, $pathUrl), 'ProductCancelResponse');
        return $result;
    }

    /**
     * Updates Product status
     * - Sends to Facturama ProductStatusUpdateRequest
     *
     * @access public
     * @param string $orderStatus A array containing full ProductStatus
     * @return Facturama_Result $result Response array with ProductStatusUpdateResponse
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

        $result = self::verifyResponse(Facturama_Http::put($pathUrl, $data), 'ProductStatusUpdateResponse');
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
            $data['response'] = $message['Message'];
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
}
