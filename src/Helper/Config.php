<?php
namespace Io\Helper;

use http\Exception\RuntimeException;
use Io;

Class Config
{

    private static $Method = 'io';
    private static $Config = null;


    /**
     * Config constructor.
     *
     * @param string $Method - name of the config method ( io / laravel / lumen .. )
     */
    public function __construct($Method=null)
    {
        self::setMethod($Method);
    }


    private function setMethod($Method=null)
    {
        if (!empty($Method) && in_array($Method, array('io', 'laravel', 'lumen')))
        {
            self::$Method = $Method;
        }
    }


    /**
     * @param string $Config - name of the config section
     * @param string $SubConfig - name of the sub config section
     * @param string $Method - name of the config method ( io, laravel .. )
     * @return mixed - null or config object
     * @throws \RuntimeException throw new \RuntimeException if json error / empty config
     */
    public static function get($Config=null, $SubConfig=null, $Method=null)
    {
        self::setMethod($Method);

        $Result = null;

        if (self::$Method == 'io' && !empty($Config) && self::initIo())
        {
            $Result = self::Returning($Config, $SubConfig);
        }
        else if (self::$Method == 'laravel' && !empty($Config) && self::initLaravel())
        {
            $Result = self::Returning($Config, $SubConfig);
        }


        return $Result;
    }


    private function Returning($Config=null, $SubConfig=null)
    {
        $Result = null;

        if (property_exists(self::$Config, $Config))
        {
            if (empty($SubConfig) && !property_exists(self::$Config->{$Config}, 'default'))
            {
                $Result = self::$Config->{$Config};
            }
            else if (empty($SubConfig) && property_exists(self::$Config->{$Config}, 'default'))
            {
                $Result = self::$Config->{$Config}->default;
            }
            else if (!empty($SubConfig) && property_exists(self::$Config->{$Config}, $SubConfig))
            {
                $Result = self::$Config->{$Config}->{$SubConfig};
            }
        }

        return $Result;
    }


    /**
     * Read config from .io.json file i doc root
     *
     * @return bool
     * @throws RuntimeException - throw new Exception if empty config or json error
     */
    private function initIo()
    {
        $DS = DIRECTORY_SEPARATOR;

        if (is_readable(__DIR__ . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '.io.json'))
        {
            $Content = file_get_contents(__DIR__ . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '..' . $DS . '.io.json');
            if (!empty($Content))
            {
                $Json = json_decode($Content);
                if (!json_last_error())
                {
                    Self::$Config = (object) $Json;

                    return true;
                }
                else
                {
                    throw new RuntimeException('json error ' . json_last_error_msg());
                }
            }
            else throw new RuntimeException('empty config ! ', 1);
        }

        return false;
    }



    private function initLaravel()
    {

        return false;
    }



}
