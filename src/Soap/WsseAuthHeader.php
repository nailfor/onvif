<?php 
namespace nailfor\onvif\Soap;

class WsseAuthHeader extends \SoapHeader 
{

    private $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $wsu_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    private $wsp_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    function __construct($user, $pass) 
    {
        $created = date('Y-m-d\TH:i:s.000\Z');
        $nonce = mt_rand();
        $passdigest = base64_encode( pack('H*', sha1( pack('H*', $nonce) . pack('a*',$created).  pack('a*',$pass))));
        $nonce = base64_encode(pack('H*', $nonce));

        $auth = new \stdClass();
        $auth->Username = new \SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);

        $auth->Password = new \SoapVar("<Password Type='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest'>$passdigest</Password>", XSD_ANYXML);
        $auth->Nonce = new \SoapVar($nonce, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
        $auth->Created = new \SoapVar($created, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wsu_ns);

        $username_token = new \stdClass();
        $username_token->UsernameToken = new \SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns);

        $security_sv = new \SoapVar(
            new \SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns),
            SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns);
        parent::__construct($this->wss_ns, 'Security', $security_sv, true);
    }
}

