<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['cli/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = "cli/controller_$1/$2/$3/$4/$5/$6";
$route['cli/(:any)/(:any)/(:any)/(:any)/(:any)'] = "cli/controller_$1/$2/$3/$4/$5";
$route['cli/(:any)/(:any)/(:any)/(:any)'] = "cli/controller_$1/$2/$3/$4";
$route['cli/(:any)/(:any)/(:any)'] = "cli/controller_$1/$2/$3";
$route['cli/(:any)/(:any)'] = "cli/controller_$1/$2";
$route['cli/(:any)'] = "cli/controller_$1";
