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

$route['default_controller'] = "login";

$route['return/(:any)'] = 'productreturn';
$route['return/view/(:any)'] = 'productreturn';

$route['purchase/(:any)'] = 'purchaseorder';
$route['purchase/view/(:any)'] = 'purchaseorder';


$route['poreceive/(:any)'] = 'purchasereceive';
$route['poreceive/view/(:any)'] = 'purchasereceive';

$route['purchaseret/(:any)'] = 'purchasereturn';
$route['purchaseret/view/(:any)'] = 'purchasereturn';

$route['delivery/(:any)'] = 'stockdelivery';
$route['delivery/view/(:any)'] = 'stockdelivery';

$route['delreceive/(:any)'] = 'stockdelivery';
$route['delreceive/view/(:any)'] = 'stockdelivery';

$route['custreceive/(:any)'] = 'stockdelivery';
$route['custreceive/view/(:any)'] = 'stockdelivery';

$route['adjust/(:any)'] = 'inventoryadjust';
$route['pending/(:any)'] = 'inventoryadjust';

$route['assort/(:any)'] = 'assortment';
$route['assort/view/(:any)'] = 'assortment';

$route['requestto/(:any)'] = 'stockrequest';
$route['requestto/view/(:any)'] = 'stockrequest';

$route['requestfrom/(:any)'] = 'stockrequest';
$route['requestfrom/view/(:any)'] = 'stockrequest';

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */