<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* ----------------------------------- WEB API PANEL --------------------------------------------- */
Route::get('/clear', function() {
    // $exitCode = Artisan::call('route:list');
    // echo 'Routes cache cleared'; echo "<br>";
    // exit;
    
    //$exitCode = Artisan::call('route:cache');
    //echo 'Routes cache cleared'; echo "<br>";

    $exitCode = Artisan::call('route:clear');
    echo 'Routes cache cleared'; echo "<br>";
     
    $exitCode = Artisan::call('config:cache');
    echo 'Config cache cleared'; echo "<br>";
    
    $exitCode = Artisan::call('cache:clear');
    echo 'Application cache cleared';  echo "<br>";
    
    $exitCode = Artisan::call('view:clear');
    echo 'View cache cleared';  echo "<br>";

    // $Command = Artisan::call('make:middleware Cors');
    Session::flash('message', 'Cache Cleared!'); 
    Session::flash('alert-class', 'alert-danger'); 
    return redirect('/admin/dashboard');
});

//GET DATA
Route::GET('/system_settings', [ApiController::class, 'system_settings']);
Route::GET('/system_countries', [ApiController::class, 'system_countries']);
Route::POST('/system_states', [ApiController::class, 'system_states']);
//GET DATA

//GET NOTIFICATIONS
Route::POST('/notifications', [ApiController::class, 'notifications']);
Route::POST('/notifications_unread', [ApiController::class, 'notifications_unread']);
Route::POST('/notification_permission', [ApiController::class, 'notification_permission']);
//GET NOTIFICATIONS

//USER AUTHENTICATION
Route::POST('/users_customers_profile_by_id', [ApiController::class, 'users_customers_profile_by_id']);
Route::POST('/users_customers_profile_by_email', [ApiController::class, 'users_customers_profile_by_email']);

Route::POST('/signin', [ApiController::class, 'users_customers_login']);
Route::POST('/signup_social', [ApiController::class, 'users_customers_signup_social']);
Route::POST('/signup', [ApiController::class, 'users_customers_signup']);
Route::POST('/update_profile_signup', [ApiController::class, 'update_profile_signup']);

Route::POST('/forgot_password', [ApiController::class, 'forgot_password']);
Route::POST('/modify_password', [ApiController::class, 'modify_password']);

Route::POST('/change_password', [ApiController::class, 'change_password']);
Route::POST('/delete_account', [ApiController::class, 'delete_account']);
//USER AUTHENTICATION

//LIVE CHAT MESSAGES
Route::POST('/getAllChatLive', [ApiController::class, 'getAllChatLive']);
Route::POST('/user_chat_live', [ApiController::class, 'user_chat_live']);
Route::GET('/get_admin_list', [ApiController::class, 'get_admin_list']);
//LIVE CHAT MESSAGES

//GET DATA SURVEYS
Route::GET('/survey_categories', [ApiController::class, 'survey_categories']);
Route::POST('/survey_rewards', [ApiController::class, 'survey_rewards']);
Route::POST('/survey_list', [ApiController::class, 'survey_list']);
Route::POST('/survey_list_top', [ApiController::class, 'survey_list_top']);
Route::POST('/survey_list_by_survey_list_id', [ApiController::class, 'survey_list_by_survey_list_id']);
Route::POST('/survey_list_by_category_id', [ApiController::class, 'survey_list_by_category_id']);
Route::POST('/survey_list_by_name', [ApiController::class, 'survey_list_by_name']);
Route::POST('/survey_list_questions', [ApiController::class, 'survey_list_questions']);
Route::POST('/survey_list_reponses', [ApiController::class, 'survey_list_reponses']);
Route::GET('/blogs_list', [ApiController::class, 'blogs_list']);
Route::GET('/blogs_list_featured', [ApiController::class, 'blogs_list_featured']);

Route::get('/partners_list', [ApiController::class, 'partners_list']);
Route::POST('/partners_list_surveys', [ApiController::class, 'partners_list_surveys']);

Route::POST('/get_child_qs', [ApiController::class, 'get_child_qs']);
//GET DATA SURVEYS

//GET PARTNERS IMAGES
Route::get('/partners_images', [ApiController::class, 'partners_images']);
//GET PARTNERS IMAGES

//REWARD PERFERENCES
Route::post('/users_rewards_preference', [ApiController::class, 'users_rewards_preference']);
//REWARD PERFERENCES

//REWARD
Route::POST('/today_reward_count', [ApiController::class, 'today_reward_count']);
Route::POST('/unclaimed_reward_count', [ApiController::class, 'unclaimed_reward_count']);
Route::POST('/unclaimed_rewards', [ApiController::class, 'unclaimed_rewards']);
Route::POST('/claimed_reward', [ApiController::class, 'claimed_reward']);
//REWARD

//GUEST LOGIN
Route::POST('/guest_login', [ApiController::class, 'guest_login']);
//GUEST LOGIN
/* ----------------------------------- WEB API PANEL --------------------------------------------- */