<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class Recording extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/recording/wsdl';
}

