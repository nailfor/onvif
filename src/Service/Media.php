<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class Media extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/media/wsdl';
}

