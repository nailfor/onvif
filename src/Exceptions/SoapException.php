<?php 
namespace nailfor\onvif\Exceptions;

class SoapException extends \Exception
{
    protected $message;
    protected $code;
    
    
    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }
}

