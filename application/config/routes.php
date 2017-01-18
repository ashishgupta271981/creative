<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'findcontent';
$route['translate_uri_dashes'] = TRUE;

$route['admin'] = 'admin/dashboard';
$route['api'] = 'admin/dashboard';



$controllers_methods = array(
    'en' => array(
        'willkommen/list' => 'welcome/list',
        'willkommen' => 'welcome'
    ),
    'fr' => array(
        'bienvenu/list' => 'welcome/list',
        'bienvenu' => 'welcome'
    )
);


//$route['^(\w{2})/(.*)$'] = '$2';
//$route['^(\w{2})$'] = $route['default_controller'];

$route['^(\w{2})/(.*)'] = function($language, $link) use ($controllers_methods)
{
    if(array_key_exists($language,$controllers_methods))
    {
        foreach ($controllers_methods[$language] as $key => $sym_link) {
            if (strrpos($link, $key, 0) !== FALSE) {
                $new_link = ltrim($link, $key);
                $new_link = $sym_link . $new_link;
                return $new_link;
            }
            else
            {
                return 'findcontent/index';
            }
        }
    }
    else
    {
        return $link;
    }
};


$route['^(\w{2})$'] = $route['default_controller'];

/************ projectowners start************/
$route['api/project-owner-dashboard'] = 'api/projectowners/dashboard';

/************ projectowners end************/


/************ freelancers start************/
$route['api/freelancer-dashboard'] = 'api/freelancers/dashboard';
$route['api/freelancer-profile-details'] = 'api/freelancers/profile_details';

$route['api/country-list'] = 'api/masters/country_list';
$route['api/state-list/(:num)'] = 'api/masters/state_list/$1';
$route['api/city-list/(:num)'] = 'api/masters/city_list/$1';
$route['api/skill-list'] = 'api/masters/skill_list';

$route['api/freelancer-upload-dp-image'] = 'api/freelancers/upload_dp';
$route['api/freelancer-upload-cover-image'] = 'api/freelancers/upload_cover';

$route['api/freelancer-update-profile'] = 'api/freelancers/profile_save';


$route['api/freelancer-upload-project-image'] = 'api/flportfolio/upload_project_image'; //flportfolio
$route['api/freelancer-add-project'] = 'api/flportfolio/create_project';
$route['api/freelancer-list-projects/(:num)'] = 'api/flportfolio/list_projects/$1';
$route['api/freelancer-project-details/(:num)'] = 'api/flportfolio/project-details/$1';

$route['api/freelancer-project-search/(.*)'] = 'api/projects/search/$1'; //projects

$route['api/freelancer-project-wish-list-add/(.*)/(.*)'] = 'api/shopcart/wish-list-addnew/$1/$2';//shopcart
$route['api/freelancer-wish-listed-projects/(.*)'] = 'api/shopcart/wish-list/$1';
$route['api/freelancer-project-add-to-cart/(.*)'] = 'api/shopcart/add-to-cart/$1';
$route['api/freelancer-project-remove-from-cart/(.*)'] = 'api/shopcart/remove-from-cart/$1';
$route['api/freelancer-shop-cart'] = 'api/shopcart/shop-cart';
$route['api/freelancer-get-coupon-details/(.*)'] = 'api/shopcart/coupon-details/$1';
$route['api/freelancer-checkout/(.*)/(.*)'] = 'api/shopcart/checkout/$1/$2';
$route['api/freelancer-transactions/(.*)/(.*)'] = 'api/orders/transactions/$1/$2';

$route['api/project-details/(:num)'] = 'api/projects/details/$1';

$route['api/widgets-blogs'] = 'api/widgets/blogs';

/************ freelancers end************/




/**Customization for apis*/
$route['api/search-projects'] = 'api/projectowners/projects';

/***Delete later**/
#$route['api/projectowners/users/(:num)'] = 'api/projectowners/users/id/$1';  // Example 4
#$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8



/* End of file routes.php */
/* Location: ./application/config/routes.php */
