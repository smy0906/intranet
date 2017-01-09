<?php

namespace Intra\Lib\Azure;

use Intra\Config\Config;

class Settings
{
    public static function getClientId()
    {
        $domain = self::getDomain();
        return Config::$azure['clientId'][$domain];
    }

    /**
     * @return string
     */
    private static function getDomain()
    {
        $domain = Config::$domain;
        return $domain;
    }

    public static function getPassword()
    {
        $domain = self::getDomain();
        return Config::$azure['password'][$domain];
    }

    public static function getRediectURI()
    {
        $domain = self::getDomain();
        return Config::$azure['redirectURI'][$domain];
    }

    public static function getResourceURI()
    {
        $domain = self::getDomain();
        return Config::$azure['resourceURI'][$domain];
    }

    public static function getAppTenantDomainName()
    {
        $domain = self::getDomain();
        return Config::$azure['appTenantDomainName'][$domain];
    }

    public static function getApiVersion()
    {
        $domain = self::getDomain();
        return Config::$azure['apiVersion'][$domain];
    }
}
