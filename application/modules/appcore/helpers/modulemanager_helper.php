<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 3/5/2018
 * Time: 21:12
 */

class TemplateManager{

    private static $sidebar_menu = array();
    private static $sidebar_menu_settings = array();


    private static $scripts = array();
    private static $html = array();
    private static $scriptsLibs = array();
    private static $cssLibs = array();
    private static $cssStyle = array();

    public static function addHeadStyle($style){
        self::$cssStyle[] = $style;
    }

    public static function addHtml($html){
        self::$html[] = $html;
    }

    public static function addScript($html){
        self::$scripts[] = $html;
    }

    public static function addScriptLibs($lib){
        self::$scriptsLibs[$lib] = $lib;
    }

    public static function addCssLibs($lib){
        self::$cssLibs[$lib] = $lib;
    }

    public static function loadCssLibs(){

        foreach (self::$cssLibs as $lib){
            echo '<link rel="stylesheet" href="'.$lib.'">';
        }
    }

    public static function loadHeadStyle(){
        foreach (self::$cssStyle as $css){
            echo $css;
        }
    }

    public static function loadScripts(){
        $scripts = "";
        foreach (self::$scripts as $script){
            $scripts = $scripts."\n".$script;
        }
        return $scripts;
    }


    public static function loadHTML(){
        $html2 = "";
        foreach (self::$html as $html){
            $html2 = $html2."\n".$html;
        }
        return $html2;
    }


    public static function loadScriptsLibs(){

        foreach (self::$scriptsLibs as $lib){
            echo '<script async src="'.$lib.'"></script>';
        }
    }


    public static function registerMenu($module,$path,$_order){

        if(isset(self::$sidebar_menu[$_order])){
            foreach (self::$sidebar_menu[$_order] as $key => $b){
                echo 'Order ('.$_order.') for '.$module.' already exists, declared with '.$key;
                die();
            }
        }else if(!isset(self::$sidebar_menu[$_order][$module])){
            self::$sidebar_menu[$_order][$module] = $path;
            ksort(self::$sidebar_menu);
        }


    }


    public static function registerMenuSetting($module,$path,$_order){

        if(isset(self::$sidebar_menu_settings[$_order])){
            foreach (self::$sidebar_menu_settings[$_order] as $key => $b){
                echo 'Order ('.$_order.') for '.$module.' already exists, declared with '.$key;
                die();
            }
        }else if(!isset(self::$sidebar_menu_settings[$_order][$module])){
            self::$sidebar_menu_settings[$_order][$module] = $path;
            ksort(self::$sidebar_menu_settings);
        }

    }


    public static function loadMenu(){
        return self::$sidebar_menu;
    }


    public static function loadMenuSetting(){
        return self::$sidebar_menu_settings;
    }


    private static $setting_active =  NULL;
    public static function set_settingActive($pack){
        self::$setting_active = $pack;
    }

    public static function isSettingActive($pack=''){
        if(self::$setting_active!=NULL)
            return TRUE;
        else
            return FALSE;
    }


    public static function assets($module,$path){
        return base_url("application/modules/".$module."/views/assets/".$path);
    }

}

class ViewLoader{

    private static $headerPATH=NULL;
    private static $bodyPATH=NULL;
    private static $footerPATH=NULL;

    private static $viewDATA=NULL;


    public static function loadHeader($viewPATH,$data=NULL){

        self::$headerPATH = $viewPATH;
        self::$viewDATA = $data;

    }


    public static function loadBody($viewPATH){

        self::$bodyPATH = $viewPATH;

    }

    public static function loadFooter($viewPATH){

        $context = &get_instance();

        self::$footerPATH = $viewPATH;

        $context = &get_instance();

        $body = $context->load->view(self::$bodyPATH,self::$viewDATA,TRUE);
        $header = $context->load->view(self::$headerPATH,self::$viewDATA,TRUE);
        $footer = $context->load->view(self::$footerPATH,self::$viewDATA,TRUE);

        echo $header;
        echo $body;
        echo $footer;

    }


}