<?php
namespace Project;

class Settings {
    /**
     * Настройки конфигурации
     *
     * @param $param string
     * @return array
     * @throws \Exception
     */
    public static function get($param) {        
        $filePath = realpath(__DIR__. '/../')."/settings/settings.php";
 
        if(!$param){
            throw new \Exception('Settings error');
        }

        if (is_file($filePath)) {
            $arrSetting = require $filePath;

            if (is_array($arrSetting)) {
                return $arrSetting[$param];
            }
        }

        throw new \Exception('Settings empty . Dir: ' . $filePath);
    }
}