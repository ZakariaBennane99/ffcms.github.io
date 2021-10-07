<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Basic setting
$route['api/genaral_setting'] = 'api/Home/genaral_setting';
$route['api/get_category'] = 'api/Home/get_category';
$route['api/get_lavel'] = 'api/Home/getLavelByCategoryId';
$route['api/get_question_by_lavel'] = 'api/Home/getQuestionByLavel';
$route['api/save_question_report'] = 'api/Home/saveQuestionReport';
$route['api/getTodayLeaderBoard'] = 'api/Home/getTodayLeaderBoard';
$route['api/getLeaderBoard'] = 'api/Home/getLeaderBoard';
$route['api/RecentQuizByUser'] = 'api/Home/RecentQuizByUser';

$route['api/checkStatus'] = 'api/Home/checkStatus';

//withdrawal_request
$route['api/withdrawal_request'] = 'api/Rating/withdrawal_request';
$route['api/withdrawal_list'] = 'api/Rating/withdrawal_list';
$route['api/add_earnpoint'] = 'api/Rating/add_earnpoint';
//End Rating

// User 
$route['api/password_change'] = 'api/Users/password_change';
$route['api/profile'] = 'api/Users/profile';
$route['api/updateprofile'] = 'api/Users/updateprofile';
$route['api/registration'] = 'api/Users/registration';
$route['api/login'] = 'api/Users/login';
$route['api/forgot_password'] = 'api/Users/forgot_password';
$route['api/updatefirebaseid'] = 'api/Users/updatefirebaseid';



// END API rought
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;