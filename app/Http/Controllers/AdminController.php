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

class AdminController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;

    // -------------- CACHE PAGE ------------- //
    public function clear_cache(Request $request){
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('view:clear');

        Session::flash('success', 'Cache Cleared!'); 
        return redirect('admin/dashboard');
    }
    // -------------- CACHE PAGE ------------- //
    
    // -------------- LOGIN PAGE ------------- //
    public function index(Request $request){
        if ($request->session()->has('id')) {
            return redirect('admin/dashboard');
        } else{
            return view('admin.login');
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
        $ifExists = DB::table('users_system')->where('email', $postData['email'])->where('password', $postData['password'])->first();
        if (!empty($ifExists)) {
            $request->session()->put([
                'id' => $ifExists->users_system_id,
                'users_system_roles_id'=>$ifExists->users_system_roles_id,
                'user_image' => $ifExists->user_image,
                'fname' => $ifExists->first_name,
                'lname' => '',
                'email' => $ifExists->email,
            ]);
            Session::flash('success', ' Logged in successfully.'); 
            return redirect('admin/dashboard');
        } else {
            Session::flash('error', 'Invalid Email/Password'); 
            return redirect()->back();
        }
    }
    // -------------- LOGIN AUTHENTICATION ------------- //

    // -------------- LOGOUT ------------- //
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('admin/');
    }
    // -------------- LOGOUT ------------- //

    // ------------- DASHBOARD -------------- //
    public function dashboard(){
        if(session()->has('id')){
            $total_users_customers     = number_format(DB::table('users_customers')->where('status', 'Active')->count());
            $total_users_partners     = number_format(DB::table('users_partners')->where('status', 'Active')->count());
            $total_survey_categories     = number_format(DB::table('survey_categories')->where('status', 'Active')->count());
            $total_survey_list     = number_format(DB::table('survey_list')->where('status', 'Active')->count());
            $total_survey_list_questions     = number_format(DB::table('survey_list_qs')->where('status', 'Active')->count());
            $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first();
            
            return view('admin.dashboard', compact('total_users_customers', 'total_users_partners', 'total_survey_categories', 'total_survey_list', 'total_survey_list_questions'));
        } else {
            return redirect('admin/');
        }
    }
    // ------------- DASHBOARD -------------- //

    // ------------- MANAGE CUSTOMERS -------------- //
    public function users_customers(Request $request){
        if ($request->session()->has('id')) {
            if(empty($request->get('filter'))){
                $users = DB::table('users_customers')->orderBy('users_customers_id', 'DESC')->get();
            } else {
                $users = DB::table('users_customers')->where('status', $request->get('filter'))->orderBy('users_customers_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return view('admin.users_customers', compact('users', 'filter'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE CUSTOMERS -------------- //

    // ------------- MANAGE CUSTOMERS VIEW -------------- //
    public function users_customers_view($id){
        if (session()->has('id')) {
            $users_data         = DB::table('users_customers')->where('users_customers_id', $id)->get();
            return view('admin.users_customers_view', compact('users_data'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE CUSTOMERS VIEW -------------- //

    // ------------- UPDATE CUSTOMERS -------------- //
    public function users_customers_update(Request $req,$id,$status){
        $update_array['status'] = $status;      
        if($req->status == 'Active'){
            $update_array['verified_badge'] = 'Yes';
        }  
        $updated = DB::table('users_customers')->where('users_customers_id', $id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return back()->with('success', 'Data Updated successfully');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE CUSTOMERS -------------- //

    // ------------- DELETE CUSTOMERS -------------- //
    public function users_customers_delete(Request $req,$id){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('users_customers')->where('users_customers_id', $id)->where('status', '!=','Deleted')->first();

                if($checkdata){
                    $del=DB::table('users_customers')->where('users_customers_id', '=', $id)->update(array( 'status' => 'Deleted'));
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/users_customers');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/users_customers');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/users_customers');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/users_customers');
            }
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- DELETE CUSTOMERS -------------- //

    // ------------- MANAGE PARTNERS -------------- //
    public function users_partners(Request $request){
        if ($request->session()->has('id')) {
            if(empty($request->get('filter'))){
                $users = DB::table('users_partners')->orderBy('users_partners_id', 'DESC')->get();
            } else {
                $users = DB::table('users_partners')->where('status', $request->get('filter'))->orderBy('users_partners_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return view('admin.users_partners', compact('users', 'filter'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS -------------- //

    // ------------- MANAGE PARTNERS VIEW -------------- //
    public function users_partners_view($id){
        if (session()->has('id')) {
            $users_data         = DB::table('users_partners')->where('users_partners_id', $id)->get();
            return view('admin.users_partners_view', compact('users_data'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS VIEW -------------- //

    // ------------- MANAGE PARTNERS -------------- //
    public function users_partners_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.users_partners_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS -------------- //

    // ------------- MANAGE PARTNERS USERS ADD DATA -------------- //
    public function users_partners_add_data(Request $req){
        $save_data['first_name']                = $req->first_name;
        $save_data['email']                     = $req->email;
        $save_data['password']                  = $req->password;
        $save_data['mobile']                    = $req->mobile;
        $save_data['city']                      = $req->city;
        $save_data['address']                   = $req->address;
        $save_data['status']                    = "Active";
        $save_data['created_at']                = date('Y-m-d H:i:s');
        $save_data['updated_at']                = date('Y-m-d H:i:s');
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/users_partners' ;
            $image_n=  "uploads/users_partners/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['user_image'] = $image_n;
        }   
        $users_partners_id = DB::table('users_partners')->insertGetId($save_data);

        if($users_partners_id > 0){ 
            session()->flash('success', 'User added successfully!');
            return redirect('admin/users_partners');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE PARTNERS USERS ADD DATA -------------- //

    // ------------- MANAGE SYSTEM USERS EDIT -------------- //
    public function users_partners_edit(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_partners';
            $users_partners = DB::table('users_partners')->where('users_partners_id', $request->id)->get()->first();
            return view('admin.users_partners_edit', compact('users_partners', 'page_name'));
        } else {
            return redirect('admin/users_partners');
        }
    }
    // ------------- MANAGE PARTNERS USERS EDIT -------------- //

    // ------------- MANAGE PARTNERS USERS EDIT DATA -------------- //
    public function users_partners_edit_data(Request $req){
        $update_data['first_name']                = $req->first_name;
        $update_data['email']                     = $req->email;
        $update_data['password']                  = $req->password;
        $update_data['mobile']                    = $req->mobile;
        $update_data['city']                      = $req->city;
        $update_data['address']                   = $req->address;
        $update_data['status']                    = $req->status;
        $update_data['updated_at']                = date('Y-m-d H:i:s');
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/users_partners' ;
            $image_n=  "uploads/users_partners/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['user_image'] = $image_n;
        }   
        $updated = DB::table('users_partners')->where('users_partners_id', $req->users_partners_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'User updated successfully!');
            return redirect('admin/users_partners');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE PARTNERS USERS EDIT DATA -------------- //

    // ------------- UPDATE PARTNERS -------------- //
    public function users_partners_update(Request $req,$id,$status){
        $update_array['status'] = $status;      
        if($req->status == 'Active'){
            $update_array['verified_badge'] = 'Yes';
        } else { 
            $update_array['verified_badge'] = 'No';
        }

        $update_array['updated_at'] = date('Y-m-d H:i:s');
        $updated = DB::table('users_partners')->where('users_partners_id', $id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return back()->with('success', 'Data Updated successfully');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE PARTNERS -------------- //

    // ------------- DELETE PARTNERS -------------- //
    public function users_partners_delete(Request $req,$id){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('users_partners')->where('users_partners_id', $id)->where('status', '!=','Deleted')->first();

                if($checkdata){
                    $del=DB::table('users_partners')->where('users_partners_id', '=', $id)->update(array( 'status' => 'Deleted', 'deleted_at' => date('Y-m-d H:i:s')));
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/users_partners');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/users_partners');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/users_partners');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/users_partners');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE PARTNERS -------------- //

    /*** SUPPORT ***/
    public function support(){
        if (!session()->has('id')) {
          return redirect('admin/admin');
        } else {
          return view('admin.support');
        }
    }
    /*** SUPPORT ***/    

    // ------------- SYSTEM COUNTRIES -------------- //
    public function system_countries(Request $request){
        if ($request->session()->has('id')) {
            $system_countries= db::table('system_countries')->orderBy('system_countries_id', 'DESC')->get();
            return view('admin.system_countries', compact('system_countries'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- SYSTEM COUNTRIES -------------- //

    // ------------- UPDATE SYSTEM COUNTRIES -------------- //
    public function system_countries_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('system_countries')->where('system_countries_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/system_countries');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE SYSTEM COUNTRIES -------------- //

    // ------------- DELETE SYSTEM COUNTRIES -------------- //
    public function system_countries_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('system_countries')->where('system_countries_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $update_array['status'] = 'Deleted';     
                    $del = DB::table('system_countries')->where('system_countries_id', $req->id)->update($update_array);
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/system_countries');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/system_countries');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/system_countries');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/system_countries');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE SYSTEM COUNTRIES -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES ADD -------------- //
    public function system_countries_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.system_countries_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES ADD -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES ADD DATA -------------- //
    public function system_countries_add_data(Request $req){
        $save_data['name']                = $req->name;
        $save_data['iso2']                = $req->iso2;
        $save_data['iso3']                = $req->iso3;
        $save_data['numeric_code']        = $req->numeric_code;
        $save_data['phonecode']           = $req->phonecode;
        $save_data['capital']             = $req->capital;
        $save_data['currency']            = $req->currency;
        $save_data['currency_name']       = $req->currency_name;
        $save_data['currency_symbol']     = $req->currency_symbol;
        $save_data['latitude']            = $req->latitude;
        $save_data['longitude']           = $req->longitude;
        $save_data['emoji']               = $req->emoji;
        $save_data['created_at']          = date('Y-m-d H:i:s');
        $save_data['updated_at']          = date('Y-m-d H:i:s');
        $save_data['status']              = $req->status;
        
        $system_countries_id = DB::table('system_countries')->insertGetId($save_data);

        if($system_countries_id > 0){ 
            session()->flash('success', 'Country added successfully!');
            return redirect('admin/system_countries');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES ADD DATA -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES EDIT -------------- //
    public function system_countries_edit(Request $request){
        if ($request->session()->has('id')) {
            $system_countries = DB::table('system_countries')->where('system_countries_id', $request->id)->get()->first();
            return view('admin.system_countries_edit', compact('system_countries'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES EDIT -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES EDIT DATA -------------- //
    public function system_countries_edit_data(Request $req){
        $update_data['name']                = $req->name;
        $update_data['iso2']                = $req->iso2;
        $update_data['iso3']                = $req->iso3;
        $update_data['numeric_code']        = $req->numeric_code;
        $update_data['phonecode']           = $req->phonecode;
        $update_data['capital']             = $req->capital;
        $update_data['currency']            = $req->currency;
        $update_data['currency_name']       = $req->currency_name;
        $update_data['currency_symbol']     = $req->currency_symbol;
        $update_data['latitude']            = $req->latitude;
        $update_data['longitude']           = $req->longitude;
        $update_data['emoji']               = $req->emoji;
        $update_data['updated_at']          = date('Y-m-d H:i:s');
        $update_data['status']              = $req->status;
        
        $updated = DB::table('system_countries')->where('system_countries_id', $req->system_countries_id)->update($update_data);
        
        if($updated > 0){ 
            session()->flash('success', 'Country updated successfully!');
            return redirect('admin/system_countries');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES EDIT DATA -------------- //

    // ------------- SYSTEM STATES -------------- //
    public function system_states(Request $request){
        if ($request->session()->has('id')) {
            $system_countries_id = $request->id;
            $system_states = db::table('system_states')->where('system_countries_id', $request->id)->orderBy('system_states_id', 'DESC')->get();
            return view('admin.system_states', compact('system_states', 'system_countries_id'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- SYSTEM STATES -------------- //

    // ------------- UPDATE SYSTEM STATES -------------- //
    public function system_states_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('system_states')->where('system_states_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/system_states/'.$req->c_id);
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE SYSTEM STATES -------------- //

    // ------------- DELETE SYSTEM COUNTRIES -------------- //
    public function system_states_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('system_states')->where('system_states_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $update_array['status'] = 'Deleted';     
                    $del = DB::table('system_states')->where('system_states_id', $req->id)->update($update_array);
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/system_states/'.$req->c_id);
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/system_states/'.$req->c_id);
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/system_states/'.$req->c_id);
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/system_states/'.$req->c_id);
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE SYSTEM COUNTRIES -------------- //

    // ------------- MANAGE SYSTEM STATES ADD -------------- //
    public function system_states_add(Request $request){
        if ($request->session()->has('id')) {
            $system_countries_id = $request->c_id;
            return view('admin.system_states_add', compact('system_countries_id'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM STATES ADD -------------- //

    // ------------- MANAGE SYSTEM STATES ADD DATA -------------- //
    public function system_states_add_data(Request $req){
        $save_data['name']                  = $req->name;
        $save_data['system_countries_id']   = $req->system_countries_id;
        $save_data['latitude']              = $req->latitude;
        $save_data['longitude']             = $req->longitude;
        $save_data['created_at']            = date('Y-m-d H:i:s');
        $save_data['updated_at']            = date('Y-m-d H:i:s');
        $save_data['status']                = $req->status;
        
        $system_states_id = DB::table('system_states')->insertGetId($save_data);

        if($system_states_id > 0){ 
            session()->flash('success', 'State added successfully!');
            return redirect('admin/system_states/'.$save_data['system_countries_id'] );
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM STATES ADD DATA -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES EDIT -------------- //
    public function system_states_edit(Request $request){
        if ($request->session()->has('id')) {
            $system_states = DB::table('system_states')->where('system_states_id', $request->id)->get()->first();
            return view('admin.system_states_edit', compact('system_states'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES EDIT -------------- //

    // ------------- MANAGE SYSTEM COUNTRIES EDIT DATA -------------- //
    public function system_states_edit_data(Request $req){
        $update_data['name']                  = $req->name;
        $update_data['system_countries_id']   = $req->system_countries_id;
        $update_data['latitude']              = $req->latitude;
        $update_data['longitude']             = $req->longitude;
        $update_data['updated_at']            = date('Y-m-d H:i:s');
        $update_data['status']                = $req->status;
        
        $updated = DB::table('system_states')->where('system_states_id', $req->system_states_id)->update($update_data);
        
        if($updated > 0){ 
            session()->flash('success', 'State updated successfully!');
            return redirect('admin/system_states/'.$update_data['system_countries_id'] );
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM COUNTRIES EDIT DATA -------------- //

    // ------------- MANAGE SURVEY LIST -------------- //
    public function survey_list(Request $request){
        if ($request->session()->has('id')) {
            $fetch_data = DB::table('survey_list')->orderBy('survey_list_id', 'DESC')->get();
            $survey_list=[];
            foreach ($fetch_data as $key => $data) {
               $partner=DB::table('users_partners')->where('users_partners_id', $data->users_partners_id)->first();
               $data->partner=$partner;
               $category_name=DB::table('survey_categories')->where('survey_categories_id', $data->survey_categories_id)->first(); 
               $data->category_name=$category_name;
               $survey_reward=DB::table('survey_rewards')->where('survey_rewards_id', $data->survey_rewards_id)->first(); 
               $data->survey_reward=$survey_reward;
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
                $data->survey_list_qs=$count;
               $total_reponses = DB::table('survey_list_reponses')->where('survey_list_id', $data->survey_list_id)->get();
               $data->total_reponses=$total_reponses;
               if($data->users_system_id){
                   $users_system = DB::table('users_system')->where('users_system_id', $data->users_system_id)->first();
                   $data->users_system=$users_system;
               }
               $survey_list[]=$data;
            }
            return view('admin.survey_list', compact('survey_list'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST -------------- //

    // ------------- MANAGE SURVEY LIST UPDATE -------------- //
    public function survey_list_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_list')->where('survey_list_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/survey_list');
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
                        return redirect('admin/survey_list');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/survey_list');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/survey_list');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/survey_list');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST DELETE -------------- //

    // ------------- MANAGE SURVEY LIST ADD -------------- //
    public function survey_list_add(Request $request){
        if ($request->session()->has('id')) {
            $users_partners = DB::table('users_partners')->orderBy('users_partners_id', 'DESC')->get();
            $survey_categories = DB::table('survey_categories')->orderBy('survey_categories_id', 'DESC')->get();
            $survey_rewards = DB::table('survey_rewards')->orderBy('survey_rewards_id', 'DESC')->get();
            return view('admin.survey_list_add', compact('users_partners', 'survey_categories', 'survey_rewards'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST ADD -------------- //

    // ------------- MANAGE SURVEY LIST ADD DATA -------------- //
    public function survey_list_add_data(Request $req){
        $save_data['users_system_id']       = session('id');
        $save_data['users_partners_id']     = $req->users_partners_id;
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
            return redirect('admin/survey_list');
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
            return view('admin.survey_list_edit', compact('survey_list', 'users_partners', 'survey_categories', 'survey_rewards'));
        } else {
            return redirect('admin');
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
            return redirect('admin/survey_list');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY LIST EDIT DATA -------------- //

    // ------------- MANAGE SURVEY LIST GRAPH -------------- //
    public function survey_list_graphs(Request $request){
        if ($request->session()->has('id')) {
            $survey_list_id=$request->s_id;
            $survey_list_graphs = DB::table('survey_list')->where('survey_list_id', $survey_list_id)->first();
            return view('admin.survey_list_graphs', compact('survey_list_graphs','survey_list_id'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST GRAPH -------------- //

    // ------------- MANAGE SURVEY LIST QS ADD -------------- //
    public function survey_list_qs(Request $request){
        if ($request->session()->has('id')) {
            $survey_list = DB::table('survey_list')->where('survey_list_id', $request->s_id)->get()->first();
            $fetch_data = DB::table('survey_list_qs')->where('survey_list_id', $request->s_id)->where('status', 'Active')->orderBy('sort_order','desc')->get();
            return view('admin.survey_list_qs', compact('fetch_data', 'survey_list'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST QS ADD -------------- //

    // ------------- MANAGE SURVEY LIST QS UPDATE -------------- //
    public function survey_list_qs_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_list_qs')->where('survey_list_qs_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/survey_list_qs/'.$req->s_id);
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
                        return redirect('admin/survey_list_qs/'.$req->s_id);
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/survey_list_qs/'.$req->s_id);
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/survey_list_qs/'.$req->s_id);
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/survey_list_qs/'.$req->s_id);
            }
        } else {
            return redirect('admin');
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
            $survey_list = DB::table('survey_list')->where('survey_list_id', $request->s_id)->get()->first();
            return view('admin.survey_list_qs_add', compact('survey_list'));
        } else {
            return redirect('admin');
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
            return redirect('admin/survey_list_qs/'.$req->survey_list_id);
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
                return redirect('admin/survey_list_qs/'.$req->survey_list_id);
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
            return view('admin.survey_list_qs_edit', compact('survey_list', 'survey_list_qs'));
        } else {
            return redirect('admin');
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
            return redirect('admin/survey_list_qs/'.$req->survey_list_id);
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
            $s_id=$request->s_id;
            return view('admin.survey_list_reponses',compact('s_id'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY LIST -------------- //

    // ------------- MANAGE SURVEY LIST -------------- //
    public function survey_list_responses(Request $request){
            $fetch_data = DB::table('survey_list_reponses')->where('survey_list_id', $request->s_id)->get();
            $final_data=[];
            foreach ($fetch_data as $key => $data) {
                $data_survey= DB::table('survey_list')->where('survey_list_id', $data->survey_list_id)->first(); 
                $data->data_survey=$data_survey;
                $data_survey_categories= DB::table('survey_categories')->where('survey_categories_id', $data_survey->survey_categories_id)->first(); 
                $data->data_survey_categories=$data_survey_categories;
                $data_survey_rewards = DB::table('survey_rewards')->where('survey_rewards_id', $data_survey->survey_rewards_id)->first(); 
                $data->data_survey_rewards=$data_survey_rewards;
                $users_data = DB::table('users_customers')->where('users_customers_id', $data->users_customers_id)->first();
                $data->users_data=$users_data;
                $survey_answers = DB::table('survey_list_qs_answers')->where('survey_list_qs_answers_id', $data->survey_list_qs_answers_id)->first();
                $data->survey_answers=$survey_answers;
                if($data->survey_answers){
                $survey_questions = DB::table('survey_list_qs')->where('survey_list_qs_id', $survey_answers->survey_list_qs_id)->first(); 
                $data->survey_questions=$survey_questions;}
                if(isset($survey_answers->survey_list_qs_id)){
                $answers_reponses = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $survey_answers->survey_list_qs_id)->get();
                $data->answers_reponses=$answers_reponses;}
                $users_reward = DB::table('users_rewards')->where('survey_list_id',$data->survey_list_id)->where('users_customers_id',$data->users_customers_id)->first();
                if($users_reward){
                    $data->reward_assigned="yes";
                }else{
                    $data->reward_assigned="no";
                }
                $final_data[]=$data;
            }
            if(count($final_data) > 0){ 
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $final_data;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No data Found.";
            }

        return response()
            ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE SURVEY LIST -------------- //
    // ------------- MANAGE SINGLE SURVEY -------------- //
    public function get_single_survey_data(Request $request){
            $data = DB::table('survey_list_reponses')->where('survey_list_reponses_id', $request->survey_id)->first();
            $data_survey= DB::table('survey_list')->where('survey_list_id',$data->survey_list_id)->first();
            $data_reward= DB::table('survey_rewards')->where('survey_rewards_id',$data_survey->survey_rewards_id)->first();
            $data->reward_data=$data_reward;
            if($data){ 
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $data;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "No data Found.";
            }

        return response()
            ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE SINGLE SURVEY -------------- //


    // ------------- MANAGE SURVEY REWARDS -------------- //
    public function survey_rewards(Request $request){
        if ($request->session()->has('id')) {
            $survey_rewards = db::table('survey_rewards')->orderBy('survey_rewards_id', 'DESC')->get();
            return view('admin.survey_rewards', compact('survey_rewards'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY REWARDS -------------- //

    // ------------- MANAGE SURVEY REWARDS UPDATE -------------- //
    public function survey_rewards_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_rewards')->where('survey_rewards_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/survey_rewards');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE SURVEY REWARDS UPDATE -------------- //

    // ------------- MANAGE SURVEY REWARDS DELETE -------------- //
    public function survey_rewards_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('survey_rewards')->where('survey_rewards_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('survey_rewards')->where('survey_rewards_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/survey_rewards');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/survey_rewards');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/survey_rewards');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/survey_rewards');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY REWARDS DELETE -------------- //

    // ------------- MANAGE SURVEY REWARDS ADD -------------- //
    public function survey_rewards_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.survey_rewards_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY REWARDS ADD -------------- //

    // ------------- MANAGE SURVEY REWARDS ADD DATA -------------- //
    public function survey_rewards_add_data(Request $req){
        $save_data['name']                = $req->name;
        $save_data['reward']                = $req->reward;
        $save_data['reward_type']                = $req->reward_type;
        if($req->reward_type== "Points"){
            $save_data['quantity']                = $req->quantity;
        }
        $save_data['reward']              = $req->reward;
        $save_data['created_at']          = date('Y-m-d H:i:s');
        $save_data['updated_at']          = date('Y-m-d H:i:s');
        $save_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_rewards' ;
            $image_n=  "uploads/survey_rewards/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['image'] = $image_n;
        }   
        $survey_rewards_id = DB::table('survey_rewards')->insertGetId($save_data);

        if($survey_rewards_id > 0){ 
            session()->flash('success', 'Reward added successfully!');
            return redirect('admin/survey_rewards');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY REWARDS ADD DATA -------------- //

    // ------------- MANAGE SURVEY REWARDS EDIT -------------- //
    public function survey_rewards_edit(Request $request){
        if ($request->session()->has('id')) {
            $survey_rewards = DB::table('survey_rewards')->where('survey_rewards_id', $request->id)->get()->first();
            return view('admin.survey_rewards_edit', compact('survey_rewards'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY REWARDS EDIT  -------------- //

    // ------------- MANAGE SURVEY REWARDS EDIT DATA -------------- //
    public function survey_rewards_edit_data(Request $req){
        $update_data['name']                = $req->name;
        $update_data['reward_type']          = $req->reward_type;
        if($req->reward_type == "Points"){
            $update_data['quantity']          = $req->quantity;
        }
        $update_data['reward']              = $req->reward;
        $update_data['updated_at']          = date('Y-m-d H:i:s');
        $update_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_rewards' ;
            $image_n=  "uploads/survey_rewards/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['image'] = $image_n;
        }   
        $updated = DB::table('survey_rewards')->where('survey_rewards_id', $req->survey_rewards_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'Reward updated successfully!');
            return redirect('admin/survey_rewards');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY REWARDS EDIT DATA -------------- //

    // ------------- MANAGE SURVEY CATEGORIES -------------- //
    public function survey_categories(Request $request){
        if ($request->session()->has('id')) {
            $survey_categories = db::table('survey_categories')->orderBy('survey_categories_id', 'DESC')->get();
            return view('admin.survey_categories', compact('survey_categories'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES -------------- //

    // ------------- MANAGE SURVEY CATEGORIES UPDATE -------------- //
    public function survey_categories_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('survey_categories')->where('survey_categories_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/survey_categories');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES UPDATE -------------- //

    // ------------- MANAGE SURVEY CATEGORIES DELETE -------------- //
    public function survey_categories_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('survey_categories')->where('survey_categories_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('survey_categories')->where('survey_categories_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/survey_categories');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/survey_categories');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/survey_categories');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/survey_categories');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES DELETE -------------- //

    // ------------- MANAGE SURVEY CATEGORIES ADD -------------- //
    public function survey_categories_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.survey_categories_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES ADD -------------- //

    // ------------- MANAGE SURVEY CATEGORIES ADD DATA -------------- //
    public function survey_categories_add_data(Request $req){
        $save_data['name']                = $req->name;
        $save_data['created_at']          = date('Y-m-d H:i:s');
        $save_data['updated_at']          = date('Y-m-d H:i:s');
        $save_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_categories' ;
            $image_n=  "uploads/survey_categories/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['image'] = $image_n;
        }   
        $survey_categories_id = DB::table('survey_categories')->insertGetId($save_data);

        if($survey_categories_id > 0){ 
            session()->flash('success', 'Category added successfully!');
            return redirect('admin/survey_categories');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES ADD DATA -------------- //

    // ------------- MANAGE SURVEY CATEGORIES EDIT -------------- //
    public function survey_categories_edit(Request $request){
        if ($request->session()->has('id')) {
            $survey_categories = DB::table('survey_categories')->where('survey_categories_id', $request->id)->get()->first();
            return view('admin.survey_categories_edit', compact('survey_categories'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES EDIT  -------------- //

    // ------------- MANAGE SURVEY CATEGORIES EDIT DATA -------------- //
    public function survey_categories_edit_data(Request $req){
        $update_data['name']                = $req->name;
        $update_data['updated_at']          = date('Y-m-d H:i:s');
        $update_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/survey_categories' ;
            $image_n=  "uploads/survey_categories/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['image'] = $image_n;
        }   
        $updated = DB::table('survey_categories')->where('survey_categories_id', $req->survey_categories_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'Category updated successfully!');
            return redirect('admin/survey_categories');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SURVEY CATEGORIES EDIT DATA -------------- //

    // ------------- MANAGE BLOGS -------------- //
    public function blogs(Request $request){
        if ($request->session()->has('id')) {
            $blogs = db::table('blogs')->orderBy('blogs_id', 'DESC')->get();
            return view('admin.blogs', compact('blogs'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE BLOGS -------------- //

    // ------------- MANAGE BLOGS UPDATE -------------- //
    public function blogs_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('blogs')->where('blogs_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/blogs');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE BLOGS UPDATE -------------- //

    // ------------- MANAGE BLOGS DELETE -------------- //
    public function blogs_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('blogs')->where('blogs_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('blogs')->where('blogs_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/blogs');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/blogs');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/blogs');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/blogs');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE BLOGS DELETE -------------- //

    // ------------- MANAGE BLOGS ADD -------------- //
    public function blogs_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.blogs_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE BLOGS ADD -------------- //

    // ------------- MANAGE BLOGS ADD DATA -------------- //
    public function blogs_add_data(Request $req){
        $save_data['title']               = $req->title;
        $save_data['description']         = $req->description;
        $save_data['is_featured']         = $req->is_featured;
        $save_data['date_added']          = date('Y-m-d H:i:s');
        $save_data['date_modified']       = date('Y-m-d H:i:s');
        $save_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/blogs' ;
            $image_n=  "uploads/blogs/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['image'] = $image_n;
        }   
        $blogs_id = DB::table('blogs')->insertGetId($save_data);

        if($blogs_id > 0){ 
            session()->flash('success', 'Blog added successfully!');
            return redirect('admin/blogs');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE BLOGS ADD DATA -------------- //

    // ------------- MANAGE BLOGS EDIT -------------- //
    public function blogs_edit(Request $request){
        if ($request->session()->has('id')) {
            $blogs = DB::table('blogs')->where('blogs_id', $request->id)->get()->first();
            return view('admin.blogs_edit', compact('blogs'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE BLOGS EDIT  -------------- //

    // ------------- MANAGE BLOGS EDIT DATA -------------- //
    public function blogs_edit_data(Request $req){
        $update_data['title']               = $req->title;
        $update_data['description']         = $req->description;
        $update_data['is_featured']         = $req->is_featured;
        $update_data['date_modified']       = date('Y-m-d H:i:s');
        $update_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/blogs' ;
            $image_n=  "uploads/blogs/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['image'] = $image_n;
        }   
        $updated = DB::table('blogs')->where('blogs_id', $req->blogs_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'Category updated successfully!');
            return redirect('admin/blogs');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE BLOGS EDIT DATA -------------- //

    // ------------- MANAGE SYSTEM SETTINGS -------------- //
    public function system_settings(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'system_settings';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_settings', compact('system_settings','page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //
    public function system_settings_edit(Request $req){
        $page_name  = $req->page_name;

        if(isset($req->invite_text)){
            $data['description']          = $req->invite_text;
            DB::table('system_settings')->where('type', 'invite_text')->update($data);
        } 

        if(isset($req->email)){
            $data['description']          = $req->email;
            DB::table('system_settings')->where('type', 'email')->update($data);
        } 

        if(isset($req->phone)){
            $data['description']          = $req->phone;
            DB::table('system_settings')->where('type', 'phone')->update($data);
        } 

        if(isset($req->system_name)){
            $data['description']          = $req->system_name;
            DB::table('system_settings')->where('type', 'system_name')->update($data);
        } 

        if(isset($req->address)){
            $data['description']          = $req->address;
            DB::table('system_settings')->where('type', 'address')->update($data);
        } 

        if(isset($req->social_login)){
            $data['description']          = $req->social_login;
            DB::table('system_settings')->where('type', 'social_login')->update($data);
        } 

        if(isset($req->link_facebook)){
            $data['description']          = $req->link_facebook;
            DB::table('system_settings')->where('type', 'link_facebook')->update($data);
        } 

        if(isset($req->link_instagram)){
            $data['description']          = $req->link_instagram;
            DB::table('system_settings')->where('type', 'link_instagram')->update($data);
        } 

        if(isset($req->link_linkedin)){
            $data['description']          = $req->link_linkedin;
            DB::table('system_settings')->where('type', 'link_linkedin')->update($data);
        } 

        if(isset($req->tutorial_link)){
            $data['description']          = $req->tutorial_link;
            DB::table('system_settings')->where('type', 'tutorial_link')->update($data);
        } 

        if(isset($req->link_twitter)){
            $data['description']          = $req->link_twitter;
            DB::table('system_settings')->where('type', 'link_twitter')->update($data);
        } 

        if(isset($req->guidelines)){
            $data['description']          = $req->guidelines;
            DB::table('system_settings')->where('type', 'guidelines')->update($data);
        } 

        if(isset($req->about_text)){
            $data['description']          = $req->about_text;
            DB::table('system_settings')->where('type', 'about_text')->update($data);
        }

        if(isset($req->terms_text)){
            $data['description']          = $req->terms_text;
            DB::table('system_settings')->where('type', 'terms_text')->update($data);
        }

        if(isset($req->privacy_text)){
            $data['description']          = $req->privacy_text;
            DB::table('system_settings')->where('type', 'privacy_text')->update($data);
        }

        if(isset($req->guidelines)){
            $data['description']          = $req->guidelines;
            DB::table('system_settings')->where('type', 'guidelines')->update($data);
        }

        if(isset($req->guidelines_video)){
            $data['description']          = $req->guidelines_video;
            DB::table('system_settings')->where('type', 'guidelines_video')->update($data);
        }

        if(isset($req->welcome_note)){
            $data['description']          = $req->welcome_note;
            DB::table('system_settings')->where('type', 'welcome_note')->update($data);
        }

        if (isset($req->welcome_bg)) {
            $image              = $req->file('welcome_bg');
            $image1_name        = $image->getClientOriginalName();
            $destinationPath    = public_path().'/uploads/system_image' ;
            $image_n            = $image1_name;
            $uploaded           = $image->move($destinationPath, $image1_name);
            
            $data['description'] = $image_n;
            DB::table('system_settings')->where('type', 'welcome_bg')->update($data);
        }   

        if(isset($req->welcome_questions)){
            $data['description']          = $req->welcome_questions;
            DB::table('system_settings')->where('type', 'welcome_questions')->update($data);
        }

        if(isset($req->welcome_users)){
            $data['description']          = $req->welcome_users;
            DB::table('system_settings')->where('type', 'welcome_users')->update($data);
        }

        if(isset($req->welcome_heading)){
            $data['description']          = $req->welcome_heading;
            DB::table('system_settings')->where('type', 'welcome_heading')->update($data);
        }

        if(isset($req->eco_countr)){
            $data['description']          = $req->eco_countr;
            DB::table('system_settings')->where('type', 'eco_countr')->update($data);
        }

        if(isset($req->text_questions_allowed)){
            $data['description']          = $req->text_questions_allowed;
            DB::table('system_settings')->where('type', 'text_questions_allowed')->update($data);
        }

        if(isset($req->single_questions_allowed)){
            $data['description']          = $req->single_questions_allowed;
            DB::table('system_settings')->where('type', 'single_questions_allowed')->update($data);
        }

        if(isset($req->multiple_questions_allowed)){
            $data['description']          = $req->multiple_questions_allowed;
            DB::table('system_settings')->where('type', 'multiple_questions_allowed')->update($data);
        }

        if(isset($req->multilevel_questions_allowed)){
            $data['description']          = $req->multilevel_questions_allowed;
            DB::table('system_settings')->where('type', 'multilevel_questions_allowed')->update($data);
        }

        if(isset($req->prize_explanation)){
            $data['description']          = $req->prize_explanation;
            DB::table('system_settings')->where('type', 'prize_explanation')->update($data);
        }

        if (isset($req->image)) {
            $image              = $req->file('image');
            $image1_name        = $image->getClientOriginalName();
            $destinationPath    = public_path().'/uploads/system_image' ;
            $image_n            = $image1_name;
            $uploaded           = $image->move($destinationPath, $image1_name);
            
            $data['description'] = $image_n;
            DB::table('system_settings')->where('type', 'system_image')->update($data);
        }   

        session()->flash('success', 'System settings updated successfully!');
        return redirect('admin/'.$page_name);
    }
    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //

    // ------------- MANAGE SYSTEM USERS -------------- //
    public function users_system(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_system';
            $users= db::table('users_system')->orderBy('users_system_id', 'DESC')->get();
            return view('admin.users_system', compact('users', 'page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS -------------- //

    // ------------- UPDATE SYSTEM USERS -------------- //
    public function users_system_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('users_system')->where('users_system_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/users_system');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE SYSTEM USERS -------------- //

    // ------------- DELETE SYSTEM USERS -------------- //
    public function users_system_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('users_system')->where('users_system_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('users_system')->where('users_system_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/users_system');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/users_system');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/users_system');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/users_system');
            }
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- DELETE SYSTEM USERS -------------- //

    // ------------- MANAGE SYSTEM USERS ADD -------------- //
    public function users_system_add(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_system';
            $roles = DB::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            return view('admin.users_system_add', compact('roles', 'page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS ADD -------------- //

    // ------------- MANAGE SYSTEM USERS ADD DATA -------------- //
    public function users_system_add_data(Request $req){
        // return $req->all();
        $save_data['users_system_roles_id']     = $req->users_system_roles_id;
        $save_data['first_name']                = $req->first_name;
        $save_data['email']                     = $req->email;
        $save_data['password']                  = $req->password;
        $save_data['mobile']                    = $req->mobile;
        $save_data['city']                      = $req->city;
        $save_data['address']                   = $req->address;
        $save_data['total_surveys_allowed']     = $req->total_surveys_allowed;
        $save_data['created_at']                = date('Y-m-d H:i:s');
        $save_data['updated_at']                = date('Y-m-d H:i:s');
        $save_data['status']                    = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/users_system' ;
            $image_n=  "uploads/users_system/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['user_image'] = $image_n;
        }   
        $users_system_id = DB::table('users_system')->insertGetId($save_data);

        if($users_system_id > 0){ 
            session()->flash('success', 'User added successfully!');
            return redirect('admin/users_system');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS ADD DATA -------------- //

    // ------------- MANAGE SYSTEM USERS EDIT -------------- //
    public function users_system_edit(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_system';
            $roles = DB::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            $users_system = DB::table('users_system')->where('users_system_id', $request->id)->first();
            return view('admin.users_system_edit', compact('roles', 'users_system', 'page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS EDIT DATA -------------- //
    public function users_system_edit_data(Request $req){
        $update_data['users_system_roles_id']     = $req->users_system_roles_id;
        $update_data['first_name']                = $req->first_name;
        $update_data['email']                     = $req->email;
        $update_data['password']                  = $req->password;
        $update_data['mobile']                    = $req->mobile;
        $update_data['city']                      = $req->city;
        $update_data['address']                   = $req->address;
        $update_data['total_surveys_allowed']     = $req->total_surveys_allowed;
        $update_data['status']                    = $req->status;
        $update_data['updated_at']                = date('Y-m-d H:i:s');
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/users_system' ;
            $image_n=  "uploads/users_system/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $update_data['user_image'] = $image_n;
        }   
        $updated = DB::table('users_system')->where('users_system_id', $req->users_system_id)->update($update_data);

        if($updated > 0){ 
            session()->flash('success', 'User updated successfully!');
            return redirect('admin/users_system');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS EDIT DATA -------------- //

    // ------------- MANAGE SYSTEM ROLES -------------- //
    public function users_system_roles(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_system_roles';
            $users_system_roles = db::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            return view('admin.users_system_roles', compact('users_system_roles','page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES -------------- //

    // ------------- MANAGE SYSTEM ROLES ADD -------------- //
    public function users_system_roles_add(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'users_system_roles';
            return view('admin.users_system_roles_add', compact('page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES ADD -------------- //

    // ------------- MANAGE SYSTEM ROLES ADD DATA -------------- //
    public function users_system_roles_add_data(Request $req){
        $data['dashboard']           = $req->dashboard;
        $data['users_customers']     = $req->users_customers;
        $data['users_system']        = $req->users_system;
        $data['users_system_roles']  = $req->users_system_roles;
        $data['system_settings']     = $req->system_settings;
        $data['account_settings']    = $req->account_settings;
        
        $users_system_id = DB::table('users_system_roles')->insertGetId($data);

        if($users_system_id > 0){ 
            session()->flash('success', 'Role added successfully!');
            return redirect('admin/users_system_roles');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM ROLES ADD DATA -------------- //

    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //
    public function users_system_roles_edit(Request $request,$id){
        if (session()->has('id')) {
            $page_name = 'users_system_roles';
            $users_system_roles = DB::table('users_system_roles')->where('users_system_roles_id', $id)->get()->first();
            return view('admin.users_system_roles_edit', compact('users_system_roles', 'page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //
    public function users_system_roles_edit_data(Request $req){
        $data['name']                = $req->name;
        $data['status']              = $req->status;
        
        $data['dashboard']           = $req->dashboard;
        $data['users_customers']     = $req->users_customers;
        $data['users_system']        = $req->users_system;
        $data['users_system_roles']  = $req->users_system_roles;
        $data['system_settings']     = $req->system_settings;
        $data['account_settings']    = $req->account_settings;

        $updated = DB::table('users_system_roles')->where('users_system_roles_id', $req->users_system_roles_id)->update($data);

        if($updated > 0){ 
            session()->flash('success', 'Role updated successfully!');
            return redirect('admin/users_system_roles');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //

    // ------------- DELETE SYSTEM USERS ROLES -------------- //
    public function users_system_roles_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('users_system')->where('users_system_roles_id', $req->id)->get();

                if(count($checkdata) == 0){
                    $del = DB::table('users_system_roles')->where('users_system_roles_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/users_system_roles');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/users_system_roles');
                    }
                } else {
                    Session::flash('error', ' This role is assigned to some users. Delete the users first.'); 
                    return redirect('admin/users_system_roles');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/users_system_roles');
            }
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- DELETE SYSTEM USERS ROLES -------------- //

    // ------------- MANAGE SYSTEM  ABOUT US -------------- //
    public function system_about_us(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'system_about_us';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_about_us', compact('system_settings','page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM ABOUT US -------------- //

    // ------------- MANAGE SYSTEM TERMS -------------- //
    public function system_terms(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'system_terms';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_terms', compact('system_settings','page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM TERMS -------------- //

    // ------------- MANAGE SYSTEM PRIVACY -------------- //
    public function system_privacy(Request $request){
        if ($request->session()->has('id')) {
            $page_name = 'system_privacy';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_privacy', compact('system_settings','page_name'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE SYSTEM PRIVACY -------------- //

    // ------------- ACCOUNT SETTINGS -------------- //
    public function account_settings(){
        if(session()->has('id')){
            $page_name = 'account_settings';
            $fetch_data = DB::table('users_system')->where('users_system_id',session('id'))->get();
            return view('admin.account_settings',compact('fetch_data','page_name'));
        } else {
            return redirect('admin/admin');
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
                $path = public_path('uploads/users_system/');
                $prefix = 'user-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                    $insert['user_image'] = 'uploads/users_system/' . $img_name;
                }
            }
        }

        $a = DB::table('users_system')->where('users_system_id',$id)->update($insert);
        if ($a) {
            Session::flash('success', ' Profile Updated successfully'); 
            return redirect('admin/account_settings');
        } else {
            Session::flash('error', ' oops! something went wrong'); 
            return redirect('admin/account_settings');
        }
    }
    // ------------- UPDATE ACCOUNT SETTINGS -------------- //

    // ------------- MANAGE PARTNERS IMAGES -------------- //
    public function partners_images(Request $request){
        if ($request->session()->has('id')) {
            $partners_images = db::table('partners_images')->orderBy('partners_images_id', 'DESC')->get();
            return view('admin.partners_images', compact('partners_images'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS IMAGES -------------- //

    // ------------- MANAGE PARTNERS IMAGES UPDATE -------------- //
    public function partners_images_update(Request $req){
        $update_array['status'] = $req->status;        
        $updated = DB::table('partners_images')->where('partners_images_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully'); 
            return redirect('admin/partners_images');
        } else {
            Session::flash('error', ' Oops! something went wrong'); 
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- MANAGE PARTNERS IMAGES UPDATE -------------- //

    // ------------- MANAGE PARTNERS IMAGES DELETE -------------- //
    public function partners_images_delete(Request $req){
        if(session()->has('id')){
            if(!empty($req->id)){
                $checkdata = DB::table('partners_images')->where('partners_images_id', $req->id)->get();

                if(count($checkdata) != 0){
                    $del = DB::table('partners_images')->where('partners_images_id', $req->id)->delete();
                    if($del){
                        Session::flash('success', ' Data Deleted successfully'); 
                        return redirect('admin/partners_images');
                    } else {
                        Session::flash('error', ' Oops! something went wrong'); 
                        return redirect('admin/partners_images');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status'); 
                    return redirect('admin/partners_images');
                }
            } else {
                Session::flash('error', ' No Data Found'); 
                return redirect('admin/partners_images');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS IMAGES DELETE -------------- //

    // ------------- MANAGE PARTNERS IMAGES ADD -------------- //
    public function partners_images_add(Request $request){
        if ($request->session()->has('id')) {
            return view('admin.partners_images_add');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PARTNERS IMAGES ADD -------------- //

    // ------------- MANAGE PARTNBERS IMAGES ADD DATA -------------- //
    public function partners_images_add_data(Request $req){
        $save_data['name']                = $req->name;
        $save_data['status']              = $req->status;
        
        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path().'/uploads/partners_images' ;
            $image_n=  "uploads/partners_images/".$image1_name;
            $image->move($destinationPath, $image1_name);
            
            $save_data['image'] = $image_n;
        }   
        $partners_images = DB::table('partners_images')->insertGetId($save_data);

        if($partners_images > 0){ 
            session()->flash('success', 'Data added successfully!');
            return redirect('admin/partners_images');
        } else {
            session()->flash('error', 'Oops! Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE PARTNBERS IMAGES ADD DATA -------------- //
    
    // ------------- ADD USER REWARD-------------- //
    public function add_user_reward(Request $req){
        if (isset($req->users_customers_id) && isset($req->survey_list_id) && isset($req->description)) {
            $survey_list = DB::table('survey_list')->where('survey_list_id', $req->survey_list_id)->first();
            $saveData=[
                'survey_list_id' => $req->survey_list_id,
                'survey_rewards_id' => $survey_list->survey_rewards_id,
                'users_customers_id' => $req->users_customers_id,
                'description' => $req->description,
                'date_added' => Carbon::now(),
                'status' => "unclaimed",
            ];
            $user_reward=DB::table('users_rewards')->insertGetId($saveData);

            if($user_reward){ 
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Data Added Successfully";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "something went wrong.";
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
// ------------- ADD USER REWARD-------------- //

/* SURVEY RESPONSES GRAPH  */
    public function survey_responses_graph(){
        $fetch_data = DB::table('survey_list_reponses')
            ->join('users_customers', 'survey_list_reponses.users_customers_id', '=', 'users_customers.users_customers_id')
            ->join('system_countries', 'users_customers.system_countries_id', '=', 'system_countries.system_countries_id')
            ->selectRaw('system_countries.name, COUNT(DISTINCT survey_list_reponses.survey_list_id) as attempts')
            ->groupBy('system_countries.name')
            ->get();
            $names = [];
            $attempts = [];
            
            foreach ($fetch_data as $result) {
                $names[] = $result->name;
                $attempts[] = $result->attempts;
            }
            
            $data = [
                'attempts' => $attempts,
                'names' => $names,
            ];

        if (count($data) > 0) {
            $response = ["code" => 200, "status" => "success", "data" => $data];
        } else {
            $response = ["code" => 404, "status" => "error", "message" => "No Data found."];
        }

        return response()->json([
            'status' => $response["status"],
            isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]
        ])->header('Content-Type', 'application/json');
    }
 /*SURVEY RESPONSES GRAPH */

 /*SURVEY GRAPH */
 public function survey_graph(Request $req){
     $survey_list_id = $req->survey_list_id;
         
    $fetch_data = DB::table('system_countries')
        ->select('system_countries.name', 'attempts')
        ->joinSub(function ($query) use ($survey_list_id) {
            $query->select('users_customers.system_countries_id', DB::raw('COUNT(*) as attempts'))
                ->from('users_customers')
                ->join('survey_list_reponses', 'users_customers.users_customers_id', '=', 'survey_list_reponses.users_customers_id')
                ->where('survey_list_reponses.survey_list_id', $survey_list_id)
                ->groupBy('users_customers.system_countries_id');
        }, 'counts', 'system_countries.system_countries_id', '=', 'counts.system_countries_id')
        ->get();
     $names = [];
     $attempts = [];
 
     foreach ($fetch_data as $result) {
         $names[] = $result->name;
         $attempts[] = $result->attempts;
     }
 
     $data = [
         'attempts' => $attempts,
         'names' => $names,
     ];
 
     if (count($data) > 0) {
         $response = ["code" => 200, "status" => "success", "data" => $data];
     } else {
         $response = ["code" => 404, "status" => "error", "message" => "No Data found."];
     }
 
     return response()->json([
         'status' => $response["status"],
         isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]
     ])->header('Content-Type', 'application/json');
 }
 /*SURVEY GRAPH */
 
    // ------------- MANAGE CUSTOMERS USERS REWARDS -------------- //
    public function users_customers_rewards(Request $request,$users_id){
        if ($request->session()->has('id')) {
            if(empty($request->get('filter'))){
                $fetch_data = DB::table('users_rewards')->where("users_customers_id",$users_id)->orderBy('users_rewards_id', 'DESC')->get();
            } else {
                $fetch_data = DB::table('users_rewards')->where("users_customers_id",$users_id)->where('status', $request->get('filter'))->orderBy('users_rewards_id', 'DESC')->get();
            }
            $get_data=[];
            foreach ($fetch_data as $key => $reward) {
                $reward->survey_list = DB::table('survey_list')->where('survey_list_id', $reward->survey_list_id)->first();
                $reward->survey_rewards = DB::table('survey_rewards')->where('survey_rewards_id', $reward->survey_rewards_id)->first();
                $get_data[]=$reward;
            }


            $filter = $request->get('filter');
            return view('admin.users_customers_rewards', compact('get_data', 'filter'));
        } else {
            return redirect('admin/admin');
        }
    }
    // ------------- MANAGE CUSTOMERS USERS REWARDS -------------- //
 
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