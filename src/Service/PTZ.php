<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class PTZ extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/ptz/wsdl';
}

