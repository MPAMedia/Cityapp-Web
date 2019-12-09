<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 4/14/19
 * Time: 17:10
 */


class SimpleChart{


    private static $chart_list = array();
    private static $months = array();
    private static $weeks = array();
    private static $years = array();
    private static $days = array();

    public static function getMonths(){
        return self::$months;
    }

    public static function init($id){

        $months = getLast12Months();
        //fill months
        foreach ($months as $m) {
            $index = date("m", strtotime($m ));
            self::$months[$index] = date("F", strtotime($m ));
        }

    }


    public static function add($module,$id,$callback){
        $result = call_user_func($callback,self::$months);
        self::$chart_list[$id][$module] = $result;
    }

    public static function get($id){
        return self::$chart_list[$id];
    }

}


class CMS_Display{

    private static $hook_data_list = array();

    public static function createHook($hook){
        self::$hook_data_list[$hook] = array();
    }

    public static function setHTML($hook, $html){
        self::$hook_data_list[$hook][] = array(
            'html' => $html
        );
    }

    public static function set($hook, $path, $data=array()){
        self::$hook_data_list[$hook][] = array(
            'path' => $path,
            'data' => $data,
        );
    }

    public static function render($hook){
        if(isset(self::$hook_data_list[$hook])){

            foreach (self::$hook_data_list[$hook] as $key => $data){

                if(isset($data['path'])){
                    $context = &get_instance();
                    $context->load->view(
                        $data['path'],
                        $data['data']
                    );
                }else{
                    echo $data['html'];
                }

            }

        }else{
            throw new Exception(("There is no init for \"$hook\" try to call this \"CMS_Display::createHook('$hook')\" in the constructor"));
        }
    }

}