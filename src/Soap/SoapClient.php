<?php 
namespace nailfor\onvif\Soap;

use nailfor\onvif\Exceptions\AuthorizeException;
use nailfor\onvif\Exceptions\SoapException;

class SoapClient extends \SoapClient
{
    protected $url = '';
    protected $api_uri = '';
    protected $api_location = '';
    protected $api_username = '';
    protected $api_password = '';

    protected function getLocation($uri) : string
    {
        $url = parse_url($uri);
        $scheme = $url['scheme'] ?? 'http';
        $host   = $url['host'] ?? '';
        $path   = $url['path'] ?? '';
        $port   = $url['port'] ?? '';
        if (!$host) {
            $host = $path;
            $path = '';
        }
        if ($port) {
            $host.=":$port";
        }
        
        return "$scheme://$host$path".$this->url;
    }
    
    public function __construct($uri, $username, $password)
    {
        $location = $this->getLocation($uri);
        $options = [
            'location'  => $location,
            'uri'       => $this->api_uri,
            'trace'     => true,
            'exceptions'=> true,
            'use'       => SOAP_LITERAL,
            'features'  => SOAP_USE_XSI_ARRAY_TYPE|SOAP_WAIT_ONE_WAY_CALLS,
        ];

        $this->api_location = $location;
        $this->api_username = $username;
        $this->api_password = $password;
        
        parent::__construct(null, $options);
    }

    /**
     * Convert params to soap params
     * @param type $ar
     * @return \SoapParam
     */
    protected function soapize($ar)
    {
        $vars = [];
        foreach($ar as $key => $val) {
            if(is_array($val)) {
                $vars[] = new \SoapParam($this->soapize($val), 'ns1:' . $key);
            }
            elseif (gettype($val) == 'object' && get_class($val) == 'SoapVar') {
                $obj = new \stdClass();
                $obj->$key = $val;
                $vars[] = new \SoapVar($val, SOAP_ENC_OBJECT);
            }
            else {
                $vars[] = new \SoapParam($val, "ns1:$key");
            }
        }
        return $vars;
    }

    /**
     * 
     * @param type $method
     */
    protected function setHeaders($method) 
    {
        $this->__setSoapHeaders([
            new WsseAuthHeader($this->api_username, $this->api_password), 
            new AddressingHeaderTo($this->api_location), 
            new AddressingHeaderAction("{$this->api_location}#$method"),
        ]);        
    }
    
    public function __call($method, $args)
    {
        $vars = [];

        if(isset($args[0]))
        {
            $vars = $this->soapize($args[0]);
        }

        $this->setHeaders($method);
        try {
            $res = parent::__call($method, $vars);
        }
        catch(\Exception $e) {
            $message = $e->getMessage();
            switch ($e->faultcode) {
                case 's:Sender':
                    throw new AuthorizeException($message, $e->faultcode);
                default:
                    throw new SoapException($message, $e->faultcode);
            }
        }
        return $res;
    }

    public function __soapCall($method, $args, $options = [], $input_headers = [], &$output_headers = [])
    {
        $this->setHeaders($method);
        return parent::__soapCall($method, $args, $options, $input_headers, $output_headers);
    }
}

