<?php namespace nailfor\onvif;

use nailfor\onvif\Service\Device;
use nailfor\onvif\Exceptions\NoProfileException;

use nailfor\onvif\Service\Analytics;
use nailfor\onvif\Service\Events;
use nailfor\onvif\Service\Imaging;
use nailfor\onvif\Service\Media;
use nailfor\onvif\Service\PTZ;

use nailfor\onvif\Service\Extension\DeviceIO;
use nailfor\onvif\Service\Extension\Display;
use nailfor\onvif\Service\Extension\Recording;
use nailfor\onvif\Service\Extension\Search;
use nailfor\onvif\Service\Extension\Replay;
use nailfor\onvif\Service\Extension\Receiver;
use nailfor\onvif\Service\Extension\AnalyticsDevice;

class onvif extends \stdClass
{
    // List of capabilities mapping from variables
    protected static $caps = [
        'Analytics' => Analytics::class, 
        'Events'    => Events::class, 
        'Imaging'   => Imaging::class, 
        'Media'     => Media::class, 
        'PTZ'       => PTZ::class,
    ];

    // List of extensions mapping from variables
    protected static $exts = [
        'DeviceIO'  => DeviceIO::class,
        'Display'   => Display::class,
        'Recording' => Recording::class,
        'Search'    => Search::class,
        'Replay'    => Replay::class,
        'Receiver'  => Receiver::class,
        'AnalyticsDevice' => AnalyticsDevice::class,
    ];

    protected $profiles;
    protected $profileToken;

    public function __construct($location, $username, $password)
    {
        $this->Device = new Device($location, $username, $password);

        $capabilities = $this->device->GetCapabilities(null);

        foreach(static::$caps as $name => $class) {
            if(isset($capabilities->$name)) {
                $this->$name = new $class($capabilities->$name->XAddr, $username, $password);
            }
        }

        if(isset($capabilities->Extension)) {
            foreach(static::$exts as $name => $class) {
                if(isset($capabilities->Extension->$name)) {
                    $this->$name = new $class($capabilities->Extension->$name->XAddr, $username, $password);
                }
            }
        }
    }
    
    /**
     * Return first token from media profiles
     * @return string
     * @throws NoProfileException
     */
    public function getToken() : string
    {
        if ($this->profileToken) {
            return $this->profileToken;
        }
        
        $this->profiles = $this->Media->GetProfiles();
        $res = $this->Media->__getLastResponse();
        if (!preg_match('%Profiles token="(.*)"%U', $res, $matches)) {
            throw new NoProfileException('Media profiles not found', 'MediaProfile');
        }
        $this->profileToken = $matches[1];
        
        return $this->profileToken;
    }
    
    /**
     * Convert degree[-180; 180] into [min; max] range
     * @param float $var_degree
     * @param stdclass $range
     * @return float
     */
    protected function normalize($var_degree, $range) : float
    {
        $var = $var_degree/180;
        
        $min = (float)$range->Min;
        $max = (float)$range->Max;
        if ($var < 0) {
            $var *= abs($min);
        }
        elseif ($var > 0) {
            $var *= abs($max);
        }
        
        if ($var < $min) {
            $var = $min;
        }
        if ($var > $max) {
            $var = $max;
        }
        
        return $var;
    }
    
    public function AbsoluteMove(float $pan_degree, float $tilt_degree, float $zoom_abs)
    {
        $token = $this->getToken();
        $config = $this->profiles['Profiles'][0]->PTZConfiguration;
        
        $ptLimits = $config->PanTiltLimits->Range;
        
        $pan    = $this->normalize($pan_degree, $ptLimits->XRange);
        $tilt   = $this->normalize($tilt_degree, $ptLimits->YRange);
        $zoom   = $zoom_abs;
        
        $res = $this->PTZ->AbsoluteMove([
            'ProfileToken' => $token,
            'Position' => new \SoapVar("<Position><PanTilt x='$pan' y='$tilt'/><Zoom x='$zoom'/></Position>", XSD_ANYXML),
        ]);
        
        return $res;
    }
}

