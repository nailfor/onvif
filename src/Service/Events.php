<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class Events extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/events/wsdl';
}

