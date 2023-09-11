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
                        <span class="ml-2">Manage Survey Questions </span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <legend> 
                                        {{$survey_list->name}}
                                        <a style="float: right;" class="btn btn-primary" href="{{url('/partners/survey_list_qs_add/' . $survey_list->survey_list_id)}}"> Add Questions </a>
                                    </legend>
                                    <hr class="col-md-12">

                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Sort Order</th>
                                                    <th>Type</th>
                                                    <th>Question</th>
                                                    <th>Answers</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 0; @endphp
                                                @foreach ($fetch_data as $key => $items)
                                                    @php
                                                        $answers_check = DB::table('survey_list_qs_answers')
                                                            ->where(['survey_list_qs_id'=> $items->survey_list_qs_id,'parent_qs_id'=> '0','qs_identifier'=>"Tree"])
                                                            ->first();
                                                    @endphp
                                                
                                                    @if ($answers_check || $items->question_type !="Multilevel Choice" ) 
                                                        @if (!$items) 
                                                            @continue
                                                        @endif
                                                
                                                        @php $count++; @endphp
                                                
                                                    <tr class="odd gradeX">
    
                                                    <td>{{ $count }}</td>
                                                    <td>
                                                        <a class="btn btn-info" href="{{url('/partners/survey_list_qs_sort_order/' . $items->survey_list_id . '/' . $items->survey_list_qs_id.'/up')}}">
                                                            <i class="fa fa-arrow-up"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/partners/survey_list_qs_sort_order/' . $items->survey_list_id . '/' . $items->survey_list_qs_id.'/down')}}">
                                                            <i class="fa fa-arrow-down"></i> 
                                                        </a>
                                                    </td>
                                                    <td>{{ $items->question_type }}</td>
                                                    <td>{{ $items->name }}</td>
                                                    <td>
                                                        <?php 
                                                            $answers_reponses = DB::table('survey_list_qs_answers')->where('survey_list_qs_id', $items->survey_list_qs_id)->get();
                                                            foreach($answers_reponses as $counter => $answers){
                                                                $question_type = DB::table('survey_list_qs')->where('survey_list_qs_id', $items->survey_list_qs_id)->first()->question_type;
                                                                if($question_type != 'Text'){
                                                                    $count_reponses = DB::table('survey_list_reponses')->where('survey_list_qs_answers_id', $answers->survey_list_qs_answers_id)->where('answer', $answers->name)->count();
                                                                    $total_responses = '('. $count_reponses .')';
                                                                } else {
                                                                    $total_responses = '';
                                                                }
                                                                echo ($counter + 1) . ' - ' .$answers->name . $total_responses . '<br>';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        @if ($items->status=='Active')
                                                        <span class="btn btn-success">Active</span>
                                                        @elseif ($items->status=='Deleted')
                                                        <span class="btn btn-danger">Deleted</span>
                                                        @else 
                                                        <span class="btn btn-warning">In Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info" href="{{url('/partners/survey_list_qs_edit/' . $items->survey_list_id . '/' . $items->survey_list_qs_id)}}">
                                                            <i class="fa fa-pencil"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/partners/survey_list_qs_update/' . $items->survey_list_id . '/' . $items->survey_list_qs_id . '/Active')}}">
                                                            <i class="fa fa-check"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" href="{{url('/partners/survey_list_qs_update/' . $items->survey_list_id . '/' . $items->survey_list_qs_id . '/Inactive')}}">
                                                            <i class="fa fa-times"></i> 
                                                        </a>

                                                        <a class="btn btn-danger" href="{{url('/partners/survey_list_qs_delete/' . $items->survey_list_id . '/' . $items->survey_list_qs_id)}}">
                                                            <i class="fa fa-trash"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
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