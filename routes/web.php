<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Helpers\Helper;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ----------------------------------- USERS PANEL --------------------------------------------- */
// Base Authentication Routes
Route::get('/', [UsersController::class, 'index']);

Route::post('/users/guestlogin_data', [UsersController::class, 'guestlogin_data']);
Route::get('/guestlogin', [UsersController::class, 'guestlogin']);

Route::post('/users/login', [UsersController::class, 'login']);
Route::get('/users/logout', [UsersController::class, 'logout']);

Route::get('/users/signup', [UsersController::class, 'signup']);
Route::post('/users/signup_submit', [UsersController::class, 'signup_submit']);

Route::get('/users/online_survey/{s_id}', [UsersController::class, 'online_survey'])->name('online_survey');
Route::get('/users/online_survey_data', [UsersController::class, 'online_survey_data'])->name('online_survey_data');
Route::get('/users/survey_list_questions', [UsersController::class, 'survey_list_questions'])->name('survey_list_questions');
Route::get('/users/get_child_qs', [UsersController::class, 'get_child_qs'])->name('get_child_qs');
Route::post('/users/survey_list_reponses', [UsersController::class, 'survey_list_reponses'])->name('survey_list_reponses');

Route::post('/users/getState', [UsersController::class, 'getState'])->name('getState');
// Base Authentication Routes

//Start GENERAl Settings
Route::get('/users/account_settings', [UsersController::class, 'account_settings']);
Route::post('/users/account_settings_update/{id}', [UsersController::class, 'account_settings_update'])->name('account_settings_update');
//Start GENERAl Settings
/* ----------------------------------- USERS PANEL --------------------------------------------- */


/* ----------------------------------- PARTNERS PANEL --------------------------------------------- */
// Base Authentication Routes
Route::get('/partners', [PartnersController::class, 'index']);

Route::post('/partners/login', [PartnersController::class, 'login']);
Route::get('/partners/logout', [PartnersController::class, 'logout']);
// Base Authentication Routes

// DASHBOARD
Route::get('/partners/dashboard', [PartnersController::class, 'Dashboard']);
// DASHBOARD

// SURVEY LIST
Route::get('/partners/survey_list', [PartnersController::class, 'survey_list']);
Route::get('/partners/survey_list_update/{id}/{status}', [PartnersController::class, 'survey_list_update'])->name('survey_list_update');
Route::get('/partners/survey_list_delete/{id}', [PartnersController::class, 'survey_list_delete'])->name('survey_list_delete');

Route::get('/partners/survey_list_add', [PartnersController::class, 'survey_list_add']);
Route::post('/partners/survey_list_add_data', [PartnersController::class, 'survey_list_add_data'])->name('survey_list_add_data');

Route::get('/partners/survey_list_edit/{id}', [PartnersController::class, 'survey_list_edit'])->name('survey_list_edit');
Route::post('/partners/survey_list_edit_data', [PartnersController::class, 'survey_list_edit_data'])->name('survey_list_edit_data');
// SURVEY LIST

// SURVEY LIST
Route::get('/partners/survey_list_graphs/{s_id}', [PartnersController::class, 'survey_list_graphs'])->name('survey_list_graphs');
// SURVEY LIST

// SURVEY LIST QS
Route::get('/partners/survey_list_qs/{s_id}', [PartnersController::class, 'survey_list_qs'])->name('survey_list_qs');
Route::get('/partners/survey_list_qs_update/{s_id}/{id}/{status}', [PartnersController::class, 'survey_list_qs_update'])->name('survey_list_qs_update');
Route::get('/partners/survey_list_qs_sort_order/{s_id}/{id}/{status}', [PartnersController::class, 'survey_list_qs_sort_order'])->name('survey_list_qs_sort_order');
Route::get('/partners/survey_list_qs_delete/{s_id}/{id}', [PartnersController::class, 'survey_list_qs_delete'])->name('survey_list_qs_delete');

Route::get('/partners/get_list_answers/{q_id}', [PartnersController::class, 'get_list_answers'])->name('get_list_answers');

Route::get('/partners/survey_list_qs_add/{s_id}', [PartnersController::class, 'survey_list_qs_add'])->name('survey_list_qs_add');
Route::post('/partners/survey_list_qs_add_data/{s_id}', [PartnersController::class, 'survey_list_qs_add_data'])->name('survey_list_qs_add_data');
Route::post('/partners/multilevel_qs_add_data/{s_id}', [AdminController::class, 'multilevel_qs_add_data'])->name('multilevel_qs_add_data');
Route::post('/partners/multilevel_parent_qs_add', [AdminController::class, 'multilevel_parent_qs_add'])->name('multilevel_parent_qs_add');
Route::get('/partners/survey_list_qs_edit/{s_id}/{id}', [PartnersController::class, 'survey_list_qs_edit'])->name('survey_list_qs_edit');
Route::post('/partners/survey_list_qs_edit_data/{s_id}', [PartnersController::class, 'survey_list_qs_edit_data'])->name('survey_list_qs_edit_data');
Route::post('/partners/survey_list_qs_delete_answer/{id}', [PartnersController::class, 'survey_list_qs_delete_answer'])->name('survey_list_qs_delete_answer');
// SURVEY LIST QS

// SURVEY LIST QS RESPONSES
Route::get('/partners/survey_list_reponses/{s_id}', [PartnersController::class, 'survey_list_reponses'])->name('survey_list_reponses');
// SURVEY LIST QS RESPONSES

//Start GENERAl Settings
Route::get('/partners/account_settings', [PartnersController::class, 'account_settings']);
Route::post('/partners/account_settings_update/{id}', [PartnersController::class, 'account_settings_update'])->name('account_settings_update');
//Start GENERAl Settings
/* ----------------------------------- PARTNERS PANEL --------------------------------------------- */

/* ----------------------------------- ADMIN PANEL --------------------------------------------- */
// Base Authentication Routes
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/clear_cache', [AdminController::class, 'clear_cache']);

Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/logout', [AdminController::class, 'logout']);
// Base Authentication Routes

// DASHBOARD
Route::get('/admin/dashboard', [AdminController::class, 'Dashboard']);
// DASHBOARD

// USERS CUSTOMERS
Route::get('/admin/users_customers', [AdminController::class, 'users_customers']);
Route::get('/admin/users_customers_view/{id}', [AdminController::class, 'users_customers_view'])->name('users_customers_view');
Route::get('/admin/users_customers_update/{id}/{status}', [AdminController::class, 'users_customers_update'])->name('users_customers_update');
Route::get('/admin/users_customers_delete/{id}', [AdminController::class, 'users_customers_delete'])->name('users_customers_delete');
Route::get('/admin/users_customers_rewards/{id}', [AdminController::class, 'users_customers_rewards'])->name('users_customers_rewards');
// USERS CUSTOMERS

// USERS PARTNERS
Route::get('/admin/users_partners', [AdminController::class, 'users_partners']);
Route::get('/admin/users_partners_update/{id}/{status}', [AdminController::class, 'users_partners_update'])->name('users_partners_update');
Route::get('/admin/users_partners_delete/{id}', [AdminController::class, 'users_partners_delete'])->name('users_partners_delete');

Route::get('/admin/users_partners_add', [AdminController::class, 'users_partners_add']);
Route::post('/admin/users_partners_add_data', [AdminController::class, 'users_partners_add_data'])->name('users_partners_add_data');

Route::get('/admin/users_partners_edit/{id}', [AdminController::class, 'users_partners_edit'])->name('users_partners_edit');
Route::post('/admin/users_partners_edit_data', [AdminController::class, 'users_partners_edit_data'])->name('users_partners_edit_data');
// USERS PARTNERS

//SUPPORT MANAGEMENT
Route::get('/admin/support', [AdminController::class, 'support']);
//SUPPORT MANAGEMENT

// SYSTEM COUNTRIES
Route::get('/admin/system_countries', [AdminController::class, 'system_countries']);
Route::get('/admin/system_countries_update/{id}/{status}', [AdminController::class, 'system_countries_update'])->name('system_countries_update');
Route::get('/admin/system_countries_delete/{id}', [AdminController::class, 'system_countries_delete'])->name('system_countries_delete');

Route::get('/admin/system_countries_add', [AdminController::class, 'system_countries_add']);
Route::post('/admin/system_countries_add_data', [AdminController::class, 'system_countries_add_data'])->name('system_countries_add_data');

Route::get('/admin/system_countries_edit/{id}', [AdminController::class, 'system_countries_edit'])->name('system_countries_edit');
Route::post('/admin/system_countries_edit_data', [AdminController::class, 'system_countries_edit_data'])->name('system_countries_edit_data');
// SYSTEM COUNTRIES

// SYSTEM COUNTRIES STATES
Route::get('/admin/system_states/{id}', [AdminController::class, 'system_states'])->name('system_states');
Route::get('/admin/system_states_update/{id}/{c_id}/{status}', [AdminController::class, 'system_states_update'])->name('system_states_update');
Route::get('/admin/system_states_delete/{id}/{c_id}', [AdminController::class, 'system_states_delete'])->name('system_states_delete');

Route::get('/admin/system_states_add/{c_id}', [AdminController::class, 'system_states_add'])->name('system_states_add');
Route::post('/admin/system_states_add_data/{c_id}', [AdminController::class, 'system_states_add_data'])->name('system_states_add_data');

Route::get('/admin/system_states_edit/{id}', [AdminController::class, 'system_states_edit'])->name('system_states_edit');
Route::post('/admin/system_states_edit_data/{c_id}', [AdminController::class, 'system_states_edit_data'])->name('system_states_edit_data');
// SYSTEM COUNTRIES STATES

// SURVEY LIST
Route::get('/admin/survey_list', [AdminController::class, 'survey_list']);
Route::get('/admin/survey_list_update/{id}/{status}', [AdminController::class, 'survey_list_update'])->name('survey_list_update');
Route::get('/admin/survey_list_delete/{id}', [AdminController::class, 'survey_list_delete'])->name('survey_list_delete');

Route::get('/admin/survey_list_add', [AdminController::class, 'survey_list_add']);
Route::post('/admin/survey_list_add_data', [AdminController::class, 'survey_list_add_data'])->name('survey_list_add_data');

Route::get('/admin/survey_list_edit/{id}', [AdminController::class, 'survey_list_edit'])->name('survey_list_edit');
Route::post('/admin/survey_list_edit_data', [AdminController::class, 'survey_list_edit_data'])->name('survey_list_edit_data');
// SURVEY LIST

// SURVEY LIST
Route::get('/admin/survey_list_graphs/{s_id}', [AdminController::class, 'survey_list_graphs'])->name('survey_list_graphs');
// SURVEY LIST

// SURVEY LIST QS
Route::get('/admin/survey_list_qs/{s_id}', [AdminController::class, 'survey_list_qs'])->name('survey_list_qs');
Route::get('/admin/survey_list_qs_update/{s_id}/{id}/{status}', [AdminController::class, 'survey_list_qs_update'])->name('survey_list_qs_update');
Route::get('/admin/survey_list_qs_sort_order/{s_id}/{id}/{status}', [AdminController::class, 'survey_list_qs_sort_order'])->name('survey_list_qs_sort_order');
Route::get('/admin/survey_list_qs_delete/{s_id}/{id}', [AdminController::class, 'survey_list_qs_delete'])->name('survey_list_qs_delete');

Route::get('/admin/get_list_answers/{q_id}', [AdminController::class, 'get_list_answers'])->name('get_list_answers');

Route::get('/admin/survey_list_qs_add/{s_id}', [AdminController::class, 'survey_list_qs_add'])->name('survey_list_qs_add');
Route::post('/admin/survey_list_qs_add_data/{s_id}', [AdminController::class, 'survey_list_qs_add_data'])->name('survey_list_qs_add_data');
Route::post('/admin/multilevel_qs_add_data/{s_id}', [AdminController::class, 'multilevel_qs_add_data'])->name('multilevel_qs_add_data');
Route::post('/admin/multilevel_parent_qs_add', [AdminController::class, 'multilevel_parent_qs_add'])->name('multilevel_parent_qs_add');

Route::get('/admin/survey_list_qs_edit/{s_id}/{id}', [AdminController::class, 'survey_list_qs_edit'])->name('survey_list_qs_edit');
Route::post('/admin/survey_list_qs_edit_data/{s_id}', [AdminController::class, 'survey_list_qs_edit_data'])->name('survey_list_qs_edit_data');
Route::post('/admin/survey_list_qs_delete_answer/{id}', [AdminController::class, 'survey_list_qs_delete_answer'])->name('survey_list_qs_delete_answer');
// SURVEY LIST QS

// SURVEY LIST QS RESPONSES
Route::get('/admin/survey_list_reponses/{s_id}', [AdminController::class, 'survey_list_reponses'])->name('survey_list_reponses');
Route::get('/admin/survey_list_responses', [AdminController::class, 'survey_list_responses'])->name('survey_list_responses');
Route::get('/admin/get_single_survey_data', [AdminController::class, 'get_single_survey_data'])->name('get_single_survey_data');
// SURVEY LIST QS RESPONSES

// ADD USER REWARD
Route::post('/admin/add_user_reward', [AdminController::class, 'add_user_reward'])->name('add_user_reward');
// ADD USER REWARD

// SURVEY REWARDS
Route::get('/admin/survey_rewards', [AdminController::class, 'survey_rewards']);
Route::get('/admin/survey_rewards_update/{id}/{status}', [AdminController::class, 'survey_rewards_update'])->name('survey_rewards_update');
Route::get('/admin/survey_rewards_delete/{id}', [AdminController::class, 'survey_rewards_delete'])->name('survey_rewards_delete');

Route::get('/admin/survey_rewards_add', [AdminController::class, 'survey_rewards_add']);
Route::post('/admin/survey_rewards_add_data', [AdminController::class, 'survey_rewards_add_data'])->name('survey_rewards_add_data');

Route::get('/admin/survey_rewards_edit/{id}', [AdminController::class, 'survey_rewards_edit'])->name('survey_rewards_edit');
Route::post('/admin/survey_rewards_edit_data', [AdminController::class, 'survey_rewards_edit_data'])->name('survey_rewards_edit_data');
// SURVEY REWARDS

// SURVEY CATEGORIES
Route::get('/admin/survey_categories', [AdminController::class, 'survey_categories']);
Route::get('/admin/survey_categories_update/{id}/{status}', [AdminController::class, 'survey_categories_update'])->name('survey_categories_update');
Route::get('/admin/survey_categories_delete/{id}', [AdminController::class, 'survey_categories_delete'])->name('survey_categories_delete');

Route::get('/admin/survey_categories_add', [AdminController::class, 'survey_categories_add']);
Route::post('/admin/survey_categories_add_data', [AdminController::class, 'survey_categories_add_data'])->name('survey_categories_add_data');

Route::get('/admin/survey_categories_edit/{id}', [AdminController::class, 'survey_categories_edit'])->name('survey_categories_edit');
Route::post('/admin/survey_categories_edit_data', [AdminController::class, 'survey_categories_edit_data'])->name('survey_categories_edit_data');
// SURVEY CATEGORIES

// BLOGS LIST
Route::get('/admin/blogs', [AdminController::class, 'blogs']);
Route::get('/admin/blogs_update/{id}/{status}', [AdminController::class, 'blogs_update'])->name('blogs_update');
Route::get('/admin/blogs_delete/{id}', [AdminController::class, 'blogs_delete'])->name('blogs_delete');

Route::get('/admin/blogs_add', [AdminController::class, 'blogs_add']);
Route::post('/admin/blogs_add_data', [AdminController::class, 'blogs_add_data'])->name('blogs_add_data');

Route::get('/admin/blogs_edit/{id}', [AdminController::class, 'blogs_edit'])->name('blogs_edit');
Route::post('/admin/blogs_edit_data', [AdminController::class, 'blogs_edit_data'])->name('blogs_edit_data');
// BLOGS LIST

// USERS SYSTEM
Route::get('/admin/users_system', [AdminController::class, 'users_system']);
Route::get('/admin/users_system_update/{id}/{status}', [AdminController::class, 'users_system_update'])->name('users_system_update');
Route::get('/admin/users_system_delete/{id}', [AdminController::class, 'users_system_delete'])->name('users_system_delete');

Route::get('/admin/users_system_add', [AdminController::class, 'users_system_add']);
Route::post('/admin/users_system_add_data', [AdminController::class, 'users_system_add_data'])->name('users_system_add_data');

Route::get('/admin/users_system_edit/{id}', [AdminController::class, 'users_system_edit'])->name('users_system_edit');
Route::post('/admin/users_system_edit_data', [AdminController::class, 'users_system_edit_data'])->name('users_system_edit_data');
// USERS SYSTEM

// USERS SYSTEM ROLES
Route::get('/admin/users_system_roles', [AdminController::class, 'users_system_roles']);
Route::get('/admin/users_system_roles_delete/{id}', [AdminController::class, 'users_system_roles_delete'])->name('users_system_roles_delete');

Route::get('/admin/users_system_roles_add', [AdminController::class, 'users_system_roles_add']);
Route::post('/admin/users_system_roles_add_data', [AdminController::class, 'users_system_roles_add_data'])->name('users_system_roles_add_data');

Route::get('/admin/users_system_roles_edit/{id}', [AdminController::class, 'users_system_roles_edit'])->name('users_system_roles_edit');
Route::post('/admin/users_system_roles_edit_data', [AdminController::class, 'users_system_roles_edit_data'])->name('users_system_roles_edit_data');
// USERS SYSTEM ROLES

//Start GENERAl Settings
Route::get('/admin/account_settings', [AdminController::class, 'account_settings']);
Route::post('/admin/account_settings_update/{id}', [AdminController::class, 'account_settings_update'])->name('account_settings_update');

Route::get('/admin/system_settings', [AdminController::class, 'system_settings']);
Route::post('/admin/system_settings_edit', [AdminController::class, 'system_settings_edit']);

Route::get('/admin/system_about_us', [AdminController::class, 'system_about_us']);
Route::get('/admin/system_terms', [AdminController::class, 'system_terms']);
Route::get('/admin/system_privacy', [AdminController::class, 'system_privacy']);
//End GENERAl Settings

// SURVEY LIST
Route::get('/admin/partners_images', [AdminController::class, 'partners_images']);
Route::get('/admin/partners_images_update/{id}/{status}', [AdminController::class, 'partners_images_update'])->name('partners_images_update');
Route::get('/admin/partners_images_delete/{id}', [AdminController::class, 'partners_images_delete'])->name('partners_images_delete');

Route::get('/admin/partners_images_add', [AdminController::class, 'partners_images_add']);
Route::post('/admin/partners_images_add_data', [AdminController::class, 'partners_images_add_data'])->name('partners_images_add_data');
Route::get('/admin/survey_responses_graph', [AdminController::class, 'survey_responses_graph'])->name('survey_responses_graph');
Route::get('/admin/survey_graph', [AdminController::class, 'survey_graph'])->name('survey_graph');
// SURVEY LIST
/* ----------------------------------- ADMIN PANEL --------------------------------------------- */