<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class Device extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/device/wsdl';
    protected $url = '/onvif/device';
}

