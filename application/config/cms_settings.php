<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['cms_title'] = 'CI App';
$config['cms_dev'] = 'youremail@yourdomain.com';

$config['RPP_project_search'] = 2;
$config['RPP_portfolio_project_list'] = 8;
$config['RPP_wish_listed'] = 2;
$config['RPP_history'] = 2;


$config['lead_cost'] = '200';
$payu  = new stdClass;
$payu->merchant_key	= 'Y6Z21F';
$payu->salt	= 'AEPGem6L';
$payu->base_url	= 'https://secure.payu.in'; // For Test environment
$payu->action	= $payu->base_url. '/_payment';
$payu->success	= '/payment-success';
$payu->failure	= '/payment-failure';

$config['payu'] = $payu;
$config['cms_featured_image'] = '400x350';
$config['projects_feature']['path'] = 'uploads/projects/featured/';
$config['projects_feature']['allowed_types'] = 'gif|jpg|png';
$config['projects_feature']['max_size'] = 2000;
$config['projects_feature']['width'] = 300;
$config['projects_feature']['height'] = 300;

$config['profile_dp']['path'] = 'uploads/profiles/dp/';
$config['profile_dp']['allowed_types'] = 'gif|jpg|png';
$config['profile_dp']['max_size'] = 1000;
$config['profile_dp']['width'] = 150;
$config['profile_dp']['height'] = 150;

$config['profile_cover']['path'] = 'uploads/profiles/cover/';
$config['profile_cover']['allowed_types'] = 'gif|jpg|png';
$config['profile_cover']['max_size'] = 5000;
$config['profile_cover']['width'] = 700;
$config['profile_cover']['height'] = 250;

$config['portfolio_project_featured']['path'] = 'uploads/profiles/projects/featured/';
$config['portfolio_project_featured']['allowed_types'] = 'gif|jpg|png';
$config['portfolio_project_featured']['max_size'] = 2000;
$config['portfolio_project_featured']['width'] = 500;
$config['portfolio_project_featured']['height'] = 500;






$config['universal_success']             = 100;
$config['universal_error']               = 404;


//General -  1001 - 3000
$config['invalid_key']                = 1001;
$config['no_access']                = 1002;
$config['session_timeout']                = 1003;
$config['missing_params']                = 1004;
$config['uploding_wrong_file_format']    = 1005;
$config['uploding_file_invalid_size']    = 1006;
$config['error_uploding_file']           = 1007;
$config['no_records']           = 1008;
$config['unable_update']           = 1009;


//Auth - Register, Login, Forgot Password, Change Password [3001,4000]
$config['invalid_username']             = 3001;
$config['duplicate_username']           = 3002;
$config['invalid_passwrd']              = 3003;
$config['do_not_match_passwrd']         = 3004;
$config['wrong_username_password']      = 3005;

//Freelancer - [4001,6000]
$config['invalid_project_title']        = 4001;
$config['invalid_project_skill']        = 4002;
$config['unable_add_project_db']        = 4003;
$config['success_add_project']          = 4004;

//ProjectOwner - [6001,8000]

//projects [8001-9000]
$config['invalid_project_id']          = 8001;
$config['unable_add_project_db']       = 8002;
