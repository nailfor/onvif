<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class AnalyticsDevice extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/analyticsdevice/wsdl';
}

