<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class Display extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/display/wsdl';
}

