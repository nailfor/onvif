#Onvif IP Camera library for PHP
original https://github.com/rockyjvec/Onvif

##Installation

Pull in the package through Composer.

Run:
```
sudo apt-get intstall php7.x-soap
composer require nailfor/onvif
```

##Usage

```
$cam = new nailfor\onvif\onvif("http://camera.hostname:8000", "username", "password");

//degree, degree, range[0,1]
$cam->AbsoluteMove(170.0, 18.0, 0.2); 
```
The various services are available as properties of the Onvif class:
$cam->Device,
$cam->Media,
$cam->Events, 
...
