<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['universal_success']                = '';
$config['universal_error']                = 'Sytax/Logical Error. Please contact CFI Tech Support.';


//General -  1001 - 3000
$lang['invalid_key']                = 'Wrong Api Key Provided';
$lang['session_timeout']                = 'Sorry, you have been logged out. Please log in again.';

$lang['no_access']                = 'You are not allowed to visit this page';
$lang['no_records']                = 'No Data Available';
$lang['missing_params']  =  'One or more Parameter(s) missing in API Request';
$lang['uploding_wrong_file_format']  =  'You are trying to upload wrong formatted file';
$lang['uploding_file_invalid_size']  =  'Invalid Height or Width of uploaded image';
$lang['error_uploding_file']  =  'There is some error when uploading image.Please try again';



//Auth - Register, Login, Forgot Password, Change Password [3001,4000]


//Freelancer - Register, Login, Forgot Password, Change Password[4001,6000]
$lang['invalid_project_title']  =  'Please add valid project title';
$lang['invalid_project_skill']  =  'Please add at least one skill used';

//ProjectOwner - Register, Login, Forgot Password, Change Password[6001,8000]

//projects [8001-9000]
$lang['invalid_project_id']          = 'Invaid project ID';
$lang['unable_add_project_db']  =  'There is some issue when inserting new project into database';
$lang['success_add_project']  =  'New Project added successfully into your portfolio';
