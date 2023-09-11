<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
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

class PartnersController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;

    // -------------- LOGIN PAGE ------------- //
    public function index(Request $request){
        if ($request->session()->has('id')) {
            return redirect('partners/dashboard');
        } else{
            return view('partners.login');
        }
    }
    // -------------- LOGIN PAGE ------------- //

    // -------------- LOGIN AUTHENTICATION ------------- //
    public function login(Request $request){
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $postData = $request->all();
        $ifExists = DB::table('users_partners')->where('email', $postData['email'])->where('password', $postData['password'])->first();
        if (!empty($ifExists)) {
            $request->session()->put([
                'id' => $ifExists->users_partners_id,
                'user_image' => $ifExists->user_image,
                'fname' => $ifExists->first_name,
                'lname' => '',
                'email' => $ifExists->email,
            ]);
            Session::flash('success', ' Logged in successfully.'); 
            return redirect('partners/dashboard');
        } else {
            Session::flash('error', 'Invalid Email/Password'); 
            return redirect()->back();
        }
    }
    // -------------- LOGIN AUTHENTICATION ------------- //

    // -------------- LOGOUT ------------- //
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('partners/');
    }
    // -------------- LOGOUT ------------- //

    // ------------- DASHBOARD -------------- //
    public function dashboard(){
        if(session()->has('id')){
            $total_users_customers     = number_format(DB::table('users_customers')->where('status', 'Active')->count());
            $total_users_partners     = number_format(DB::table('users_partners')->where('status', 'Active')->count());
            $total_survey_categories     = number_format(DB::table('survey_categories')->where('status', 'Active')->count());
            $total_survey_list     = number_format(DB::table('survey_list')->where('users_partners_id', session('id'))->where('status', 'Active')->count());
            $total_survey_list_questions     = number_format(DB::table('survey_list')
                                            ->leftJoin('survey_list_qs', 'survey_list.survey_list_id', '=', 'survey_list_qs.survey_list_qs_id')
                                            ->where('survey_list.users_partners_id', session('id'))
                                            ->where('survey_list_qs.status', 'Active')->count());
            $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first();
            
            return view('partners.dashboard', compact('total_users_customers', 'total_users_partners', 'total_survey_categories', 'total_survey_list', 'total_survey_list_questions'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- DASHBOARD -------------- //

    // ------------- MANAGE SURVEY LIST -------------- //
    public function survey_list(Request $request){
        if ($request->session()->has('id')) {
            $survey_list = db::table('survey_list')->where('users_partners_id', session('id'))->orderBy('survey_list_id', 'DESC')->get();
            return view('partners.survey_list', compact('survey_list'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST -------------- //

    // ------------- MANAGE SURVEY LIST UPDATE -------------- //
    public function survey_list_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_list')->where('survey_list_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('partners/survey_list');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE SURVEY LIST UPDATE -------------- //

    // ------------- MANAGE SURVEY LIST DELETE -------------- //
    public function survey_list_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('survey_list')->where('survey_list_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('survey_list')->where('survey_list_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('partners/survey_list');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('partners/survey_list');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('partners/survey_list');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('partners/survey_list');
            }
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST DELETE -------------- //

    // ------------- MANAGE SURVEY LIST ADD -------------- //
    public function survey_list_add(Request $request){
        if ($request->session()->has('id')) {
            $users_partners = DB::table('users_partners')->orderBy('users_partners_id', 'DESC')->get();
            $survey_categories = DB::table('survey_categories')->orderBy('survey_categories_id', 'DESC')->get();
            $survey_rewards = DB::table('survey_rewards')->orderBy('survey_rewards_id', 'DESC')->get();
            return view('partners.survey_list_add', compact('users_partners', 'survey_categories', 'survey_rewards'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST ADD -------------- //

    // ------------- MANAGE SURVEY LIST ADD DATA -------------- //
    public function survey_list_add_data(Request $req){
        $save_data['survey_categories_id']  = $req->survey_categories_id;
        $save_data['survey_rewards_id']     = $req->survey_rewards_id;
        $save_data['name']                  = $req->name;
        $save_data['users_partners_id']     = $req->users_partners_id;
        $save_data['created_at']            = date('Y-m-d H:i:s');
        $save_data['updated_at']            = date('Y-m-d H:i:s');
        $save_data['status']                = 'Active';
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_list' ;
            $image_n=  "uploads/survey_list/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['image'] = $image_n;
        }   
        $survey_list_id = DB::table('survey_list')->insertGetId($save_data);

        if($survey_list_id > 0){ 
            session()->flash('success', 'Survey added successfully!');
            return redirect('partners/survey_list');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY LIST ADD DATA -------------- //

    // ------------- MANAGE SURVEY LIST EDIT -------------- //
    public function survey_list_edit(Request $request){
        if ($request->session()->has('id')) {
            $users_partners = DB::table('users_partners')->orderBy('users_partners_id', 'DESC')->get();
            $survey_categories = DB::table('survey_categories')->orderBy('survey_categories_id', 'DESC')->get();
            $survey_rewards = DB::table('survey_rewards')->orderBy('survey_rewards_id', 'DESC')->get();

            $survey_list = DB::table('survey_list')->where('survey_list_id', $request->id)->get()->first();
            return view('partners.survey_list_edit', compact('survey_list', 'users_partners', 'survey_categories', 'survey_rewards'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST EDIT  -------------- //

    // ------------- MANAGE SURVEY LIST EDIT DATA -------------- //
    public function survey_list_edit_data(Request $req){
        $update_data['users_partners_id']    = $req->users_partners_id;
        $update_data['survey_categories_id'] = $req->survey_categories_id;
        $update_data['survey_rewards_id']    = $req->survey_rewards_id;
        $update_data['name']                 = $req->name;
        $update_data['users_partners_id']    = $req->users_partners_id;
        $update_data['updated_at']           = date('Y-m-d H:i:s');
        $update_data['status']               = $req->status;
                
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_list' ;
            $image_n=  "uploads/survey_list/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['image'] = $image_n;
        }   
        $updated = DB::table('survey_list')->where('survey_list_id', $req->survey_list_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'Survey updated successfully!');
            return redirect('partners/survey_list');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY LIST EDIT DATA -------------- //

    // ------------- MANAGE SURVEY LIST GRAPH -------------- //
    public function survey_list_graphs(Request $request){
        if ($request->session()->has('id')) {
            $survey_list_graphs = DB::table('survey_list')->where('survey_list_id', $request->s_id)->get()->first();
            return view('partners.survey_list_graphs', compact('survey_list_graphs'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST GRAPH -------------- //

    // ------------- MANAGE SURVEY LIST QS ADD -------------- //
    public function survey_list_qs(Request $request){
        if ($request->session()->has('id')) {
            $survey_list = DB::table('survey_list')->where('survey_list_id', $request->s_id)->get()->first();
            $fetch_data = DB::table('survey_list_qs')->where('survey_list_id', $request->s_id)->get();
            return view('partners.survey_list_qs', compact('fetch_data', 'survey_list'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST QS ADD -------------- //

    // ------------- MANAGE SURVEY LIST QS UPDATE -------------- //
    public function survey_list_qs_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('partners/survey_list_qs/'.$req->s_id);
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE SURVEY LIST QS UPDATE -------------- //

    // ------------- MANAGE SURVEY LIST QS DELETE -------------- //
    public function survey_list_qs_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->delete();
                    $del_ans = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $req->id)->delete();
                    if($del_ans){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('partners/survey_list_qs/'.$req->s_id);
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('partners/survey_list_qs/'.$req->s_id);
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('partners/survey_list_qs/'.$req->s_id);
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('partners/survey_list_qs/'.$req->s_id);
            }
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST QS DELETE -------------- //

    // ------------- MANAGE SURVEY LIST ANSWERS -------------- //
    public function get_list_answers(Request $request){
        if ($request->session()->has('id')) {
            $answers_rows_counter=$request->answers_rows_counter;
            $survey_list_qs_answers = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $request->q_id)->get();
            $survey_answers = '';
            foreach($survey_list_qs_answers as $key => $data){
                $survey_answers .='<div class="accordion__item">';
                $survey_answers .= '<div class="accordion__header collapsed rounded-lg" data-toggle="collapse" data-target="#default_collapse' . $data->survey_list_qs_answers_id . '">';
                $survey_answers .='<span class="accordion__header--text">'. $data->name .'</span>
                <span class="accordion__header--indicator"></span></div><div id="default_collapse' . $data->survey_list_qs_answers_id . '" class="collapse accordion__body" data-parent="#accordion-one"><div class="accordion__body--text">
                <input type="hidden" name="parent_qs_answers_id[]" value="' . $data->survey_list_qs_answers_id . '">
                <div class="row col-md-12 d-flex">            
                    <div class="form-group col-md-4 mt-2">
                        <h4><strong>Question</strong></h4>
                        <h4><strong><input style="border:2px solid" type="text" name="question[]" class="form-control input" placeholder="Enter Question"></strong></h4>
                    </div>
                    <div class="col-md-2 mt-4">    
                        <span class="pull-right mt-4">
                            <a href="javascript:;" class="pull-right btn btn-primary" onclick="addAnswersRowsMultiLevelChoice(' . $answers_rows_counter. ')">
                                 Add Answer 
                                 <!--<i class="fa fa-plus"></i>-->
                            </a>
                        </span>
                    </div>

                </div>
                    <div id="answers_list_row_fields_multilevel_choice_' .$answers_rows_counter. '"></div>
                    <div id="answers_rows_counter_multilevel_choice_'  .$answers_rows_counter.  '" value="0"></div>
                </div></div></div>';
                $answers_rows_counter++;
            }
        } else {
            $survey_answers = '';
        }

        echo $survey_answers;
    }
    // ------------- MANAGE SURVEY LIST ANSWERS -------------- //

    // ------------- MANAGE SURVEY LIST QS ADD -------------- //
    public function survey_list_qs_add(Request $request){
        if ($request->session()->has('id')) {
            $survey_list = DB::table('survey_list')->where('survey_list_id', $request->s_id)->first();
            return view('partners.survey_list_qs_add', compact('survey_list'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST QS ADD -------------- //

      // ------------- MANAGE SURVEY LIST QS ADD DATA -------------- //
      public function survey_list_qs_add_data(Request $req){
        $save_data['survey_list_id']   = $req->survey_list_id;
        $save_data['question_type']    = $req->question_type;
        $save_data['name']             = $req->name;
        $save_data['created_at']       = date('Y-m-d H:i:s');
        $save_data['updated_at']       = date('Y-m-d H:i:s');
        $save_data['status']           = "Active";
        if($req->mandatory){
            $save_data['mandatory']    = "Yes";
        }else{
            $save_data['mandatory']    = "No";
        }
        $survey_list_qs_id = DB::table('survey_list_qs')->insertGetId($save_data);

        /* ADD QUESTIONS */
        if($req->answers_name){
            foreach($req->answers_name as $key => $answers_name){
                $data_sub_table['survey_list_qs_id']        = $survey_list_qs_id;
                
                if(isset($req->parent_qs_id[$key])) {
                    $data_sub_table['parent_qs_id']             = $req->parent_qs_id[$key];
                }

                if(isset($req->parent_qs_answers_id[$key])) {
                    $data_sub_table['parent_qs_answers_id']     = $req->parent_qs_answers_id[$key];
                }

                if(isset($req->field_type[$key])) {
                    $data_sub_table['field_type']               = $req->field_type[$key];
                } else {
                    $data_sub_table['field_type']               = 'No';
                }
                if($req->question_type=="Multilevel Choice"){
                    $data_sub_table['qs_identifier']                   = "Tree";
                }else{
                    $data_sub_table['qs_identifier']                   = "Individual";
                }
                
                $data_sub_table['name']                     = $req->answers_name[$key];
                $data_sub_table['status']                   = "Active";
                
                $data_sub_table['created_at']               = date('Y-m-d H:i:s');
                $data_sub_table['updated_at']               = date('Y-m-d H:i:s');
                DB::table('survey_list_qs_answers')->insert($data_sub_table);
            }
        }
        /* ADD QUESTIONS */

        if($survey_list_qs_id > 0){ 
            session()->flash('success', 'Questions added successfully!');
            return redirect('partners/survey_list_qs/'.$req->survey_list_id);
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY LIST QS ADD DATA -------------- //

    // ------------- MANAGE MULTILEVEL QS ADD DATA -------------- //
    public function multilevel_qs_add_data(Request $req){
        // return $req->all();
        foreach ($req->question as $k => $qus) {
            if($req->question[$k]){
                $save_data['survey_list_id']   = $req->survey_list_id;
                $save_data['question_type']    = $req->question_type;
                $save_data['name']             = $req->question[$k];
                $save_data['created_at']       = date('Y-m-d H:i:s');
                $save_data['updated_at']       = date('Y-m-d H:i:s');
                $save_data['status']           = "Active";
                $survey_list_qs_id = DB::table('survey_list_qs')->insertGetId($save_data);

                /* ADD QUESTIONS */
                foreach($req->answers_name[$k+1] as $key => $answer_name){
                    $data_sub_table['name']                     = $req->answers_name[$k+1][$key];

                    $data_sub_table['survey_list_qs_id']        = $survey_list_qs_id;
                    
                    if(isset($req->survey_list_qs_id)) {
                        $data_sub_table['parent_qs_id']             = $req->survey_list_qs_id;
                    }

                    if (isset($req->parent_qs_answers_id[$k])) {
                        $data_sub_table['parent_qs_answers_id'] = $req->parent_qs_answers_id[$k];
                    }

                    if(isset($req->field_type[$k+1])) {
                        $data_sub_table['field_type']               = $req->field_type[$k+1];
                    } else {
                        $data_sub_table['field_type']               = 'No';
                    }
                    if($req->question_type=="Multilevel Choice"){
                        $data_sub_table['qs_identifier']                   = "Tree";
                    }else{
                        $data_sub_table['qs_identifier']                   = "Individual";
                    }
                    
                    $data_sub_table['status']                   = "Active";
                    
                    $data_sub_table['created_at']               = date('Y-m-d H:i:s');
                    $data_sub_table['updated_at']               = date('Y-m-d H:i:s');
                    DB::table('survey_list_qs_answers')->insert($data_sub_table);
                }
            }
        }
            /* ADD QUESTIONS */

            if($survey_list_qs_id > 0){ 
                session()->flash('success', 'Questions added successfully!');
                return redirect('partners/survey_list_qs/'.$req->survey_list_id);
            } else {
                session()->flash('error', 'Oops! Something went wrong. Please try again.');
                return redirect()->back();
            }
    }
    // ------------- MANAGE MULTILEVEL QS ADD DATA -------------- //

    // ------------- MANAGE SURVEY LIST QS EDIT -------------- //
    public function survey_list_qs_edit(Request $request){
        if ($request->session()->has('id')) {
            $survey_list       = DB::table('survey_list')->where('survey_list_id', $request->s_id)->get()->first();
            $survey_list_qs    = DB::table('survey_list_qs')->where('survey_list_qs_id', $request->id)->get()->first();
            return view('partners.survey_list_qs_edit', compact('survey_list', 'survey_list_qs'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST QS EDIT -------------- //

    // ------------- MANAGE SURVEY LIST QS EDIT DATA -------------- //
    public function survey_list_qs_edit_data(Request $req){
        $update_data['survey_list_id']   = $req->survey_list_id;
        $update_data['question_type']    = $req->question_type;
        $update_data['name']             = $req->name;
        $update_data['created_at']       = date('Y-m-d H:i:s');
        $update_data['updated_at']       = date('Y-m-d H:i:s');
        $update_data['status']           = $req->status;
        $updated = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->survey_list_qs_id)->update($update_data);

        /* UPDATE QUESTIONS */
        foreach($req->answers_name as $key => $answers_name){
            $data_sub_table['survey_list_qs_id']        = $req->survey_list_qs_id;
            
            if(isset($req->parent_qs_id[$key])) {
                $data_sub_table['parent_qs_id']             = $req->parent_qs_id[$key];
            }

            if(isset($req->parent_qs_answers_id[$key])) {
                $data_sub_table['parent_qs_answers_id']     = $req->parent_qs_answers_id[$key];
            }

            //print_r($req->field_type); exit();
            if(isset($req->field_type[$key])) {
                $data_sub_table['field_type']           = $req->field_type[$key];
            } else {
                $data_sub_table['field_type']           = 'No';
            }

            $data_sub_table['name']                     = $req->answers_name[$key];
            $data_sub_table['status']                   = $req->answers_status[$key];
            
            $data_sub_table['created_at']               = date('Y-m-d H:i:s');
            $data_sub_table['updated_at']               = date('Y-m-d H:i:s');

            if($req->survey_list_qs_answers_id[$key] != 0){
                //UPDATE ANSWER
                $updated = DB::table('survey_list_qs_answers')->where('survey_list_qs_answers_id', $req->survey_list_qs_answers_id[$key])->update($data_sub_table);
                //UPDATE ANSWER
            } else{ 
                //CREATE ANSWER
                $updated = DB::table('survey_list_qs_answers')->insert($data_sub_table);
                //CREATE ANSWER
            }
        }
        /* UPDATE QUESTIONS */

        if($updated > 0){ 
            session()->flash('success', 'Survey updated successfully!');
            return redirect('partners/survey_list_qs/'.$req->survey_list_id);
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY LIST QS EDIT DATA -------------- //

    // ------------- MANAGE SURVEY LIST QS DELETE API -------------- //
    public function survey_list_qs_delete_answer(Request $req){
        if (isset($req->id)) {
            $data_update['updated_at']       = date('Y-m-d H:i:s');
            $data_update['status']           = 'Deleted';
            $updated = DB::table('survey_list_qs_answers')->where('survey_list_qs_answers_id', $req->id)->update($data_update);

            if($updated > 0){ 
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Answer removed successfully.";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong. Please try again.";
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
    // ------------- MANAGE SURVEY LIST QS DELETE API -------------- //

    // ------------- MANAGE SURVEY LIST -------------- //
    public function survey_list_reponses(Request $request){
        if ($request->session()->has('id')) {
            $survey_list_reponses = db::table('survey_list_reponses')->where('survey_list_id', $request->s_id)->get();
            return view('partners.survey_list_reponses', compact('survey_list_reponses'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- MANAGE SURVEY LIST -------------- //

    // ------------- ACCOUNT SETTINGS -------------- //
    public function account_settings(){
        if(session()->has('id')){
            $page_name = 'account_settings';
            $fetch_data = DB::table('users_partners')->where('users_partners_id',session('id'))->get();
            return view('partners.account_settings',compact('fetch_data','page_name'));
        } else {
            return redirect('partners/');
        }
    }
    // ------------- ACCOUNT SETTINGS -------------- //

    // ------------- UPDATE ACCOUNT SETTINGS -------------- //
    public function account_settings_update(Request $req,$id){
        $insert=array();
        $insert['first_name'] = $req->first_name;
        $insert['email'] = $req->email;
        $insert['password'] = $req->password;
        
        $insert['city'] = $req->city;
        $insert['address'] = $req->address;
        $insert['mobile'] = $req->mobile;

        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $ext = $file->extension();
                $path = public_path('uploads/users_partners/');
                $prefix = 'user-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                    $insert['user_image'] = 'uploads/users_partners/' . $img_name;
                }
            }
        }

        $a = DB::table('users_partners')->where('users_partners_id','=',$id)->update($insert);
        if ($a) {
            Session::flash('success', ' Profile Updated successfully'); 
            return redirect('partners/account_settings');
        } else {
            Session::flash('error', ' oops! something went wrong'); 
            return redirect('partners/account_settings');
        }
    }
    // ------------- UPDATE ACCOUNT SETTINGS -------------- //
    
    // ------------- MANAGE CUSTOMERS USERS REWARDS -------------- //
    public function multilevel_parent_qs_add(Request $req){
        // return $req->all();
        if(isset($req->survey_list_id) && isset($req->question_type) && isset($req->question) && isset($req->answers) ){
           
            DB::beginTransaction();

            try {
            $save_data['survey_list_id']   = $req->survey_list_id;
            $save_data['question_type']    = $req->question_type;
            $save_data['name']             = $req->question;
            $save_data['created_at']       = date('Y-m-d H:i:s');
            $save_data['updated_at']       = date('Y-m-d H:i:s');
            $save_data['status']           = "Active";
            if($req->mandatory ==1){
                $save_data['mandatory']    = "Yes";
            }else{
                $save_data['mandatory']    = "No";
            }
            $survey_list_qs_id = DB::table('survey_list_qs')->insertGetId($save_data);
            
            /* ADD QUESTIONS */
            foreach($req->answers as $key => $answers_name){
                $data_sub_table['survey_list_qs_id']        = $survey_list_qs_id;
                
                if(isset($req->parent_qs_id[$key])) {
                    $data_sub_table['parent_qs_id']             = $req->parent_qs_id[$key];
                }

                if(isset($req->parent_qs_answers_id[$key])) {
                    $data_sub_table['parent_qs_answers_id']     = $req->parent_qs_answers_id[$key];
                }

                if(isset($req->field_type[$key])) {
                    $data_sub_table['field_type']               = $req->field_type[$key];
                } else {
                    $data_sub_table['field_type']               = 'No';
                }
                
                $data_sub_table['name']                     = $req->answers[$key];
                $data_sub_table['status']                   = "Active";
                
                $data_sub_table['created_at']               = date('Y-m-d H:i:s');
                $data_sub_table['updated_at']               = date('Y-m-d H:i:s');
                DB::table('survey_list_qs_answers')->insert($data_sub_table);
            }
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()
                ->json(["code" => 404,"status" => "error","message" => "something went wrong found."])
                ->header('Content-Type', 'application/json');
            }
            $survey_list_qs= DB::table('survey_list_qs')->where("survey_list_qs_id",$survey_list_qs_id)->first();
            if ($survey_list_qs) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $survey_list_qs;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "no data found.";
            }
        } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "All Fields are required.";
        }
          return response()
            ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');

        /* ADD QUESTIONS */
    }
    // ------------- MANAGE CUSTOMERS USERS REWARDS -------------- //
        
    // ------------- MANAGE SURVEY LIST QS SORT ORDER -------------- //
    public function survey_list_qs_sort_order(Request $req){
        $status['status'] = $req->status;  
        $ques = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->first();
        if ($req->status == 'up') {
            $update_data['sort_order'] = (int)$ques->sort_order + 1;
            // dd($update_data); // Add this line for debugging
        }elseif($status['status']=='down' && $ques->sort_order>1){
            $update_data['sort_order']=(int)$ques->sort_order-1;
        }else{
            $update_data['sort_order']=0;
        }  
                    //  dd($update_data,$ques,$req->status);
        $updated = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->update($update_data);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/survey_list_qs/'.$req->s_id);
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE SURVEY LIST QS SORT ORDER -------------- //
}