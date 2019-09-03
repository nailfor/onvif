<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class DeviceIO extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/deviceIO/wsdl';
}

