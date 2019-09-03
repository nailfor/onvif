<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class Receiver extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/receiver/wsdl';
}

