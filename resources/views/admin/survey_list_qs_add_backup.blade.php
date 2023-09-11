@extends('layout.admin.list_master')

@section('style')
<style>
    .branch-card {
        height: 150px;
        border: 2px dashed #ddd;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 12px;
    }

    .branch-card-active::after {
        content: "\e9da";
        font-family: boxicons !important;
        font-weight: 400;
        font-style: normal;
        font-variant: normal;
        line-height: 1;
        text-transform: none;
        -webkit-font-smoothing: antialiased;
        position: absolute;
        right: -14px;
        top: -9px;
        height: 28px;
        width: 28px;
        font-size: 20px;
        background: #556ee6;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        border-radius: 50%;
    }

    .branch-card-active {
        border: 2px solid #556ee6;
        position: relative;
    }

    [data-repeater-item]:first-child .repeater-delete {
        display: none;
    }
</style>
@endsection
@section('content')

    <!-- Add the styles and scripts you provided here -->

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3> {{$survey_list->name}} </h3>
     
                        </div>
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <div class="default-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <?php 
                                        $text_questions_allowed = DB::table('system_settings')->where('type', 'text_questions_allowed')->first()->description;
                                        $single_questions_allowed = DB::table('system_settings')->where('type', 'single_questions_allowed')->first()->description;
                                        $multiple_questions_allowed = DB::table('system_settings')->where('type', 'multiple_questions_allowed')->first()->description;
                                        $multilevel_questions_allowed = DB::table('system_settings')->where('type', 'multilevel_questions_allowed')->first()->description;
                                    ?>

                                    @if($text_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#taxt">Taxt</a>
                                        </li>
                                    @endif    
                                    @if($single_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Single_Choice">Single Choice</a>
                                        </li>
                                    @endif
                                    
                                    @if($multiple_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Multiple_Choice">Multiple Choice</a>
                                        </li>
                                    @endif
                                    
                                    @if($multilevel_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Multilevel_Choice">Multilevel Choice</a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="taxt" role="tabpanel">
                                        <div class="basic-form">
                                            <form action="/admin/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_taxt" name="myform_taxt" enctype="multipart/form-data">
                                                @csrf
                                                <div id="taxt_question" data-taxt_qustion="0">
                                                    <div class="row col-md-12">            
                                                        <div class="form-group col-md-4">
                                                            <b>Question</b>
                                                            <b><input style="border:1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required></b>
                                                        </div>
                
                                                    </div>
                
                                                    <hr class="col-md-12" />
                                                    <h5> 
                                                        Answers Section 
                                                        <span class="pull-right">
                                                            <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_taxt">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        </span>
                                                    </h5>

                
                                                    <hr class="col-md-12" />
                                                    <div id="answers_list_row_fields_taxt"></div>
                                                    
                                                    <input type="hidden" value="0" class="form-control" name="question_counter_taxt" id="question_counter_taxt" required="">
                                                    <div id="question_fields_taxt"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_taxt" placeholder="Enter Answers Rows" required="">
                                                <button type="button" class="btn btn-primary mb-3" id="addQuestionBtn_taxt">
                                                    Add Question
                                                </button>
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Single_Choice">
                                        <div class="basic-form">
                                            <form action="/admin/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_single_choice" name="myform_single_choice" enctype="multipart/form-data">
                                                @csrf
            
                                                <div id="single_choice_question" data-taxt_qustion="0">
                                                <div class="row col-md-12">            
                                                    <div class="form-group col-md-4">
                                                        <b>Question</b>
                                                        <b><input style="border:1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required></b>
                                                    </div>
            
                                                </div>
            
                                                <hr class="col-md-12" />
                                                <h5> 
                                                    Answers Section 
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_single_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5>
            
                                                <hr class="col-md-12" />
                                                <div id="answers_list_row_fields_single_choice"></div>
                                                <input type="hidden" value="0" class="form-control" name="question_counter_single_choice" id="question_counter_single_choice" required="">
                                                    <div id="question_fields_single_choice"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_single_choice" placeholder="Enter Answers Rows" required="">
                                                
                                                <button type="button" class="btn btn-primary mb-3" id="addQuestionBtn_single_choice">
                                                    Add Question
                                                </button>
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Multiple_Choice">
                                        <div class="basic-form">
                                            <form action="/admin/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_multiple_choice" name="myform_multiple_choice" enctype="multipart/form-data">
                                                @csrf
            
                                                <div id="multiple_choice_question" data-taxt_qustion="0">
                                                <div class="row col-md-12">            
                                                    <div class="form-group col-md-4">
                                                        <b>Question</b>
                                                        <b><input style="border:1px solid" type="text" name="name" class="form-control" placeholder="Enter Question" required></b>
                                                    </div>
            
                                                </div>
            
                                                <hr class="col-md-12" />
                                                <h5> 
                                                    Answers Section 
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_multiple_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5>
            
                                                <hr class="col-md-12" />
                                                <div id="answers_list_row_fields_multiple_choice"></div>
                                                <input type="hidden" value="0" class="form-control" name="question_counter_multiple_choice" id="question_counter_multiple_choice" required="">
                                                    <div id="question_fields_multiple_choice"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_multiple_choice" placeholder="Enter Answers Rows" required="">
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Multilevel_Choice">
                                        <div class="basic-form">
                                            <form action="/admin/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_multilevel_choice" name="myform_multilevel_choice" enctype="multipart/form-data">
                                                @csrf
            
                                                <div id="multilevel_choice_question" data-taxt_qustion="0">
                                                <div class="row col-md-12">            
                                                    <div class="form-group col-md-4">
                                                        <b>Question</b>
                                                        <b><input style="border:1px solid" type="text" name="name" class="form-control" placeholder="Enter Question" required></b>
                                                    </div>
            
                                                </div>
            
                                                <hr class="col-md-12" />
                                                <h5> 
                                                    Answers Section 
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_multilevel_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5>
            
                                                <hr class="col-md-12" />
                                                <div id="answers_list_row_fields_multilevel_choice"></div>
                                                <input type="hidden" value="0" class="form-control" name="question_counter_multilevel_choice" id="question_counter_multilevel_choice" required="">
                                                    <div id="question_fields_multilevel_choice"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_multilevel_choice" placeholder="Enter Answers Rows" required="">
                                                <button type="button" class="btn btn-primary mb-3" id="addQuestionBtn_multilevel_choice">
                                                    Add Question
                                                </button>
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <!--**********************************
           Chat box End
    ***********************************-->

    
@endsection
@section('script')
    
    <script>
        $("#answers_rows_counter_add_taxt").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_taxt = eval(parseInt($("#answers_rows_counter_taxt").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_taxt +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[0][]" class="form-control" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter_taxt +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            
            if(answers_rows_counter_taxt == 1){
                $("#answers_list_row_fields_taxt").html(answers_list_row);
                $("#answers_rows_counter_taxt").val(answers_rows_counter_taxt);
            } else {
                $("#answers_list_row_fields_taxt").append(answers_list_row);
                $("#answers_rows_counter_taxt").val(answers_rows_counter_taxt);
            }
        });
        $("#addQuestionBtn_taxt").click(function(e) {
            addQuestionTaxt();
        });
        
        function addAnswersRowsTaxt(quesCounter) {
            const answersRowsCounterTaxt = parseInt($("#answers_rows_counter_taxt_" + quesCounter).val()) + 1;
            let answersListRow = '';

            answersListRow += '<div class="row col-md-12 answers_list_row_' + quesCounter + '_' + answersRowsCounterTaxt + '">';
            answersListRow += '<div class="form-group col-md-8">';
            answersListRow += '<b>Answer Text</b>';
            answersListRow += '<input style="border: 1px solid" type="text" name="answers_name[' + quesCounter + '][]" class="form-control" placeholder="Enter Answer Text" required>';
            answersListRow += '</div>';
            answersListRow += '<div class="form-group col-md-1" style="padding: 30px;">';
            answersListRow += '<a href="javascript:;" class="btn btn-danger" onclick="removeAnswersRows(' + quesCounter + ', ' + answersRowsCounterTaxt + ')"><i class="fa fa-minus"></i></a>';
            answersListRow += '</div>';
            answersListRow += '<hr class="col-md-12" />';
            answersListRow += '</div>';

            if (answersRowsCounterTaxt === 1) {
                $("#answers_list_row_fields_taxt_" + quesCounter).html(answersListRow);
                $("#answers_rows_counter_taxt_" + quesCounter).val(answersRowsCounterTaxt);
            } else {
                $("#answers_list_row_fields_taxt_" + quesCounter).append(answersListRow);
                $("#answers_rows_counter_taxt_" + quesCounter).val(answersRowsCounterTaxt);
            }
        }

        function addQuestionTaxt() {
            const questionCounterTaxt = parseInt($("#question_counter_taxt").val()) + 1;
            let questionTaxtRow = '';

            questionTaxtRow += '<div id="taxt_question" data-taxt_qustion="' + questionCounterTaxt + '">';
            questionTaxtRow += '<div class="row col-md-12">';
            questionTaxtRow += '<div class="form-group col-md-4">';
            questionTaxtRow += '<b>Question</b>';
            questionTaxtRow += '<input style="border: 1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required>';
            questionTaxtRow += '</div>';
            questionTaxtRow += '</div>';
            questionTaxtRow += '<hr class="col-md-12" />';
            questionTaxtRow += '<h5> Answers Section <span class="pull-right"><a href="javascript:;" class="pull-right btn btn-sm btn-danger" onclick="addAnswersRowsTaxt(' + questionCounterTaxt + ')"><i class="fa fa-plus"></i> </a></span></h5>';
            questionTaxtRow += '<hr class="col-md-12" />';
            questionTaxtRow += '<div id="answers_list_row_fields_taxt_' + questionCounterTaxt + '"></div>';
            questionTaxtRow += '</div>';

            if (questionCounterTaxt === 1) {
                $("#question_fields_taxt").html(questionTaxtRow);
                $("#question_counter_taxt").val(questionCounterTaxt);
            } else {
                $("#question_fields_taxt").append(questionTaxtRow);
                $("#question_counter_taxt").val(questionCounterTaxt);
            }
        }

        function removeAnswersRows(quesCounter, counter) {
            $(".answers_list_row_" + quesCounter + "_" + counter).remove();
            $("#answers_rows_counter_taxt_" + quesCounter).val(counter - 1);
        }

        function removeAllAnswers() {
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields_taxt").html('');
            $("#answers_rows_counter_taxt").val(0);
            //}
        }

        $("#myform_taxt").submit(function (e) {
            // Validate if each question has at least one answer
            const questionCounterTaxt = parseInt($("#question_counter_taxt").val());
            for (let i = 1; i <= questionCounterTaxt; i++) {
                if ($("#answers_rows_counter_taxt_" + i).val() === "0") {
                    e.preventDefault();
                    toastr.warning("Please Add Answers for Question " + i);
                    return false;
                }
            }
            return true;
        });
        $("#answers_rows_counter_add_single_choice").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_single_choice = eval(parseInt($("#answers_rows_counter_single_choice").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_single_choice +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter_single_choice +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            
            if(answers_rows_counter_single_choice == 1){
                $("#answers_list_row_fields_single_choice").html(answers_list_row);
                $("#answers_rows_counter_single_choice").val(answers_rows_counter_single_choice);
            } else {
                $("#answers_list_row_fields_single_choice").append(answers_list_row);
                $("#answers_rows_counter_single_choice").val(answers_rows_counter_single_choice);
            }
        });
        
        $("#addQuestionBtn_single_choice").click(function(e) {
            addQuestionSingleChoice();
        });
        
        function addAnswersRowsSingleChoice(quesCounter) {
            const answersRowsCounterSingleChoice = parseInt($("#answers_rows_counter_single_choice_" + quesCounter).val()) + 1;
            let answersListRow = '';

            answersListRow += '<div class="row col-md-12 answers_list_row_' + quesCounter + '_' + answersRowsCounterSingleChoice + '">';
            answersListRow += '<div class="form-group col-md-8">';
            answersListRow += '<b>Answer Text</b>';
            answersListRow += '<input style="border: 1px solid" type="text" name="answers_name[' + quesCounter + '][]" class="form-control" placeholder="Enter Answer Text" required>';
            answersListRow += '</div>';
            answersListRow += '<div class="form-group col-md-1" style="padding: 30px;">';
            answersListRow += '<a href="javascript:;" class="btn btn-danger" onclick="removeAnswersRows(' + quesCounter + ', ' + answersRowsCounterSingleChoice + ')"><i class="fa fa-minus"></i></a>';
            answersListRow += '</div>';
            answersListRow += '<hr class="col-md-12" />';
            answersListRow += '</div>';

            if (answersRowsCounterSingleChoice === 1) {
                $("#answers_list_row_fields_single_choice_" + quesCounter).html(answersListRow);
                $("#answers_rows_counter_single_choice_" + quesCounter).val(answersRowsCounterSingleChoice);
            } else {
                $("#answers_list_row_fields_single_choice_" + quesCounter).append(answersListRow);
                $("#answers_rows_counter_single_choice_" + quesCounter).val(answersRowsCounterSingleChoice);
            }
        }

        function addQuestionSingleChoice() {
            const questionCounterSingleChoice = parseInt($("#question_counter_single_choice").val()) + 1;
            let questionSingleChoiceRow = '';

            questionSingleChoiceRow += '<div id="taxt_question" data-taxt_qustion="' + questionCounterSingleChoice + '">';
            questionSingleChoiceRow += '<div class="row col-md-12">';
            questionSingleChoiceRow += '<div class="form-group col-md-4">';
            questionSingleChoiceRow += '<b>Question</b>';
            questionSingleChoiceRow += '<input style="border: 1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required>';
            questionSingleChoiceRow += '</div>';
            questionSingleChoiceRow += '</div>';
            questionSingleChoiceRow += '<hr class="col-md-12" />';
            questionSingleChoiceRow += '<h5> Answers Section <span class="pull-right"><a href="javascript:;" class="pull-right btn btn-sm btn-danger" onclick="addAnswersRowsSingleChoice(' + questionCounterSingleChoice + ')"><i class="fa fa-plus"></i> </a></span></h5>';
            questionSingleChoiceRow += '<hr class="col-md-12" />';
            questionSingleChoiceRow += '<div id="answers_list_row_fields_single_choice_' + questionCounterSingleChoice + '"></div>';
            questionSingleChoiceRow += '</div>';

            if (questionCounterSingleChoice === 1) {
                $("#question_fields_single_choice").html(questionSingleChoiceRow);
                $("#question_counter_single_choice").val(questionCounterSingleChoice);
            } else {
                $("#question_fields_single_choice").append(questionSingleChoiceRow);
                $("#question_counter_single_choice").val(questionCounterSingleChoice);
            }
        }

        function removeAnswersRows(quesCounter, counter) {
            $(".answers_list_row_" + quesCounter + "_" + counter).remove();
            $("#answers_rows_counter_single_choice_" + quesCounter).val(counter - 1);
        }

        function removeAllAnswers() {
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields_single_choice").html('');
            $("#answers_rows_counter_single_choice").val(0);
            //}
        }

        $("#myform_single_choice").submit(function (e) {
            // Validate if each question has at least one answer
            const questionCounterSingleChoice = parseInt($("#question_counter_single_choice").val());
            for (let i = 1; i <= questionCounterSingleChoice; i++) {
                if ($("#answers_rows_counter_single_choice_" + i).val() === "0") {
                    e.preventDefault();
                    toastr.warning("Please Add Answers for Question " + i);
                    return false;
                }
            }
            return true;
        });

        $("#answers_rows_counter_add_multiple_choice").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_multiple_choice = eval(parseInt($("#answers_rows_counter_multiple_choice").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_multiple_choice +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter_multiple_choice +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            
            if(answers_rows_counter_multiple_choice == 1){
                $("#answers_list_row_fields_multiple_choice").html(answers_list_row);
                $("#answers_rows_counter_multiple_choice").val(answers_rows_counter_multiple_choice);
            } else {
                $("#answers_list_row_fields_multiple_choice").append(answers_list_row);
                $("#answers_rows_counter_multiple_choice").val(answers_rows_counter_multiple_choice);
            }
        });
        $("#addQuestionBtn_multiple_choice").click(function(e) {
            addQuestionMultipleChoice();
        });
        
        function addAnswersRowsMultipleChoice(quesCounter) {
            const answersRowsCounterMultipleChoice = parseInt($("#answers_rows_counter_multiple_choice_" + quesCounter).val()) + 1;
            let answersListRow = '';

            answersListRow += '<div class="row col-md-12 answers_list_row_' + quesCounter + '_' + answersRowsCounterMultipleChoice + '">';
            answersListRow += '<div class="form-group col-md-8">';
            answersListRow += '<b>Answer Text</b>';
            answersListRow += '<input style="border: 1px solid" type="text" name="answers_name[' + quesCounter + '][]" class="form-control" placeholder="Enter Answer Text" required>';
            answersListRow += '</div>';
            answersListRow += '<div class="form-group col-md-1" style="padding: 30px;">';
            answersListRow += '<a href="javascript:;" class="btn btn-danger" onclick="removeAnswersRows(' + quesCounter + ', ' + answersRowsCounterMultipleChoice + ')"><i class="fa fa-minus"></i></a>';
            answersListRow += '</div>';
            answersListRow += '<hr class="col-md-12" />';
            answersListRow += '</div>';

            if (answersRowsCounterMultipleChoice === 1) {
                $("#answers_list_row_fields_multiple_choice_" + quesCounter).html(answersListRow);
                $("#answers_rows_counter_multiple_choice_" + quesCounter).val(answersRowsCounterMultipleChoice);
            } else {
                $("#answers_list_row_fields_multiple_choice_" + quesCounter).append(answersListRow);
                $("#answers_rows_counter_multiple_choice_" + quesCounter).val(answersRowsCounterMultipleChoice);
            }
        }

        function addQuestionMultipleChoice() {
            const questionCounterMultipleChoice = parseInt($("#question_counter_multiple_choice").val()) + 1;
            let questionMultipleChoiceRow = '';

            questionMultipleChoiceRow += '<div id="taxt_question" data-taxt_qustion="' + questionCounterMultipleChoice + '">';
            questionMultipleChoiceRow += '<div class="row col-md-12">';
            questionMultipleChoiceRow += '<div class="form-group col-md-4">';
            questionMultipleChoiceRow += '<b>Question</b>';
            questionMultipleChoiceRow += '<input style="border: 1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required>';
            questionMultipleChoiceRow += '</div>';
            questionMultipleChoiceRow += '</div>';
            questionMultipleChoiceRow += '<hr class="col-md-12" />';
            questionMultipleChoiceRow += '<h5> Answers Section <span class="pull-right"><a href="javascript:;" class="pull-right btn btn-sm btn-danger" onclick="addAnswersRowsMultipleChoice(' + questionCounterMultipleChoice + ')"><i class="fa fa-plus"></i> </a></span></h5>';
            questionMultipleChoiceRow += '<hr class="col-md-12" />';
            questionMultipleChoiceRow += '<div id="answers_list_row_fields_multiple_choice_' + questionCounterMultipleChoice + '"></div>';
            questionMultipleChoiceRow += '</div>';

            if (questionCounterMultipleChoice === 1) {
                $("#question_fields_multiple_choice").html(questionMultipleChoiceRow);
                $("#question_counter_multiple_choice").val(questionCounterMultipleChoice);
            } else {
                $("#question_fields_multiple_choice").append(questionMultipleChoiceRow);
                $("#question_counter_multiple_choice").val(questionCounterMultipleChoice);
            }
        }

        function removeAnswersRows(quesCounter, counter) {
            $(".answers_list_row_" + quesCounter + "_" + counter).remove();
            $("#answers_rows_counter_multiple_choice_" + quesCounter).val(counter - 1);
        }

        function removeAllAnswers() {
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields_multiple_choice").html('');
            $("#answers_rows_counter_multiple_choice").val(0);
            //}
        }

        $("#myform_multiple_choice").submit(function (e) {
            // Validate if each question has at least one answer
            const questionCounterMultipleChoice = parseInt($("#question_counter_multiple_choice").val());
            for (let i = 1; i <= questionCounterMultipleChoice; i++) {
                if ($("#answers_rows_counter_multiple_choice_" + i).val() === "0") {
                    e.preventDefault();
                    toastr.warning("Please Add Answers for Question " + i);
                    return false;
                }
            }
            return true;
        });

        $("#answers_rows_counter_add_multilevel_choice").click(function(e) {
            answers_list_row = '';
            answers_rows_counter_multilevel_choice = eval(parseInt($("#answers_rows_counter_multilevel_choice").val()) + 1);
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_multilevel_choice +'">';
                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<b>Choose Question</b>';
                        answers_list_row += '<select style="border:1px solid" name="parent_qs_id[]" id="parent_qs_id_'+ answers_rows_counter_multilevel_choice +'" onchange="get_answers_list(this.value, '+ answers_rows_counter_multilevel_choice +');"class="form-control" required>';
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
                        answers_list_row += '<select style="border:1px solid" name="parent_qs_answers_id[]" id="parent_qs_answers_id_'+ answers_rows_counter_multilevel_choice +'" class="form-control" required>';
                            answers_list_row += '<option value="">Please choose questions</option>';
                        answers_list_row += '</select>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<input type="checkbox" name="field_type[]" id="field_type_'+ answers_rows_counter_multilevel_choice +'" value="Yes" checked onchange="require_answer('+ answers_rows_counter_multilevel_choice +')"> Open Text Field <br>';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:1px solid" type="text" name="answers_name[]" id="answers_name_'+ answers_rows_counter_multilevel_choice +'" class="form-control" placeholder="Enter Answer Text">';
                    answers_list_row += '</div>';

                    answers_list_row += '<input type="hidden" class="form-control" name="survey_list_qs_answers_id[]" id="survey_list_qs_answers_id" value="0" required="">';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter_multilevel_choice +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            
            if(answers_rows_counter_multilevel_choice == 1){
                $("#answers_list_row_fields_multilevel_choice").html(answers_list_row);
                $("#answers_rows_counter_multilevel_choice").val(answers_rows_counter_multilevel_choice);
            } else {
                $("#answers_list_row_fields_multilevel_choice").append(answers_list_row);
                $("#answers_rows_counter_multilevel_choice").val(answers_rows_counter_multilevel_choice);
            }
        });

        $("#addQuestionBtn_multilevel_choice").click(function(e) {
            addQuestionMultiLevelChoice();
        });
        
        function addAnswersRowsMultiLevelChoice(quesCounter) {
    const answersRowsCounterMultiLevelChoice = parseInt($("#answers_rows_counter_multilevel_choice_" + quesCounter).val()) + 1;
    let answersListRow = '';

    answersListRow += '<div class="row col-md-12 answers_list_row_' + quesCounter + '_' + answersRowsCounterMultiLevelChoice + '">';
    answersListRow += '<div class="form-group col-md-3">';
    answersListRow += '<b>Choose Question</b>';
    answersListRow += '<select style="border: 1px solid" name="parent_qs_id[]" id="parent_qs_id_' + answersRowsCounterMultiLevelChoice + '" onchange="get_answers_list(this.value, ' + answersRowsCounterMultiLevelChoice + ');" class="form-control" required>';
    answersListRow += '<option value="">Please select question</option>';
    <?php 
        $survey_list_qs = DB::table('survey_list_qs')->where('survey_list_id', $survey_list->survey_list_id)->get(); 
        foreach ($survey_list_qs as $data) {
    ?>
        answersListRow += '<option value="{{$data->survey_list_qs_id}}">{{$data->name}}</option>';
    <?php } ?>
    answersListRow += '</select>';
    answersListRow += '</div>';

    answersListRow += '<div class="form-group col-md-3">';
    answersListRow += '<b>Choose Answer</b>';
    answersListRow += '<select style="border: 1px solid" name="parent_qs_answers_id[]" id="parent_qs_answers_id_' + answersRowsCounterMultiLevelChoice + '" class="form-control" required>';
    answersListRow += '<option value="">Please choose questions</option>';
    answersListRow += '</select>';
    answersListRow += '</div>';

    answersListRow += '<div class="form-group col-md-3">';
    answersListRow += '<input type="checkbox" name="field_type[]" id="field_type_' + answersRowsCounterMultiLevelChoice + '" value="Yes" checked onchange="require_answer(' + answersRowsCounterMultiLevelChoice + ')"> Open Text Field <br>';
    answersListRow += '<b>Answer Text</b>';
    answersListRow += '<input style="border: 1px solid" type="text" name="answers_name[]" id="answers_name_' + answersRowsCounterMultiLevelChoice + '" class="form-control" placeholder="Enter Answer Text">';
    answersListRow += '</div>';

    answersListRow += '<input type="hidden" class="form-control" name="survey_list_qs_answers_id[]" id="survey_list_qs_answers_id" value="0" required="">';

    answersListRow += '<div class="form-group col-md-1" style="padding: 30px;">';
    answersListRow += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows(' + answersRowsCounterMultiLevelChoice + ')"><i class="fa fa-minus"></i></a>';
    answersListRow += '</div>';
    answersListRow += '<hr class="col-md-12" />';
    answersListRow += '</div>';

    if (answersRowsCounterMultiLevelChoice === 1) {
        $("#answers_list_row_fields_multilevel_choice_" + quesCounter).html(answersListRow);
        $("#answers_rows_counter_multilevel_choice_" + quesCounter).val(answersRowsCounterMultiLevelChoice);
    } else {
        $("#answers_list_row_fields_multilevel_choice_" + quesCounter).append(answersListRow);
        $("#answers_rows_counter_multilevel_choice_" + quesCounter).val(answersRowsCounterMultiLevelChoice);
    }
}


        function addQuestionMultiLevelChoice() {
            const questionCounterMultiLevelChoice = parseInt($("#question_counter_multilevel_choice").val()) + 1;
            let questionMultiLevelChoiceRow = '';

            questionMultiLevelChoiceRow += '<div id="taxt_question" data-taxt_qustion="' + questionCounterMultiLevelChoice + '">';
            questionMultiLevelChoiceRow += '<div class="row col-md-12">';
            questionMultiLevelChoiceRow += '<div class="form-group col-md-4">';
            questionMultiLevelChoiceRow += '<b>Question</b>';
            questionMultiLevelChoiceRow += '<input style="border: 1px solid" type="text" name="question[]" class="form-control" placeholder="Enter Question" required>';
            questionMultiLevelChoiceRow += '</div>';
            questionMultiLevelChoiceRow += '</div>';
            questionMultiLevelChoiceRow += '<hr class="col-md-12" />';
            questionMultiLevelChoiceRow += '<h5> Answers Section <span class="pull-right"><a href="javascript:;" class="pull-right btn btn-sm btn-danger" onclick="addAnswersRowsMultiLevelChoice(' + questionCounterMultiLevelChoice + ')"><i class="fa fa-plus"></i> </a></span></h5>';
            questionMultiLevelChoiceRow += '<hr class="col-md-12" />';
            questionMultiLevelChoiceRow += '<div id="answers_list_row_fields_multilevel_choice_' + questionCounterMultiLevelChoice + '"></div>';
            questionMultiLevelChoiceRow += '</div>';

            if (questionCounterMultiLevelChoice === 1) {
                $("#question_fields_multilevel_choice").html(questionMultiLevelChoiceRow);
                $("#question_counter_multilevel_choice").val(questionCounterMultiLevelChoice);
            } else {
                $("#question_fields_multilevel_choice").append(questionMultiLevelChoiceRow);
                $("#question_counter_multilevel_choice").val(questionCounterMultiLevelChoice);
            }
        }

        function removeAnswersRows(quesCounter, counter) {
            $(".answers_list_row_" + quesCounter + "_" + counter).remove();
            $("#answers_rows_counter_multilevel_choice_" + quesCounter).val(counter - 1);
        }

        function removeAllAnswers() {
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields_multilevel_choice").html('');
            $("#answers_rows_counter_multilevel_choice").val(0);
            //}
        }

        $("#myform_multilevel_choice").submit(function (e) {
            // Validate if each question has at least one answer
            const questionCounterMultiLevelChoice = parseInt($("#question_counter_multilevel_choice").val());
            for (let i = 1; i <= questionCounterMultiLevelChoice; i++) {
                if ($("#answers_rows_counter_multilevel_choice_" + i).val() === "0") {
                    e.preventDefault();
                    toastr.warning("Please Add Answers for Question " + i);
                    return false;
                }
            }
            return true;
        });
        

        function remove_answers_rows(counter){
            $(".answers_list_row_"+counter).remove();
            $("#answers_rows_counter_taxt").val(counter-1);
        }

        function remove_answers_rows(counter){
            $(".answers_list_row_"+counter).remove();
            $("#answers_rows_counter_single_choice").val(counter-1);
        }

        function remove_answers_rows(counter){
            $(".answers_list_row_"+counter).remove();
            $("#answers_rows_counter_multilevel_choice").val(counter-1);
        }

        function remove_answers_rows(counter){
            $(".answers_list_row_"+counter).remove();
            $("#answers_rows_counter_multilevel_choice").val(counter-1);
        }

        function remove_all_answers(){
            //if (confirm("Are you sure you want to change question type?") == true) {
            alert("Are you sure you want to change question type?");
            $("#answers_list_row_fields_taxt").html('');
            $("#answers_rows_counter_taxt").val(0);
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