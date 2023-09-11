@extends('layout.partners.list_master')

@section('content')
    <?php $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->get()->first(); ?>

    <style>
        input{
           border-radius: 20px;
        }
        .avatar {
          vertical-align: middle;
          width: 50px;
          height: 50px;
          border-radius: 50%;
        }
        .imageUpload
        {
            display: none;
        }

        .profileImage
        {
            /* margin-top: -40px; */
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            width: 130px;
            height: 130px;
            color: white;
            justify-content: center;
            border: 1px solid #8f8989;
            overflow: hidden;
        }

        #profile-container img {
            width: 150px;
            height: 150px;
           
        }
    </style>
    <!--**********************************
        Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="col-md-12 mb-n5">
                <div class="col-sm-12 p-md-0">
                    <div class="col-sm-12 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        @section('titleBar')
                        <span class="ml-2">Survey Responses</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Survey Details</th>
                                                    <th>Customer Details</th>
                                                    <th>Questions Details</th>
                                                    <th>All Answers</th>
                                                    <th>Users Answers</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($survey_list_reponses as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <?php 
                                                            $data_survey                = DB::table('survey_list')->where('survey_list_id', $items->survey_list_id)->first(); 
                                                            $data_survey_categories     = DB::table('survey_categories')->where('survey_categories_id', $data_survey->survey_list_id)->first(); 
                                                            $data_survey_rewards        = DB::table('survey_rewards')->where('survey_rewards_id', $data_survey->survey_rewards_id)->first(); 
                                                        ?>
                                                        {{ $data_survey->name }} <br>
                                                        {{ $data_survey_categories->name }} <br>
                                                        {{ $data_survey_rewards->name }} <br>

                                                        <?php echo DB::table('survey_rewards')->where('survey_rewards_id', $data_survey->survey_rewards_id)->first()->name; ?>
                                                        (<?php echo DB::table('survey_rewards')->where('survey_rewards_id', $data_survey->survey_rewards_id)->first()->reward; ?>)   
                                                    </td>
                                                    <td>
                                                        <?php $users_data = DB::table('users_customers')->where('users_customers_id', $items->users_customers_id)->first(); ?>
                                                        {{ $users_data->first_name }} <br>
                                                        {{ $users_data->email }} <br>
                                                        {{ $users_data->phone }}
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $survey_answers = DB::table('survey_list_qs_answers')->where('survey_list_qs_answers_id', $items->survey_list_qs_answers_id)->first(); 
                                                            if($survey_answers){ 
                                                                $survey_questions = DB::table('survey_list_qs')->where('survey_list_qs_id', $survey_answers->survey_list_qs_id)->first(); 
                                                                echo $survey_questions->name;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if(isset($survey_answers->survey_list_qs_id)){ 
                                                                $answers_reponses = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $survey_answers->survey_list_qs_id)->get();
                                                                if($answers_reponses[0]){ 
                                                                    foreach($answers_reponses as $counter => $answers){
                                                                        echo ($counter + 1) . ' - ' .$answers->name . '<br>';
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </td>

                                                    <td> <?php echo $items->answer; ?> </td>
                                                    <td>{{ $items->created_at }}</td>
                                                    <td>{{ $items->updated_at }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection