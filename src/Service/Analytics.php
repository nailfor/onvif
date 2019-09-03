<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class Analytics extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/analytics/wsdl';
}

