<?php 
namespace nailfor\onvif\Service\Extension;

use nailfor\onvif\Soap\SoapClient;

class Search extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver10/search/wsdl';
}

