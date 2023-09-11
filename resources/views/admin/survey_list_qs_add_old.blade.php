@extends('layout.admin.list_master')

@section('content')
    <style>
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
        .error{
            color: red;
        }

        .errorto{
            color: red;
            background-color: rgb(244, 198, 198);
            padding-top: 15px;
            padding-bottom: 15px; 
            text-align: center;
        }

        .bootstrap-select {
            border: 10px solid red;
        }
    </style>
	<!--**********************************
           Chat box End
    ***********************************-->
    
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mb-n5">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <!-- <h4>Hi, welcome back!</h4> -->
                     
                        {{-- <p class="mb-0">Validation</p> --}}
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        @section('titleBar')
                        <span class="ml-2">Add Survey Questions</span>
                        @endsection               
                    </ol>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="/admin/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform" name="myform" enctype="multipart/form-data">
                                    @csrf

                                    <h3> {{$survey_list->name}} </h3><hr class="col-md-12" />
                                    <h5> General Section </h5><hr class="col-md-12" />

                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-4">
                                            <b>Question Type</b>
                                            <b>
                                                <select style="border:1px solid" name="question_type" id="question_type" class="form-control" onchange="remove_all_answers()" required>
                                                    <?php 
                                                        $text_questions_allowed = DB::table('system_settings')->where('type', 'text_questions_allowed')->first()->description;
                                                        $single_questions_allowed = DB::table('system_settings')->where('type', 'single_questions_allowed')->first()->description;
                                                        $multiple_questions_allowed = DB::table('system_settings')->where('type', 'multiple_questions_allowed')->first()->description;
                                                        $multilevel_questions_allowed = DB::table('system_settings')->where('type', 'multilevel_questions_allowed')->first()->description;
                                                    ?>

                                                    <?php if($text_questions_allowed == 'Yes'){ ?>
                                                    <option value="Text">Text</option>
                                                    <?php } ?>
                                                    
                                                    <?php if($single_questions_allowed == 'Yes'){ ?>
                                                    <option value="Single Choice">Single Choice</option>
                                                    <?php } ?>
                                                    
                                                    <?php if($multiple_questions_allowed == 'Yes'){ ?>
                                                    <option value="Multiple Choice">Multiple Choice</option>
                                                    <?php } ?>
                                                    
                                                    <?php if($multilevel_questions_allowed == 'Yes'){ ?>
                                                    <option value="Multilevel Choice">Multilevel Choice</option>
                                                    <?php } ?>
                                                </select>
                                            </b>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <b>Question</b>
                                            <b><input style="border:1px solid" type="text" name="name" class="form-control" placeholder="Enter Question" required></b>
                                        </div>

                                    </div>

                                    <hr class="col-md-12" />
                                    <h5> 
                                        Answers Section 
                                        <span class="pull-right">
                                            <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </span>
                                    </h5>

                                    <hr class="col-md-12" />
                                    <div id="answers_list_row_fields"></div>
                                    
                                    <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                    <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter" placeholder="Enter Answers Rows" required="">
                                    <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
		    </div>
        </div>
    </div>
					
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $("#answers_rows_counter_add").click(function(e) {
            answers_list_row = '';
            if($('#question_type').val() != 'Multilevel Choice'){
                answers_rows_counter = eval(parseInt($("#answers_rows_counter").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            } else {
                answers_rows_counter = eval(parseInt($("#answers_rows_counter").val()) + 1);
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter +'">';
                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<b>Choose Question</b>';
                        answers_list_row += '<select style="border:1px solid" name="parent_qs_id[]" id="parent_qs_id_'+ answers_rows_counter +'" onchange="get_answers_list(this.value, '+ answers_rows_counter +');"class="form-control" required>';
                            answers_list_row += '<option value="">Please select question</option>';
                        <?php 
                            $survey_list_qs = DB::table('survey_list_qs')->where('survey_list_id', $survey_list->survey_list_id)->get(); 
                            foreach($survey_list_qs as $data){
                        ?>
                            answers_list_row += '<option value="{{$data->survey_list_qs_id}}">{{$data->name}}</option>';
                        <?php } ?>
                        answers_list_row += '</select>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<b>Choose Answer</b>';
                        answers_list_row += '<select style="border:1px solid" name="parent_qs_answers_id[]" id="parent_qs_answers_id_'+ answers_rows_counter +'" class="form-control" required>';
                            answers_list_row += '<option value="">Please choose questions</option>';
                        answers_list_row += '</select>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<input type="checkbox" name="field_type[]" id="field_type_'+ answers_rows_counter +'" value="Yes" checked onchange="require_answer('+ answers_rows_counter +')"> Open Text Field <br>';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[]" id="answers_name_'+ answers_rows_counter +'" class="form-control" placeholder="Enter Answer Text">';
                    answers_list_row += '</div>';

                    answers_list_row += '<input type="hidden" class="form-control" name="survey_list_qs_answers_id[]" id="survey_list_qs_answers_id" value="0" required="">';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            }

            if(answers_rows_counter == 1){
                $("#answers_list_row_fields").html(answers_list_row);
                $("#answers_rows_counter").val(answers_rows_counter);
            } else {
                $("#answers_list_row_fields").append(answers_list_row);
                $("#answers_rows_counter").val(answers_rows_counter);
            }
        });
        
        $("#myform").submit(function(e) {
            if($("#answers_rows_counter").val() == 0){
                e.preventDefault();
                Command: toastr['warning']("Please Add Answers");
                return false;
            } else {
                return true;
            }
        });

        function remove_answers_rows(counter){
            $(".answers_list_row_"+counter).remove();
            $("#answers_rows_counter").val(counter-1);
        }

        function remove_all_answers(){
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields").html('');
            $("#answers_rows_counter").val(0);
            //}
        }

        function get_answers_list(qs_id, answers_rows_counter){
            $.ajax({
                url: "{{env('WEB_URL')}}admin/get_list_answers/"+qs_id, success: function(result){
                    $('#parent_qs_answers_id_' + answers_rows_counter).html(result);
                }
            });
        }

        function require_answer(field_name){
            var chkBox = document.getElementById('field_type_' + field_name);
            if (chkBox.checked){
                $('#answers_name_' + field_name).removeAttr('required');
            } else {
                $('#answers_name_' + field_name).attr('required', 'required');
            }
        }
    </script>
@endsection