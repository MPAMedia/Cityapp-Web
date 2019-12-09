<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 12/6/2017
 * Time: 17:14
 */


class FModuleLoader{


    public static function loadExternalModules(){

        $path = Path::getPath(array("modules"));
        return self::loadFromDir($path);

    }
    public static function loadCoreModules(){

        $path = Path::getPath(array("application","modules"));
        return self::loadFromDir($path);

    }



    private static function loadFromDir($path){

        $data = array();

        if(is_dir($path))
        if ($handle = opendir($path) AND $path!="") {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $data[] = $entry;
                }
            }
        }

        return $data;

    }


}
