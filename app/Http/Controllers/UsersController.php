<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Event_post;
use App\Models\Tag;
use App\Models\Event_tag;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use DB;

use Artisan;
use Session;

class UsersController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;

    // -------------- LOGIN PAGE ------------- //
    public function index(Request $request){
        if(isset($request->s_id)){
            $request->session()->put([
                's_id' => $request->s_id
            ]);
        }

        if ($request->session()->has('users_id') && $request->session()->has('s_id')) {
            return redirect('users/online_survey/'.session('s_id'));
        } else{
            return view('users.login');
        }
    }
    // -------------- LOGIN PAGE ------------- //

    // -------------- GUEST LOGIN PAGE ------------- //
    public function guestlogin(Request $request){
        if(isset($request->s_id)){
            $request->session()->put([
                's_id' => $request->s_id
            ]);
        }

        if ($request->session()->has('users_id') && $request->session()->has('s_id')) {
            return redirect('users/online_survey/'.session('s_id'));
        } else{
            return view('users.guestlogin');
        }
    }
    // -------------- GUEST LOGIN PAGE ------------- //

    // -------------- SIGNUP PAGE ------------- //
    public function signup(Request $request){
        if(isset($request->s_id)){
            $request->session()->put([
                's_id' => $request->s_id
            ]);
        }

        if ($request->session()->has('users_id') && $request->session()->has('s_id')) {
            return redirect('users/online_survey/'.session('s_id'));
        } else{
            return view('users.signup');
        }
    }
    // -------------- SIGNUP PAGE ------------- //

    // -------------- LOGIN AUTHENTICATION ------------- //
    public function signup_submit(Request $request){
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $postData = $request->all();
        $ifExists = DB::table('users_customers')->where('email', $postData['email'])->where('password', md5($postData['password']))->first();
        if (empty($ifExists)) {
            //SAVE USER DATA
            $save_data['first_name']            = $request->first_name;
            $save_data['last_name']             = $request->last_name;
            $save_data['phone']                 = $request->phone;
            $save_data['system_countries_id']   = $request->system_countries_id;
            $save_data['system_states_id']      = $request->system_states_id;
            $save_data['email']                 = $request->email;
            $save_data['password']              = md5($request->password);
            $save_data['account_type']          = 'SignupWithApp';
            $save_data['social_acc_type']       = 'None';
            $save_data['google_access_token']   = '';

            $save_data['verified_badge']        = 'No';
            $save_data['date_added']            = date('Y-m-d H:i:s');
            $save_data['status']                = 'Active';;

            $survey_list_qs_id = DB::table('users_customers')->insertGetId($save_data);
            //SAVE USER DATA
            Session::flash('success', ' Signup successfully.'); 
            return redirect('/');
        } else {
            Session::flash('error', 'Email Already exists'); 
            return redirect()->back();
        }
    }
    // -------------- LOGIN AUTHENTICATION ------------- //

    // -------------- LOGIN AUTHENTICATION ------------- //
    public function login(Request $request){
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $postData = $request->all();
        $ifExists = DB::table('users_customers')->where('email', $postData['email'])->where('password', md5($postData['password']))->first();
        if (!empty($ifExists)) {
            if (!empty($request->s_id)) {
                $request->session()->put([
                    'users_id' => $ifExists->users_customers_id,
                    'user_image' => $ifExists->profile_pic,
                    'fname' => $ifExists->first_name,
                    'lname' => $ifExists->last_name,
                    'email' => $ifExists->email,
                     's_id' => $request->s_id,
                ]);
                Session::flash('success', ' Logged in successfully.'); 
                return redirect('users/online_survey/'.session('s_id'));
            } else {
                Session::flash('error', ' Survey ID missing. Please select survey.'); 
                return redirect('/');
            }
        } else {
            Session::flash('error', 'Invalid Email/Password'); 
            return redirect()->back();
        }
    }
    // -------------- LOGIN AUTHENTICATION ------------- //
    
    // -------------- GUEST LOGIN DATA ------------- //
    public function guestlogin_data(Request $request){
        $validateData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email'
        ]);
        
        $postData = $request->all();
        $ifExists = DB::table('users_customers')->where(['first_name'=> $request->first_name,'last_name'=> $request->last_name,'email'=> $request->email])->first();
        if (!empty($ifExists)) {
            if (!empty($request->s_id)) {
                $request->session()->put([
                    'users_id' => $ifExists->users_customers_id,
                    'user_image' => $ifExists->profile_pic,
                    'fname' => $ifExists->first_name,
                    'lname' => $ifExists->last_name,
                    'email' => $ifExists->email,
                     's_id' => $request->s_id,
                ]);
                Session::flash('success', ' Logged in successfully.'); 
                return redirect('users/online_survey/'.session('s_id'));
            } else {
                Session::flash('error', ' Survey ID missing. Please select survey.'); 
                return redirect('/');
            }
        } else {
            if (!empty($request->s_id)) {
              $saveData['first_name']         = $request->first_name;
              $saveData['last_name']          = $request->last_name;
              $saveData['email']              = $request->email;
              $saveData['password']           = md5('123456');
              $saveData['notifications']      = 'Yes';          
              $saveData['account_type']       ='SignupWithApp';
              $saveData['social_acc_type']    = 'None';
              $saveData['google_access_token']= '';
              $saveData['verified_badge']     = 'No';
              $saveData['date_added']         = date('Y-m-d H:i:s');
              $saveData['status']             = 'Active';

          $users_customers_id   = DB::table('users_customers')->insertGetId($saveData);
          $users_customers      = DB::table('users_customers')->where('users_customers_id', $users_customers_id)->first();
                $request->session()->put([
                    'users_id' => $users_customers->users_customers_id,
                    'user_image' => $users_customers->profile_pic,
                    'fname' => $users_customers->first_name,
                    'lname' => $users_customers->last_name,
                    'email' => $users_customers->email,
                     's_id' => $request->s_id,
                ]);
                Session::flash('success', ' Logged in successfully.'); 
                return redirect('users/online_survey/'.session('s_id'));
            } else {
                Session::flash('error', ' Survey ID missing. Please select survey.'); 
                return redirect('/');
            }
        }
    }
    // -------------- GUEST LOGIN DATA ------------- //

    // -------------- LOGOUT ------------- //
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/');
    }
    // -------------- LOGOUT ------------- //

    // ------------- MANAGE SURVEY LIST GRAPH -------------- //
    public function online_survey(Request $request){
        if(isset($request->s_id)){
            $request->session()->put([
                's_id' => $request->s_id
            ]);
        }

        if ($request->session()->has('users_id') && $request->session()->has('s_id')) {
            $s_id =base64_decode(session('s_id'));
            return view('users.online_survey', compact('s_id'));
        } else {
            Session::flash('error', ' Please login to fill out survey.'); 
            return redirect('/');
        }
    }
    // ------------- MANAGE SURVEY LIST GRAPH -------------- //
    // ------------- MANAGE SURVEY LIST GRAPH -------------- //
    public function online_survey_data(Request $req){
        if (isset($req->s_id)) {
            $data = DB::table('survey_list')->where('survey_list_id', $req->s_id)->first();
            $data->total_questions      = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->count();
            $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', session('users_id'))
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }
            if ($data) {
                $response = ["code" => 200,"status" => "success","data" => $data,];
              } else {
                  $response = ["code" => 404,"status" => "error","message" => "No Data found.",];
              }
          } else {
              $response = ["code" => 404, "status" => "error","message" => "All Fields Required.",];
          }
    
        return response()
            ->json(['status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"] ])->header('Content-Type', 'application/json');
        } 
    // ------------- MANAGE SURVEY LIST GRAPH -------------- //

    // ------------- ACCOUNT SETTINGS -------------- //
    public function account_settings(){
        if(session()->has('users_id')){
            $page_name = 'account_settings';
            $fetch_data = DB::table('users_customers')->where('users_customers_id',session('users_id'))->get();
            return view('users.account_settings',compact('fetch_data','page_name'));
        } else {
            return redirect('/');
        }
    }
    // ------------- ACCOUNT SETTINGS -------------- //

    // ------------- UPDATE ACCOUNT SETTINGS -------------- //
    public function account_settings_update(Request $req,$id){
        $insert=array();
        $insert['first_name'] = $req->first_name;
        $insert['last_name'] = $req->last_name;
        $insert['email'] = $req->email;
        $insert['phone'] = $req->phone;
 
        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $ext = $file->extension();
                $path = public_path('uploads/users_customers/');
                $prefix = 'user-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                    $insert['profile_pic'] = 'uploads/users_customers/' . $img_name;
                }
            }
        }

        $a = DB::table('users_customers')->where('users_customers_id','=',$id)->update($insert);
        if ($a) {
            Session::flash('success', ' Profile Updated successfully'); 
            return redirect('users/account_settings');
        } else {
            Session::flash('error', ' oops! something went wrong'); 
            return redirect('users/account_settings');
        }
    }
    // ------------- UPDATE ACCOUNT SETTINGS -------------- //

    /* GET SURVEY LIST QUESTIONS CATEGORY ID */
  public function survey_list_questions(Request $req){
    if (isset($req->survey_list_id)) {
      $fetch_data = DB::table('survey_list_qs')->where('survey_list_id', $req->survey_list_id)->where('question_type', '!=', 'Multilevel Choice')->where('status', 'Active')->get();
      if ($fetch_data) {
        $final_data = [];
        foreach($fetch_data as $data){
          $data->answers  = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $data->survey_list_qs_id)->get();

          $final_data[]   = $data;
        }

        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No Questions found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All Fields are required.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SURVEY LIST QUESTIONS CATEGORY ID */

  /* GET CHILD QS LIST */
  public function get_child_qs(Request $req){
    if (isset($req->parent_qs_id) && isset($req->parent_qs_answers_id)) {
      $final_data = [];

      $fetch_data = DB::table('survey_list_qs_answers')->select('survey_list_qs_id')->where('parent_qs_id', $req->parent_qs_id)->where('parent_qs_answers_id', $req->parent_qs_answers_id)->where('status', 'Active')->distinct('parent_qs_id')->get();

      if ($fetch_data) {
        foreach($fetch_data as $data){
          //echo "<pre>"; print_r($data);
          $question     = DB::table('survey_list_qs')->where('survey_list_qs_id', $data->survey_list_qs_id)->first();
          if(!empty($question)){
            // $data->questions    = $questions;
            $question->answers      = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $data->survey_list_qs_id)->where('parent_qs_answers_id', $req->parent_qs_answers_id)->get();
            $final_data[]       = $question;
          }
        }
        if (!empty($final_data)) {
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $final_data;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "No Multilevel Questions found.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No Questions found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All Fields are required.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET CHILD QS LIST */
  
   /* GET SURVEY LIST QUESTIONS CATEGORY ID */
  public function survey_list_reponses(Request $req){
      if (isset($req->survey_list_id) && isset($req->users_customers_id) && isset($req->survey_list_qs_answers)) {
          $final_data = [];
          foreach ($req->survey_list_qs_answers as $key => $qs_answers) {       
              if (is_array($qs_answers['answer'])) {
                  $arrycount = $qs_answers['answer'];
                  for ($i = 0; $i < count($arrycount); $i++) {
                      $survey_list_qs_id = $qs_answers['survey_list_qs_id'][$i];
                      $survey_list_qs_answers_id = $qs_answers['survey_list_qs_answers_id'][$i];
                      $answer = $qs_answers['answer'][$i];

                      $all_answers = DB::table('survey_list_reponses')
                          ->where('survey_list_qs_id', $survey_list_qs_id)
                          ->where('survey_list_id', $req->survey_list_id)
                          ->where('users_customers_id', $req->users_customers_id)
                          ->where('survey_list_qs_answers_id', $survey_list_qs_answers_id)
                          ->first();

                      if (!$all_answers) {
                          $save_data['survey_list_id'] = $req->survey_list_id;
                          $save_data['users_customers_id'] = $req->users_customers_id;
                          $save_data['survey_list_qs_id'] = $survey_list_qs_id;
                          $save_data['survey_list_qs_answers_id'] = $survey_list_qs_answers_id;
                          $save_data['answer'] = $answer;
                          $save_data['created_at'] = date('Y-m-d H:i:s');
                          $save_data['updated_at'] = date('Y-m-d H:i:s');

                          $survey_list_reponses = DB::table('survey_list_reponses')->insertGetId($save_data);
                          $final_data[] = $save_data;
                      }
                  }
              } else {
                  $survey_list_qs_id = $qs_answers['survey_list_qs_id'];
                  $survey_list_qs_answers_id = $qs_answers['survey_list_qs_answers_id'];
                  $answer = $qs_answers['answer'];

                  $all_answers = DB::table('survey_list_reponses')
                      ->where('survey_list_qs_id', $survey_list_qs_id)
                      ->where('survey_list_id', $req->survey_list_id)
                      ->where('users_customers_id', $req->users_customers_id)
                      ->where('survey_list_qs_answers_id', $survey_list_qs_answers_id)
                      ->first();

                  if (!$all_answers) {
                      $save_data['survey_list_id'] = $req->survey_list_id;
                      $save_data['users_customers_id'] = $req->users_customers_id;
                      $save_data['survey_list_qs_id'] = $survey_list_qs_id;
                      $save_data['survey_list_qs_answers_id'] = $survey_list_qs_answers_id;
                      $save_data['answer'] = $answer;
                      $save_data['created_at'] = date('Y-m-d H:i:s');
                      $save_data['updated_at'] = date('Y-m-d H:i:s');

                      $survey_list_reponses = DB::table('survey_list_reponses')->insertGetId($save_data);
                      $final_data[] = $save_data;
                  }
              }
          }
          if ($survey_list_reponses) {
              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = "Answer saved successfully.";
          } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Already answered these questions.";
          }
      } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "All fields are required.";
      }

      return response()
          ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
          ->header('Content-Type', 'application/json');
  }
   /* GET SURVEY LIST QUESTIONS CATEGORY ID */
   
   /* GET STATE BY COUNTRY ID */
   public function getState(Request $request)
   {
       $data['states'] = DB::table('system_states')->where("system_countries_id",$request->system_countries_id)
                   ->get(["name","system_states_id"]);
       return response()->json($data);
   }
   /* GET STATE BY COUNTRY ID */
}