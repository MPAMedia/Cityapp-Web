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


$route['ajax/(.+)/(.+)/(.+)']       = "$1/ajax/$2/$3";
$route['ajax/(.+)/(.+)']       = "$1/ajax/$2";

$route['api/1.0/(.+)/(.+)']        = "$1/api/$2";
$route['api/(.+)/(.+)/(.+)']        = "$1/api/$2/$3";
$route['api/(.+)/(.+)']        = "$1/api/$2";

//support all routes modules

$route['setting/(.+)']          = "setting/$1";
$route['campaign/(.+)']         = "campaign/$1";
$route['category/(.+)']         = "category/$1";
$route['event/(.+)']            = "event/$1";
$route['cms/(.+)']              = "cms/$1";
$route['messenger/(.+)']        = "messenger/$1";
$route['notification/(.+)']     = "notification/$1";
$route['offer/(.+)']            = "offer/$1";
$route['packmanager/(.+)']      = "packmanager/$1";
$route['uploader/(.+)']         = "uploader/$1";
$route['user/(.+)']             = "user/$1";
$route['pack/(.+)']             = "pack/$1";
$route['payment/(.+)']             = "payment/$1";



$route['(.+)']        = "cms/pages/$1";




/* End of file routes.php */
/* Location: ./application/config/routes.php */