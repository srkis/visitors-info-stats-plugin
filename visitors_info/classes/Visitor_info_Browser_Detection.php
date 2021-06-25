<?php

class Browser_detection {

    private $_user_agent;
    private $_name;
    private $_version;
    private $_platform;

    private $_basic_browser = array (
        'Trident\/7.0' => 'Internet Explorer 11',
        'Beamrise' => 'Beamrise',
        'Opera' => 'Opera',
        'OPR' => 'Opera',
        'Shiira' => 'Shiira',
        'Chimera' => 'Chimera',
        'Phoenix' => 'Phoenix',
        'Firebird' => 'Firebird',
        'Camino' => 'Camino',
        'Netscape' => 'Netscape',
        'OmniWeb' => 'OmniWeb',
        'Konqueror' => 'Konqueror',
        'icab' => 'iCab',
        'Lynx' => 'Lynx',
        'Links' => 'Links',
        'hotjava' => 'HotJava',
        'amaya' => 'Amaya',
        'IBrowse' => 'IBrowse',
        'iTunes' => 'iTunes',
        'Silk' => 'Silk',
        'Dillo' => 'Dillo',
        'Maxthon' => 'Maxthon',
        'Arora' => 'Arora',
        'Galeon' => 'Galeon',
        'Iceape' => 'Iceape',
        'Iceweasel' => 'Iceweasel',
        'Midori' => 'Midori',
        'QupZilla' => 'QupZilla',
        'Namoroka' => 'Namoroka',
        'NetSurf' => 'NetSurf',
        'BOLT' => 'BOLT',
        'EudoraWeb' => 'EudoraWeb',
        'shadowfox' => 'ShadowFox',
        'Swiftfox' => 'Swiftfox',
        'Uzbl' => 'Uzbl',
        'UCBrowser' => 'UCBrowser',
        'Kindle' => 'Kindle',
        'wOSBrowser' => 'wOSBrowser',
        'Epiphany' => 'Epiphany',
        'SeaMonkey' => 'SeaMonkey',
        'Avant Browser' => 'Avant Browser',
        'Firefox' => 'Firefox',
        'Chrome' => 'Google Chrome',
        'MSIE' => 'Internet Explorer',
        'Internet Explorer' => 'Internet Explorer',
        'Safari' => 'Safari',
        'Mozilla' => 'Mozilla'
    );

    private $_basic_platform = array(
        'windows' => 'Windows',
        'iPad' => 'iPad',
        'iPod' => 'iPod',
        'iPhone' => 'iPhone',
        'mac' => 'Apple',
        'android' => 'Android',
        'linux' => 'Linux',
        'Nokia' => 'Nokia',
        'BlackBerry' => 'BlackBerry',
        'FreeBSD' => 'FreeBSD',
        'OpenBSD' => 'OpenBSD',
        'NetBSD' => 'NetBSD',
        'UNIX' => 'UNIX',
        'DragonFly' => 'DragonFlyBSD',
        'OpenSolaris' => 'OpenSolaris',
        'SunOS' => 'SunOS',
        'OS\/2' => 'OS/2',
        'BeOS' => 'BeOS',
        'win' => 'Windows',
        'Dillo' => 'Linux',
        'PalmOS' => 'PalmOS',
        'RebelMouse' => 'RebelMouse'
    );

    function __construct($ua = '') {
        if(empty($ua)) {
            $this->_user_agent = (!empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:getenv('HTTP_USER_AGENT'));
        }
        else {
            $this->_user_agent = $ua;
        }
    }

    function detect() {
        $this->detectBrowser();
        $this->detectPlatform();
        return $this;
    }

    function detectBrowser() {
        foreach($this->_basic_browser as $pattern => $name) {
            if( preg_match("/".$pattern."/i",$this->_user_agent, $match)) {
                $this->_name = $name;
                // finally get the correct version number
                $known = array('Version', $pattern, 'other');
                $pattern_version = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
                if (!preg_match_all($pattern_version, $this->_user_agent, $matches)) {
                    // we have no matching number just continue
                }
                // see how many we have
                $i = count($matches['browser']);
                if ($i != 1) {
                    //we will have two since we are not using 'other' argument yet
                    //see if version is before or after the name
                    if (strripos($this->_user_agent,"Version") < strripos($this->_user_agent,$pattern)){
                        @$this->_version = $matches['version'][0];
                    }
                    else {
                        @$this->_version = $matches['version'][1];
                    }
                }
                else {
                    $this->_version = $matches['version'][0];
                }
                break;
            }
        }
        return $this;
    }

    function detectPlatform() {
        foreach($this->_basic_platform as $key => $platform) {
            if (stripos($this->_user_agent, $key) !== false) {
                $this->_platform = $platform;
                break;
            }
        }
        return $this;
    }

    function getBrowser() {
        $this->_name = $this->detectBrowser()->_name;
        if(!empty($this->_name)) {
            return $this->_name;
        }
    }

    function getVersion() {
        return $this->_version;
    }

    function getPlatform() {
        $this->_platform = $this->detectPlatform()->_platform;
        if(!empty($this->_platform)) {
            return $this->_platform;
        }
    }

    function getUserAgent() {
        return $this->_user_agent;
    }

    function getInfo() {

        return array(
            'browser' => $this->getBrowser(),
            'version' => $this->getVersion(),
           // 'Browser User Agent' => $this->getUserAgent(),
            'device' => $this->getDevice(),
            'system' => $this->getPlatform()
        );
    }


    function getDevice()
    {
        $userAgent = $this->_user_agent; //$_SERVER["HTTP_USER_AGENT"];

        $devicesTypes = array(
            "Desktop" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
            "tablet"   => array("tablet", "android", "ipad", "tablet.*firefox"),
            "mobile"   => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
            "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
        );
        foreach($devicesTypes as $deviceType => $devices) {
            foreach($devices as $device) {
                if(preg_match("/" . $device . "/i", $userAgent)) {
                    $deviceName = $deviceType;
                }
            }
        }
        return isset($deviceName) ? ucfirst($deviceName) : "undefined";
    }

}
