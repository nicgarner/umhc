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

$route["admin"] = "admin/index";
$route["logout"] = "admin/logout";
$route["committee/(:num)"] = "ctte/index/$1";
$route["committee"] = "ctte/index";
$route["news/(:num)"] = "news/index/$1";
$route["news"] = "news/index/0";
$route["socials/new"] = "social/create";
$route["socials/edit/(:any)"] = "social/edit/$1";
$route["socials/delete/(:any)"] = "social/delete/$1";
$route["socials/restore/(:any)"] = "social/restore/$1";
$route["socials/cancel/(:any)"] = "social/cancel/$1";
$route["socials/uncancel/(:any)"] = "social/uncancel/$1";
$route["socials/(:num)"] = "social/index/$1";
$route["socials/(:any)"] = "social/view/$1";
$route["socials"] = "social/index";
$route["hikes/new"] = "hike/create";
$route["hikes/edit/(:any)"] = "hike/edit/$1";
$route["hikes/delete/(:any)"] = "hike/delete/$1";
$route["hikes/restore/(:any)"] = "hike/restore/$1";
$route["hikes/cancel/(:any)"] = "hike/cancel/$1";
$route["hikes/uncancel/(:any)"] = "hike/uncancel/$1";
$route["hikes/(:num)"] = "hike/index/$1";
$route["hikes/(:any)"] = "hike/view/$1";
$route["hikes"] = "hike/index";
$route["latest-released-hike-details"] = "hike/latest";
$route["mailing-list"] = "mailinglist";
$route["mailing-list/(:any)"] = "mailinglist/index/$1";
$route["announcements"] = "announcement/index";
$route["announcements/new"] = "announcement/create";
$route["announcements/edit/(:any)"] = "announcement/edit/$1";
$route["announcements/delete/(:any)"] = "announcement/delete/$1";
$route["announcements/expire/(:any)"] = "announcement/expire/$1";
$route["users"] = "user/index";
$route["users/new"] = "user/create";
$route["users/edit/(:any)"] = "user/edit/$1";
$route["users/activate/(:any)"] = "user/activate/$1";
$route["users/deactivate/(:any)"] = "user/deactivate/$1";
$route["pages/edit/(:any)"] = "page/edit/$1";
$route["(:any)"] = "page/read/$1";
$route["homepage-qr-redirect"] = "page/homepage_qr_redirect";
$route["default_controller"] = "page";
$route["404_override"] = "";


/* End of file routes.php */
/* Location: ./application/config/routes.php */