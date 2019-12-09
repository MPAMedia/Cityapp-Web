<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "cms/pages";
$route['404_override'] = 'cms/error404';
$route['translate_uri_dashes'] = FALSE;



$route[__ADMIN.'']   = "cms/admin/home";
$route[__ADMIN.'/(.+)/(.+)']   = "$1/admin/$2";
$route[__ADMIN.'/(.+)/(.+)/(.+)']   = "$1/admin/$2/$3";


$route['ajax/(.+)/(.+)/(.+)']       = "$1/ajax/$2/$3";
$route['ajax/(.+)/(.+)']       = "$1/ajax/$2";

$route['api/1.0/(.+)/(.+)']        = "$1/api/$2";
$route['api/(.+)/(.+)/(.+)']        = "$1/api/$2/$3";
$route['api/(.+)/(.+)']        = "$1/api/$2";


//support all routes modules


$modulesC = array();
$path = FCPATH."/application/modules";
if ($handle = opendir($path) AND $path!="" AND is_dir($path)) {

    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry!=".DS_store") {
            $modulesC[] = $entry;
        }
    }
}

foreach ($modulesC as $module) {
    $route[$module.'/(.+)/(.+)']         = "$module/$1/$2";
    $route[$module.'/(.+)/(.+)/(.+)']         = "$module/$1/$2/$3";
    $route[$module.'/(.+)']         = "$module/$1";
    $route[$module]         = "$module/index";
}



$route['(.+)']        = "cms/pages/$1";




/* End of file routes.php */
/* Location: ./application/config/routes.php */