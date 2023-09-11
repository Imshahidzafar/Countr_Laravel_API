<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\{UserRewardPref};
use App\Helpers\Helper;

class ApiController extends Controller{
  /* SEND NOTIFICATIONS */
  public function send_notification($data){
    DB::table('notifications')->insert($data);
  }
  /* SEND NOTIFICATIONS */

  /* TIME ELAPSED */
  public function time_elapsed_string($datetime, $full = false) {
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }
  /* TIME ELAPSED */

  /* DECODE IMAGE */
  public function decode_image($img , $path_url, $prefix, $random, $postfix){                                   
    $data = base64_decode($img);
    $file_name = $prefix.$random.$postfix.'.jpeg';
    $file = $path_url.$file_name;
    $success = file_put_contents($file, $data);
    return $file_name; 
  }
  /* DECODE IMAGE */

  /* GET SYSTEM SETTINGS */
  public function system_settings(){
    $fetch_data   =  DB::table('system_settings')->get();
    
    if (!empty($fetch_data)) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SYSTEM SETTINGS */

  /* ALL COUNTRIES */
  public function system_countries(){
    $fetch_data   =  DB::table('system_countries')->get();
    
    if (!empty($fetch_data)) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL COUNTRIES */

  /* ALL STATES */
  public function system_states(Request $req){
    if (isset($req->system_countries_id)) {
      $fetch_data   =  DB::table('system_states')->where('system_countries_id', $req->system_countries_id)->get();
      
      if (!empty($fetch_data)) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $fetch_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields required.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL STATES */

  /* USER TRIGGER NOTIFICATION PERMISSION */
  public function notification_permission(Request $req){
    if (isset($req->users_customers_id)) {
      $userId=['users_customers_id'=>$req->users_customers_id,'status'=>'Active'];
      $user=DB::table('users_customers')->where($userId)->first();
        if ($user) {
        if($user->notifications=="Yes"){
          $saveData['notifications'] ='No';
          $users_customers_id   = DB::table('users_customers')->where($userId)->update($saveData);
          $data=DB::table('users_customers')->where($userId)->first();
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $data;
        }else{
          $saveData['notifications'] ='Yes';
          $users_customers_id   = DB::table('users_customers')->where($userId)->update($saveData);
          $data=DB::table('users_customers')->where($userId)->first();
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $data;
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* USER TRIGGER NOTIFICATION PERMISSION */

  /* NOTIFICATIONS API */
  public function notifications(Request $req){
    if (isset($req->users_customers_id)) {
      $notifications  = DB::table('notifications')->where('receivers_id', $req->users_customers_id)->get();
      $data=[];
      foreach($notifications as $notification){
        $notification->notification_sender= DB::table('users_customers')->where('users_customers_id', $notification->senders_id)->select("first_name","last_name","profile_pic")->first();
        $data[]=$notification;
      }

      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* NOTIFICATIONS API */

  /* UNREAD NOTIFICATIONS API */
  public function notifications_unread(Request $req){
    if (isset($req->users_customers_id)) {
      $notifications  = DB::table('notifications')->where('receivers_id', $req->users_customers_id)->where('notifications.status', 'Unread')->get();

      $data = array("status"=>'Read');
      $updateProfile=DB::table('notifications')->where('receivers_id', $req->users_customers_id)->where('status', 'Unread')->update($data);

      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $notifications;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* UNREAD NOTIFICATIONS API */

  /* USERS CUSTOMERS DETAILS */
  public function users_customers_profile_by_id(Request $req){
    if (isset($req->users_customers_id)) {
      $userDetail = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get()->first();
      if (isset($userDetail) && $userDetail != null) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $userDetail;
      } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User do not exist.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* USERS CUSTOMERS DETAILS */

  /* EMAIL EXIST API */
  public function users_customers_profile_by_email(Request $req){
    if (isset($req->email)) {
      $email=DB::table('users_customers')->where('email', $req->email)->first();
      if ($email) {
        $response["code"]     = 200;
        $response["status"]   = "success";
        $response["data"]     = $email;
      }else{
        $response["code"]     = 404;
        $response["status"]   = "success";
        $response["message"]  = "Email does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Please enter email address.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* EMAIL EXIST API */

  /* LOGIN USERS CUSTOMERS */
  public function users_customers_login(Request $req){
    if (isset($req->email) && isset($req->password)) {
      $email = DB::table('users_customers')->where('email', $req->email)->get()->count();
      if ($email>0) {
        $data=DB::table('users_customers')->where('email', $req->email)->get();
        $password=$data[0]->password;
        $id = $data[0]->users_customers_id;
        if (md5($req->password) == $password) {
          if($data[0]->status == 'Active'){
            if($req->one_signal_id){
              $update=DB::table('users_customers')->where('email', $req->email)->update(['one_signal_id'=>$req->one_signal_id]);
            }

            $userDetail=DB::table('users_customers')->where('users_customers_id', $id)->get()->first();
            if (isset($userDetail) && $userDetail != null) {
              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $userDetail;
            } else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "User do not exist.";
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Your account is in ".$data[0]->status." status. Please contact admin.";
          }
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Password do not match.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exists.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* LOGIN USERS CUSTOMERS */

  /*** SIGNUP with Google/facebook  ***/
  public  function users_customers_signup_social(Request $req){
    if (isset($req->email) && isset($req->system_countries_id)  && isset($req->system_states_id) && isset($req->password) && isset($req->status) && isset($req->one_signal_id) && isset($req->account_type) && isset($req->social_acc_type) && isset($req->verify_code) && isset($req->google_access_token)) {
      $email=DB::table('users_customers')->where('email', $req->email)->get()->count();
      if ($email==0) {
        $data=array(
          'email'=>$req->email,
          'password'=>$req->password,
          'status'=>$req->status,
          'one_signal_id'=>$req->one_signal_id,
          'account_type'=>$req->account_type,
          'social_acc_type'=>$req->social_acc_type,
          'verify_code'=>$req->verify_code,
          'system_countries_id'=>$req->system_countries_id,
          'system_states_id'=>$req->system_states_id,
          'google_access_token'=>$req->google_access_token
        );

        $registerId   = DB::table('users_customers')->insertGetId($data);
        $register     = DB::table('users_customers')->where('users_customers_id', $registerId)->get();
        $response["code"] = 200;   
        $response["status"] = "success";
        $response["data"] = $register;
      } else {
        $register     = DB::table('users_customers')->where('email', $req->email)->get();

        $response["code"] = 200;   
        $response["status"] = "success";
        $response["data"] = $register;
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /*** SIGNUP with Google/facebook  ***/

  /* SIGNUP USERS CUSTOMERS */
  public function users_customers_signup(Request $req){
    if (isset($req->email) && isset($req->password) && isset($req->confirm_password) && isset($req->system_countries_id) && isset($req->system_states_id)) {
      $email = DB::table('users_customers')->where('email', $req->email)->get()->count();

      if($email == 0) {
        if($req->password == $req->confirm_password){
          if(isset($req->one_signal_id)){
            $saveData['one_signal_id']      = $req->one_signal_id;
          }
         
          $saveData['email']                = $req->email;
          $saveData['password']             = md5($req->password);
          $saveData['system_countries_id']  = $req->system_countries_id;
          $saveData['system_states_id']     = $req->system_states_id;

          $saveData['notifications']        = 'Yes';
          
          if(isset($req->account_type)){
            $saveData['account_type']       = $req->account_type;
          }
          $saveData['social_acc_type']      = 'None';
          $saveData['google_access_token']  = '';

          $saveData['verified_badge']       = 'No';
          $saveData['date_added']           = date('Y-m-d H:i:s');
          $saveData['status']               = 'Active';

          $users_customers_id   = DB::table('users_customers')->insertGetId($saveData);
          $users_customers      = DB::table('users_customers')->where('users_customers_id', $users_customers_id)->first();

          $response["code"]     = 200;   
          $response["status"]   = "success";
          $response["data"]     = $users_customers;
        } else {
          $response["code"]     = 401;
          $response["status"]   = "error";
          $response["message"]  = "Password and confirm password donot match.";
        }       
      } else {
        $response["code"]     = 401;
        $response["status"]   = "error";
        $response["message"]  = "Email already exists.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* SIGNUP USERS CUSTOMERS */

  /* UPDATE PROFILE */
  public function update_profile_signup(Request $req){
    if(isset($req->users_customers_id) && isset($req->first_name) && isset($req->last_name) && isset($req->phone) && isset($req->location) && isset($req->longitude) && isset($req->lattitude)) {
      $updateData['users_customers_id'] = $req->users_customers_id;

      $updateData['first_name']         = $req->first_name;
      $updateData['last_name']          = $req->last_name;
      $updateData['phone']              = $req->phone;
      $updateData['location']           = $req->location;
      $updateData['longitude']           = $req->longitude;
      $updateData['lattitude']           = $req->lattitude;
      $updateData['notifications']      = $req->notifications;

      if(isset($req->profile_pic)){
        $profile_pic = $req->profile_pic;
        $prefix = time();
        $img_name = $prefix . '.jpeg';
        $image_path = public_path('uploads/users_customers/') . $img_name;

        file_put_contents($image_path, base64_decode($profile_pic));
        $updateData['profile_pic'] = 'uploads/users_customers/'. $img_name;
      }

      DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update($updateData);
      $updatedData = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get();
 
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $updatedData;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* UPDATE PROFILE */

  /* FORGETPASSWORD API */
  public function forgot_password(Request $req){
    if (isset($req)) {
      $email=DB::table('users_customers')->where('email', $req->email)->get()->count();
      if ($email>0) {
        $data = DB::table('users_customers')->where('email', $req->email)->first();
        $id = $data->users_customers_id;
        $onlyEmail = $req->email;
        $otp = rand(1000,9999);
        $details = [
            "title"=>"Email Verification Code",
            "data"=>$data,
            "body"=> $otp
        ];
        $otpSended= Mail::to($onlyEmail)->send(new SendMail($details));
        $otpData=array(
         'verify_code'=>$otp
        );
        $UserotpUpdate=DB::table('users_customers')->where('users_customers_id', $id)->update($otpData);

        $details = array('otp' => $otp,'data'=>$data, 'message' => 'OTP sent in the email.');
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $details;
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exists.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Please enter email address.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* FORGETPASSWORD API */

  /* MODIFY PASSWORD */
  public function modify_password(Request $req){
    if (isset($req->email) && isset($req->otp) && isset($req->password) && isset($req->confirm_password)) {
      $forgetOtp = DB::table('users_customers')->select('verify_code')->where('email', $req->email)->first();
      $otpforgetdb = $forgetOtp->verify_code;
      if ($otpforgetdb == $req->otp) {
        if ($req->confirm_password == $req->password) {
          $otpData=[
           'verify_code'=> null,
           'password' => md5($req->password)
          ];
          
          $UserotpUpdate =DB::table('users_customers')->where('email', $req->email)->update($otpData);
          $users_customer = DB::table('users_customers')->where('email', $req->email)->first();
          
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $users_customer;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Password and confirm password do not match.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Otp do not match.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* MODIFY PASSWORD */

  /* CHANGE PASSWORD */
  public function change_password(Request $req){
    if (isset($req->email) && isset($req->old_password) && isset($req->password) && isset($req->confirm_password)) {
      $old_password = DB::table('users_customers')->select('password')->where('email', $req->email)->first();
      if (!empty($old_password)){
        $old_passwordDB = $old_password->password;
        if ($old_passwordDB == md5($req->old_password)) {
          if ($req->confirm_password == $req->password) {
            $otpData=array('password' => md5($req->password));          
            $UserotpUpdate =DB::table('users_customers')->where('email', $req->email)->update($otpData);
            $users_customers = DB::table('users_customers')->where('email', $req->email)->get();
            
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $users_customers;
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Password and confirm password do not match.";
          }
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Old password is not correct.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }  
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* CHANGE PASSWORD */

  /* DELETE ACCOUNT API */
  public function delete_account(Request $req){
    if (isset($req->user_email) && isset($req->delete_reason) && isset($req->comments)) {
      $users_customers = DB::table('users_customers')->where('email', $req->user_email)->get()->count();
      if ($users_customers>0) {
        $users_customers_delete = DB::table('users_customers_delete')->where('email', $req->user_email)->get()->count();
        if ($users_customers_delete == 0) { 
          $data = array(
            'email'=>$req->user_email,
            'delete_reason'=> $req->delete_reason,
            'comments'=> $req->comments,
            'date_added'=>date('Y-m-d H:i:s'),
            'status'=>'Pending'
          );
          $users_customers_id   = DB::table('users_customers_delete')->insertGetId($data);

          $response["code"] = 200;
          $response["status"] = "success";
          $response["message"] = "Delete account request recieved successfully.";
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Delete account request already sent. Please wait out team will get back to you in 24 hours.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exists.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* DELETE ACCOUNT API */

  /* GET ADMIN LIST */
    public function get_admin_list(Request $req){
      $admin_list = DB::table('users_system')->where('status', 'Active')->get();
      if ($admin_list) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $admin_list;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No active admin found.";
      }
      
      return response()
        ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
        ->header('Content-Type', 'application/json');
  }
  /* GET ADMIN LIST */

  /*** CHAT HEADS ADMIN ***/
  public function getAllChatLive(Request $req){  
      if (isset($req->users_customers_id)){
        $final_chat_array = array();
        $chat_list = DB::table('chat_list_live')->where('sender_id', $req->users_customers_id)->get();

        foreach($chat_list as $key => $chat){
          $chat_array = array();
          $chat_array['sender_id'] = $chat->sender_id;
          $chat_array['receiver_id'] = $chat->receiver_id;

          $receiver_data = DB::table('users_system')->where('users_system_id',$chat->receiver_id)->get();
          $chat_array['first_name'] = $receiver_data[0]->first_name;
          $chat_array['user_image'] = $receiver_data[0]->user_image;
            
          $chat_message = DB::table('chat_messages_live')
            ->where([['sender_id', $req->appUserId],['receiver_id', $chat->receiver_id]])
            ->orWhere([['sender_id', $chat->receiver_id], ['receiver_id', $req->appUserId]])
            ->get()->last();
          if (!empty($chat_message)) {
            $date_request = Helper::get_day_difference($chat_message->send_date);
            $chat_array['date'] = $date_request;
            $chat_array['last_message'] = $chat_message;
          } else {
            $date_request = Helper::get_day_difference($chat->date_request);
            $chat_array['date'] = $date_request;
            $chat_array['last_message'] = 'No Message sent or recieved.';
          }

          $final_chat_array[] = $chat_array;
        }

        if (!empty($final_chat_array)) {
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $final_chat_array;
        } else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "No chat found.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Enter All Fields.";
      }

      return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /*** CHAT HEADS ADMIN ***/

  /*** CHAT MESSAGES ***/
  public function user_chat_live(Request $req){
    if (isset($req->requestType)) {
      $request_type = $req->requestType;
      switch ($request_type) {
        case "startChat":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $check_request = DB::table('chat_list_live')->where([ ['sender_id', $req->users_customers_id], ['receiver_id', $req->other_users_customers_id]])->orWhere([ ['sender_id', $req->other_users_customers_id], ['receiver_id', $req->users_customers_id]])->count();
            if($check_request > 0){
              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = 'chat already started';    
            } else {
              $data_save = array(
                  'sender_id'=> $req->users_customers_id,
                  'receiver_id'=> $req->other_users_customers_id,
                  'date_request'=> date('Y-m-d'),
                  'created_at' => Carbon::now()
              );
              $requestSend = DB::table('chat_list_live')->insert($data_save);
              
              if($requestSend){
                  $response["code"] = 200;
                  $response["status"] = "success";
                  $response["message"] = 'Chat Started!';
                } else {
                  $response["code"] = 404;
                  $response["status"] = "error";
                  $response["message"] = 'Error in starting chat';
                }
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';      
          }
        break;   
        
        case "sendMessage":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id) && isset($req->content) && isset($req->messageType) && isset($req->sender_type)){
            $message_details = array(
              'sender_type'=> $req->sender_type,
              'sender_id'=> $req->users_customers_id,
              'receiver_id'=> $req->other_users_customers_id,
              'message'=>  json_encode($req->content) ,
              'message_type'=> $req->messageType,
              'send_date'=> date('Y-m-d'),
              'send_time'=> date('H:i:s'),
              'created_at'=> date('Y-m-d H:i:s'),
              'status'=> 'Unread'
            );

            $insertedId = DB::table('chat_messages_live')->insertGetId($message_details);
            if($insertedId){

              //NEW MESSAGE Notifications
              $dataInsert=array(
                'bookings_id'=>0,
                'senders_id'=>$req->users_customers_id,
                'receivers_id'=>$req->other_users_customers_id,
                'message'=> 'sent a message.',
                'date_added'=>date('Y-m-d H:i:s'),
                'date_modified'=>date('Y-m-d H:i:s'),
                'status'=>'Unread'
              );
              $this->send_notification($dataInsert);
              //NEW MESSAGE Notifications

              $messageDetails =  DB::table('chat_messages_live')->where('chat_messages_live_id', $insertedId)->first();
              $messageDetails->message = json_decode($messageDetails->message);
              if($messageDetails->message_type == 'attachment'){
                $messageDetails->message = config('base_urls.chat_attachments_base_url').$messageDetails->message;
              }

              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = 'Message sent successfully.';  
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = 'Oops! Something went wrong.';  
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';  
          }
        break;
                                       
        case "getMessages":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $chat_array =array();
            $day_array =array();
            $result = DB::table('chat_messages_live')->where([
              ['sender_id',$req->other_users_customers_id],    
              ['receiver_id', $req->users_customers_id]
            ])->update(array('status' => 'Read'));  
            
            $all_chat = DB::table('chat_messages_live')->where([
                ['sender_id',$req->users_customers_id],
                ['receiver_id',$req->other_users_customers_id],
            ])->orWhere([
                ['sender_id',$req->other_users_customers_id],
                ['receiver_id',$req->users_customers_id],
            ])->orderBy('chat_messages_live_id','ASC')->get();

            if(sizeof($all_chat) > 0){
              foreach($all_chat as $key => $chat){

                $get_data['sender_type'] = $chat->sender_type;

                $chat->message = json_decode($chat->message);
                $day = Helper::get_day_difference($chat->send_date);

                if (in_array($day, $day_array, TRUE)){
                  $get_data['date']= '';
                } else {
                  array_push($day_array, $day);
                  $get_data['date']= $day;
                } 
                
                $get_data['time'] =  date('h:i A',strtotime($chat->send_time));
                $get_data['msgType'] = $chat->message_type;

                if($chat->message_type=='attachment'){
                  $attachment = config('base_urls.chat_attachments_base_url') . $chat->message;
                  $get_data['message'] = $attachment;
                } else {
                  $get_data['message'] = $chat->message;
                }

                if($chat->sender_type == 'Admin'){
                  $receiver_data = DB::table('users_system')->where('users_system_id',$req->other_users_customers_id)->get();
                  $get_data['users_data'] = $receiver_data[0];
                } else {
                  $sender_data = DB::table('users_customers')->where('users_customers_id',$req->users_customers_id)->get();
                  $get_data['users_data'] = $sender_data[0];
                }
                array_push($chat_array, $get_data);
                
                if(!empty($chat_array)){
                  $result =  DB::table('chat_messages_live')->where([
                    ['sender_id',$req->other_users_customers_id],
                    ['receiver_id',$req->users_customers_id]
                  ])->update(array('status'=>'Read'));
                }
              }
              
              if($chat_array){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $chat_array; 
              } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = 'Error in chat array'; 
              }
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = 'no chat history'; 
            }                       
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are needed'; 
          }
        break;

        case "updateMessages":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $user_id = $req->users_customers_id;
            $other_user_id  = $req->other_users_customers_id;
            $chat_array =array();
            $all_chat =  DB::table('chat_messages_live')->where([
                  ['sender_id', $other_user_id],
                  ['receiver_id',$user_id],
                  ['status','Unread']
            ])->orderBy('chat_messages_live_id', 'ASC')->get();
            
            if(sizeof($all_chat) > 0){
              foreach($all_chat as $chat){
                $get_data['chat_messages_live_id'] = $chat->chat_messages_live_id;
                $get_data['sender_type'] = $chat->sender_type;

                $chat->message = json_decode($chat->message);                
                $get_data['time'] =  date('h:i A',strtotime($chat->send_date));
                $get_data['msgType'] = $chat->message_type;
                if($chat->message_type =='attachment'){
                  $image = config('base_urls.chat_attachments_base_url') . $chat->message;
                  $get_data['message'] = $image;
                } else { 
                  $get_data['message'] = $chat->message;
                } 

                if($chat->sender_type == 'Admin'){
                  $receiver_data = DB::table('users_system')->where('users_system_id',$other_user_id)->get();
                  $get_data['users_data'] = $receiver_data[0];
                } else {
                  $sender_data = DB::table('appUsers')->where('appUserId',$other_user_id)->get();
                  $get_data['users_data'] = $sender_data[0];
                }   

                if(!empty($chat_array)){
                  $result =  DB::table('chat_messages_live')->where([
                    ['sender_id',$other_user_id],
                    ['receiver_id',$user_id]
                    ])->update(array('status'=>'Read'));
                }
                array_push($chat_array, $get_data);
                           
                $chat_length   =  DB::table('chat_messages_live')->where([
                  ['sender_id', $user_id],
                  ['receiver_id',$other_user_id]
                  ])->orderBy('chat_messages_live_id','ASC')->count();

                if($chat_array){
                  $finalDataset = array(
                      "chat_length" => $chat_length,
                      "unread_messages" => $chat_array,
                  );

                  $response["code"] = 200;
                  $response["status"] = "success";
                  $response["data"] = $finalDataset; 
                } else {
                  $response["code"] = 404;
                  $response["status"] = "error";
                  $response["message"] = "Un Updated Chat Not Found!"; 
                }
              }
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "No New Message Found!"; 
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are required!"; 
          }
        break;    
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Request type not found"; 
    }

    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /*** CHAT MESSAGES ***/

  /* GET SURVEY CATEGORIES */
  public function survey_categories(Request $req){
    $fetch_data = DB::table('survey_categories')->where('status', 'Active')->get();
    if ($fetch_data) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "No category found.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SURVEY CATEGORIES */

  /* GET SURVEY LIST */
  public function survey_list_top(Request $req){
    if (isset($req->users_customers_id)) {
      $user_reward_pref = UserRewardPref::where(['users_customers_id' => $req->users_customers_id,'status' => "Active"])->get();
      if(count($user_reward_pref)>0){
        $survey_rewards_ids = $user_reward_pref->pluck('survey_rewards_id')->toArray();
        $fetch_data = DB::table('survey_list')->whereIn('survey_rewards_id',$survey_rewards_ids)->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
      }else{
        $fetch_data = DB::table('survey_list')->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
        // $fetch_data = DB::table('survey_list')->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->take(10)->get();
      }
      
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data        = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data      = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories        = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards           = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string      = $this->time_elapsed_string($data->created_at);

        //DB::enableQueryLog();
        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        //dd(DB::getQueryLog());
        //print_r($attempted_questions);
        //exit;

        if($data->total_questions <= $attempted_questions){
         $data->survey_attempt_status        = 'Completed';
         $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }
        $final_data[] = $data;
      }
      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All Fields Required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SURVEY LIST */

  /* GET SURVEY LIST */
  public function survey_list(Request $req){
     if (isset($req->users_customers_id)) {
      $user_reward_pref = UserRewardPref::where(['users_customers_id' => $req->users_customers_id,'status' => "Active"])->get();
      if(count($user_reward_pref)>0){
        $survey_rewards_ids = $user_reward_pref->pluck('survey_rewards_id')->toArray();
        $fetch_data = DB::table('survey_list')->whereIn('survey_rewards_id',$survey_rewards_ids)->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
      }else{
        $fetch_data = DB::table('survey_list')->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
      }
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data    = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data  = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories    = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards       = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string  = $this->time_elapsed_string($data->created_at);

        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }

        $final_data[] = $data;
      }
      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All Fields Required.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SURVEY LIST */

  /* GET LIST PARTNERS */
  public function partners_list(Request $req){
    $fetch_data = DB::table('users_partners')->where('status', 'Active')->get();
    $final_data = [];
    foreach($fetch_data as $data){
      //$data->time_elapsed_string  = $this->time_elapsed_string($data->date_modified);

      $final_data[] = $data;
    }
    if ($final_data) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $final_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "No partners found.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET LIST PARTNERS */

  /* GET SURVEY LIST PARTNERS */
  public function partners_list_surveys(Request $req){
     if (isset($req->users_customers_id) && isset($req->users_partners_id)) {
      $fetch_data = DB::table('survey_list')->where('users_partners_id', $req->users_partners_id)->where('status', 'Active')->get();
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data    = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data  = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories    = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards       = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string  = $this->time_elapsed_string($data->created_at);

        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }

        $final_data[] = $data;
      }
      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All Fields Required.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SURVEY LIST PARTNERS */

  /* GET SURVEY LIST BY CATEGORY ID */
  public function survey_list_by_category_id(Request $req){
    if (isset($req->survey_categories_id) && isset($req->users_customers_id)) {
      $user_reward_pref = UserRewardPref::where(['users_customers_id' => $req->users_customers_id,'status' => "Active"])->get();
      if(count($user_reward_pref)>0){
        $survey_rewards_ids = $user_reward_pref->pluck('survey_rewards_id')->toArray();
        $fetch_data = DB::table('survey_list')->whereIn('survey_rewards_id',$survey_rewards_ids)->where('survey_categories_id', $req->survey_categories_id)->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
      }else{
        $fetch_data = DB::table('survey_list')->where('survey_categories_id', $req->survey_categories_id)->orderBy('survey_list_id', 'DESC')->where('status', 'Active')->get();
      }
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data    = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data  = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories    = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards       = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string  = $this->time_elapsed_string($data->created_at);

        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }

        $final_data[] = $data;
      }

      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
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
  /* GET SURVEY LIST BY CATEGORY ID */

  /* GET SURVEY LIST BY SURVEY LIST ID */
  public function survey_list_by_survey_list_id(Request $req){
    if (isset($req->survey_list_id) && isset($req->users_customers_id)) {
      $fetch_data = DB::table('survey_list')->where('survey_list_id', $req->survey_list_id)->where('status', 'Active')->get();
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data    = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data  = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories    = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards       = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string  = $this->time_elapsed_string($data->created_at);

        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }

        $final_data[] = $data;
      }

      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
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
  /* GET SURVEY LIST BY SURVEY LIST ID */

  /* GET SURVEY LIST BY NAME */
  public function survey_list_by_name(Request $req){
    if (isset($req->name) && isset($req->users_customers_id)) {
      $fetch_data = DB::table('survey_list')->where('name', 'like', '%'.$req->name.'%')->where('status', 'Active')->get();
      $final_data = [];
      foreach($fetch_data as $data){
        $data->users_system_data    = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();;
        $data->users_partners_data  = DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
        $data->survey_categories    = DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first();
        $data->survey_rewards       = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
        $total_questions                = DB::table('survey_list_qs')->where('survey_list_id', $data->survey_list_id)->get();
        $count=0;
        foreach ($total_questions as $key => $question) {
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $question->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $question->question_type !="Multilevel Choice"){
            $count++;
          }
          
        }
        $data->total_questions          = $count;
        $data->time_elapsed_string  = $this->time_elapsed_string($data->created_at);

        $attempted_questions            = count(DB::table('survey_list_reponses')
                                            ->select('survey_list_qs_id', DB::raw('count(*) as total'))
                                            ->groupBy('survey_list_qs_id')
                                            ->where('survey_list_id', $data->survey_list_id)
                                            ->where('users_customers_id', $req->users_customers_id)
                                            ->get()
                                          );
        if($data->total_questions <= $attempted_questions){
          $data->survey_attempt_status        = 'Completed';
          $data->survey_attempted_questions   = $attempted_questions;
        } else {
          $data->survey_attempt_status  = 'Incomplete';
          $data->survey_attempted_questions   = $attempted_questions;
        }

        $final_data[] = $data;
      }
      
      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No survey found.";
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
  /* GET SURVEY LIST BY NAME */

  /* GET SURVEY LIST QUESTIONS CATEGORY ID */
  public function survey_list_questions(Request $req){
    if (isset($req->survey_list_id)) {
      // $fetch_data = DB::table('survey_list_qs')->where('survey_list_id', $req->survey_list_id)->where('question_type', '!=', 'Multilevel Choice')->where('status', 'Active')->get();
      $fetch_data = DB::table('survey_list_qs')->where('survey_list_id', $req->survey_list_id)->where('status', 'Active')->orderBy('sort_order','desc')->get();
      if ($fetch_data) {
        $final_data = [];
        foreach($fetch_data as $data){
          $answers_check = DB::table('survey_list_qs_answers')
          ->where(['survey_list_qs_id'=> $data->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
          ->first(); 
          if($answers_check || $data->question_type !="Multilevel Choice"){
            $data->answers  = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $data->survey_list_qs_id)->get();

            $final_data[]   = $data;
          }  
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

  /* GET SURVEY LIST QUESTIONS CATEGORY ID */
  public function survey_list_reponses(Request $req){
    if (isset($req->survey_list_id) && isset($req->users_customers_id) && isset($req->survey_list_qs_answers)) {
      foreach($req->survey_list_qs_answers as $qs_answers){
        $all_answers = DB::table('survey_list_reponses')->where('survey_list_qs_id', $req->survey_list_qs_id)->where('survey_list_qs_id', $req->survey_list_qs_id)->where('users_customers_id', $req->users_customers_id)->where('survey_list_qs_answers_id', $qs_answers['survey_list_qs_answers_id'])->count();
        if ($all_answers == 0) {
          $save_data['survey_list_id']              = $req->survey_list_id;
          $save_data['users_customers_id']          = $req->users_customers_id;
          $save_data['survey_list_qs_id']           = $qs_answers['survey_list_qs_id'];
          $save_data['survey_list_qs_answers_id']   = $qs_answers['survey_list_qs_answers_id'];
          $save_data['answer']                      = $qs_answers['answer'];
          $save_data['created_at']                  = date('Y-m-d H:i:s');
          $save_data['updated_at']                  = date('Y-m-d H:i:s');
          //echo "<pre>"; print_r($save_data);

          $survey_list_reponses = DB::table('survey_list_reponses')->insert($save_data);
        }
      }

      if($survey_list_reponses){
        $response["code"] = 200;
        $response["status"] = "success";
        $response["message"] = "Answer saved successfully.";
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Already Answered to this questions.";
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

  /* GET BLOGS LIST */
  public function blogs_list(Request $req){
    $fetch_data = DB::table('blogs')->where('is_featured', 'No')->where('status', 'Active')->get();
    $final_data = [];
    foreach($fetch_data as $data){
      $data->time_elapsed_string  = $this->time_elapsed_string($data->date_modified);

      $final_data[] = $data;
    }
    if ($final_data) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $final_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "No blogs found.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET BLOGS LIST */

  /* GET FEATURED BLOGS LIST */
  public function blogs_list_featured(Request $req){
    $fetch_data = DB::table('blogs')->where('is_featured', 'Yes')->where('status', 'Active')->get();
    $final_data = [];
    foreach($fetch_data as $data){
      $data->time_elapsed_string  = $this->time_elapsed_string($data->date_modified);

      $final_data[] = $data;
    }
    if ($final_data) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $final_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "No blogs found.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET FEATURED BLOGS LIST */

  /* GET CHILD QS LIST */
  public function get_child_qs(Request $req){
    if (isset($req->parent_qs_id) && isset($req->parent_qs_answers_id)) {
      $final_data = [];

      $fetch_data = DB::table('survey_list_qs_answers')->select('survey_list_qs_id')->where('parent_qs_id', $req->parent_qs_id)->where('parent_qs_answers_id', $req->parent_qs_answers_id)->where('status', 'Active')->distinct('parent_qs_id')->get();

      if ($fetch_data) {
        foreach($fetch_data as $data){
          //echo "<pre>"; print_r($data);
          $questions      = DB::table('survey_list_qs')->where('survey_list_qs_id', $data->survey_list_qs_id)->first();
          if(!empty($questions)){
            $data->questions    = $questions;
            $data->answers      = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $data->survey_list_qs_id)->where('parent_qs_answers_id', $req->parent_qs_answers_id)->get();
            $final_data[]       = $data;
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

  /* PARTNERS IMAGES API */
  public function partners_images(Request $req){
    $partners_images  = DB::table('partners_images')->where('status', 'Active')->orderBy('partners_images_id','DESC')->get();

    $response["code"] = 200;
    $response["status"] = "success";
    $response["data"] = $partners_images;
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* PARTNERS IMAGES API */

   /* GET SURVEY REWARS */
   public function survey_rewards(Request $req){
    if (isset($req->users_customers_id)) {
      $fetch_data = DB::table('survey_rewards')->where('status', 'Active')->get();
      $final_data=[];
      foreach ($fetch_data as $key => $data) {
        $pref = DB::table('users_rewards_pref')->where(['users_customers_id'=>$req->users_customers_id,'survey_rewards_id'=>$data->survey_rewards_id,'status'=>'Active'])->first();
        if($pref){
          $data->users_rewards_pref="yes";
        }else{
          $data->users_rewards_pref="no";
        }
          $final_data[]=$data;
        
      }
      if ($final_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "No Data found.";
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
  /* GET SURVEY REWARS */

    /* TODAY REWARD COUNT */
    public function today_reward_count(Request $req){
      if (isset($req->users_customers_id)) {
        $today = Carbon::now()->toDateString();
        $fetch_data = DB::table('users_rewards')
            ->where('users_customers_id', $req->users_customers_id)
            ->whereDate('date_added', $today)
            ->where('status', "unclaimed")
            ->get();
            
          $final_data = [];
          foreach ($fetch_data as $response) {
            $reward= DB::table('survey_list')
                  ->where('survey_list_id', $response->survey_list_id)
                  ->first();
              if (isset($final_data[$reward->survey_rewards_id])) {
                  $final_data[$reward->survey_rewards_id]++;
              } else {
                  $final_data[$reward->survey_rewards_id] = 1;
                }             
          }
          $response_data = [];
          foreach ($final_data as $reward_id => $count) {
              $reward_name = DB::table('survey_rewards')
                  ->where('survey_rewards_id', $reward_id)
                  ->first();
  
              $response_data[] = [
                  'reward_name' => $reward_name->name,
                  'reward_count' => $count*$reward_name->quantity, 
              ];
          }
  
          if (count($response_data) > 0) {
            $response = ["code" => 200,"status" => "success","data" => $response_data,];
          } else {
              $response = ["code" => 404,"status" => "error","message" => "No Data found.",];
          }
      } else {
          $response = ["code" => 404, "status" => "error","message" => "All Fields Required.",];
      }
  
    return response()
        ->json(['status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"] ])->header('Content-Type', 'application/json');
  }

    

    
    /* TODAY REWARD COUNT */

    /* UNCLAIMED REWARD COUNT */
  public function unclaimed_reward_count(Request $req){
    if (isset($req->users_customers_id)) {
      $fetch_data = DB::table('users_rewards')
          ->where('users_customers_id', $req->users_customers_id)
          ->where('status', "unclaimed")
          ->get();

          $final_data = [];
          foreach ($fetch_data as $response) {
            $reward= DB::table('survey_list')
                  ->where('survey_list_id', $response->survey_list_id)
                  ->first();
              if (isset($final_data[$reward->survey_rewards_id])) {
                  $final_data[$reward->survey_rewards_id]++;
              } else {
                  $final_data[$reward->survey_rewards_id] = 1;
                }             
          }
          $response_data = [];
          foreach ($final_data as $reward_id => $count) {
              $reward_name = DB::table('survey_rewards')
                  ->where('survey_rewards_id', $reward_id)
                  ->first();
  
              $response_data[] = [
                'rewards_id' => $reward_id,
                'reward_name' => $reward_name->name,
                'reward_image' => $reward_name->image,
                'reward_count' => $count*$reward_name->quantity, 
              ];
          }
  
          if (count($response_data) > 0) {
            $response = ["code" => 200,"status" => "success","data" => $response_data,];
        } else {
            $response = ["code" => 404,"status" => "error","message" => "No Data found.",];
        }
    } else {
        $response = ["code" => 404, "status" => "error","message" => "All Fields Required.",];
    }

    return response()
        ->json(['status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"] ])->header('Content-Type', 'application/json');
  }
    /* UNCLAIMED REWARD COUNT */

     /* USERS REWARS PREFERENCE */
  public function users_rewards_preference(Request $req){
    if (isset($req->users_customers_id) && isset($req->survey_rewards_id)) {
      $check_reward_pref = UserRewardPref::where([
        'survey_rewards_id'  => $req->survey_rewards_id,
        'users_customers_id' => $req->users_customers_id,
        'status' => "Active",
    ])->first();
      if($check_reward_pref){
        $change_status = UserRewardPref::where([
          'survey_rewards_id'  => $req->survey_rewards_id,
          'users_customers_id' => $req->users_customers_id,])->update(['status' => "Deleted"]);

          $response["code"] = 200;
          $response["status"] = "success";
          $response["message"] = "Reward Preference Updated successfully.";
      }else{
        $reward_pref = UserRewardPref::updateOrCreate(
          [
            'survey_rewards_id'  => $req->survey_rewards_id,
            'users_customers_id' => $req->users_customers_id
          ],
          [
            'survey_rewards_id'  => $req->survey_rewards_id,
            'users_customers_id' => $req->users_customers_id,
            'status' => "Active",
        ]);
        
        if($reward_pref){
          $response["code"] = 200;
          $response["status"] = "success";
          $response["message"] = "Reward Preference Added successfully.";
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Oops! Something went wrong.";
        }
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
  /* USERS REWARS PREFERENCE */

   /* UNCLAIMED REWARDS */
   public function unclaimed_rewards(Request $req){
    if (isset($req->users_customers_id) && isset($req->survey_rewards_id)) {
      $fetch_data = DB::table('users_rewards')
          ->where('users_customers_id', $req->users_customers_id)
          ->where('survey_rewards_id', $req->survey_rewards_id)
          ->where('status', "unclaimed")
          ->get();
        $final_data=[];
        foreach ($fetch_data as $key => $data) {
          $data->survey_rewards = DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first();
          $final_data[]=$data;
        }
        if (count($final_data) > 0) {
          $response = ["code" => 200,"status" => "success","data" => $final_data,];
        } else {
            $response = ["code" => 404,"status" => "error","message" => "No Data found.",];
        }
    } else {
        $response = ["code" => 404, "status" => "error","message" => "All Fields Required.",];
    }

    return response()
        ->json(['status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"] ])->header('Content-Type', 'application/json');
  }
    /* UNCLAIMED REWARDS*/

   /* CLAIMED REWARD */
   public function claimed_reward(Request $req){
    if (isset($req->users_rewards_id) ) {
      $claimed = DB::table('users_rewards')
          ->where('users_rewards_id', $req->users_rewards_id)
          ->update(['status'=> "claimed"]);

        if ($claimed) {
          $response = ["code" => 200,"status" => "success","message" =>"Reward Claimed",];
        } else {
            $response = ["code" => 404,"status" => "error","message" => "No Data found.",];
        }
    } else {
        $response = ["code" => 404, "status" => "error","message" => "All Fields Required.",];
    }

    return response()
        ->json(['status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"] ])->header('Content-Type', 'application/json');
  }
    /* CLAIMED REWARD*/
    
  /* GUEST LOGIN */
  public function guest_login(Request $req){
    if (isset($req->email) && isset($req->first_name) && isset($req->last_name) && isset($req->one_signal_id)) {
      $email = DB::table('users_customers')->where('email', $req->email)->first();

      if(!$email) {
          $saveData['one_signal_id']      = $req->one_signal_id;
          $saveData['first_name']         = $req->first_name;
          $saveData['last_name']          = $req->last_name;
          $saveData['email']              = $req->email;
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

          $response["code"]     = 200;   
          $response["status"]   = "success";
          $response["data"]     = $users_customers;      
      } else {
        $response["code"]     = 200;
        $response["status"]   = "success";
        $response["data"]  = $email;
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* GUEST LOGIN */
} 