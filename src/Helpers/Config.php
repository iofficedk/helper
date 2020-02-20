<?php
namespace Fm\Helpers;

Class Config
{
    private $json = null;

    public function __construct()
    {
        list($root, $null) = explode("vendor/iofficedk", __FILE__ );
        if (is_readable($root . '.fm.json'))
        {
            $content = json_decode(file_get_contents($root . '.fm.json'));
            if (!empty($content) && is_object($content) && !json_last_error())
            {
                $this->json = $content;
            }
            else if (empty($content))
            {
                throw new \Exception("no content in fm");
            }
            else if (!is_object($content))
            {
                throw new \Exception("fm not a object");
            }
            else
            {
                throw new \Exception("json error " . json_last_error() . " => " . json_last_error_msg());
            }
        }
        else
        {
            throw new \Exception("no fm or not readable");
        }
    }


    public static function get($what=null)
    {
        $Return = new \stdClass();

        if (!empty($what)) {
            $_this = new self();
            if ($_this->json && property_exists($_this->json, $what) && is_object($_this->json->{$what}))
            {
                $Return = $_this->json->{$what};
            }
            else if (!property_exists($_this->json, $what))
            {
                throw new \Exception($what . " section not found");
            }
            else if (!is_object($_this->json->{$what}))
            {
                throw new \Exception($what . " is not an object");
            }
        }

        return $Return;
    }


}
?>
