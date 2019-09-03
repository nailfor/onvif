<?php 
namespace nailfor\onvif\Service;

use nailfor\onvif\Soap\SoapClient;

class Imaging extends SoapClient
{
    protected $api_uri = 'http://www.onvif.org/ver20/imaging/wsdl';
}

