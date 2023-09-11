@extends('layout.partners.list_master')

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
    .nav-link.active{
        background-color:#d7e4f5!important;
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
                                            <a class="nav-link active" data-toggle="tab" href="#text"><h4><strong>Text</strong></h4></a>
                                        </li>
                                    @endif    
                                    @if($single_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Single_Choice"><h4><strong>Single Choice</strong></h4></a>
                                        </li>
                                    @endif
                                    
                                    @if($multiple_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Multiple_Choice"><h4><strong>Multiple Choice</strong></h4></a>
                                        </li>
                                    @endif
                                    
                                    @if($multilevel_questions_allowed == 'Yes')
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Multilevel_Choice"><h4><strong>Multilevel Choice</strong></h4></a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="text" role="tabpanel">
                                        <div class="col-xl-6 d-none">
                                            <div class="card"> 
                                                <div class="card-body">
                                                    <div id="accordion-one" class="accordion accordion-primary">
                                                        <div class="accordion__item">
                                                            <div class="accordion__header rounded-lg" data-toggle="collapse" data-target="#default_collapseOne">
                                                                <span class="accordion__header--text">Accordion Header One</span>
                                                                <span class="accordion__header--indicator"></span>
                                                            </div>
                                                            <div id="default_collapseOne" class="collapse accordion__body show" data-parent="#accordion-one">
                                                                <div class="accordion__body--text">
                                                                    Anim pariatur cliche 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="basic-form">
                                            <form action="/partners/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_text" name="myform_text" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" value="Text" class="form-control" name="question_type" id="question_type" required="">
                                                <div id="text_question" data-text_qustion="0">
                                                    <div class="row col-md-12">            
                                                        <div class="form-group col-md-4 mt-2">
                                                            <h4><strong>Question</strong></h4>
                                                            <h4><strong><textarea style="border:1px solid" type="text"  name="name" class="form-control" placeholder="Enter Question" required></textarea></strong></h4>
                                                        </div>                                                        
                                                        <div class="col-md-2 mt-4">    
                                                            <h4><strong>Mandatory</strong></h4>
                                                            <h4><strong><input style="border:2px solid" type="checkbox" name="mandatory"></strong>
                                                            </h4>
                                                        </div>
                
                                                    </div>
{{--                 
                                                    <h5> 
                                                        <h4><strong>Answers Section </strong></h4>
                                                        <span class="pull-right">
                                                            <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_text">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        </span>
                                                    </h5>
                                                    <div id="answers_list_row_fields_text"></div> --}}
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_text" placeholder="Enter Answers Rows" required="">
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Single_Choice">
                                        <div class="basic-form">
                                            <form action="/partners/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_single_choice" name="myform_single_choice" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" value="Single Choice" class="form-control" name="question_type" id="question_type" required="">
                                                <div id="single_choice_question" data-text_qustion="0">
                                                <div class="row col-md-12 d-flex">            
                                                    <div class="form-group col-md-4 mt-2  ">
                                                        <h4><strong>Question</strong></h4>
                                                        <h4><strong><textarea style="border:1px solid" type="text"  name="name" class="form-control" placeholder="Enter Question" required></textarea></strong>
                                                    </h4>
                                                </div>
                                                        <div class="col-md-2 mt-4">    
                                                            <h4><strong>Mandatory</strong></h4>
                                                            <h4><strong><input style="border:2px solid" type="checkbox" name="mandatory"></strong>
                                                            </h4>
                                                        </div>
                                                    <div class="col-md-2 mt-4">    
                                                        <span class="pull-right mt-4">
                                                            <a href="javascript:;" class="pull-right btn btn-primary" id="answers_rows_counter_add_single_choice">
                                                                 Add Answer
                                                                <!--<i class="fa fa-plus"></i>-->
                                                            </a>
                                                        </span>
                                                    </div>
            
                                                </div>
            
                                                <!-- <h5> 
                                                    <h4><strong>Answers Section </strong></h4>
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_single_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5> -->
                                                <div id="answers_list_row_fields_single_choice"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_single_choice" placeholder="Enter Answers Rows" required="">
                                                
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Multiple_Choice">
                                        <div class="basic-form">
                                            <form action="/partners/survey_list_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_multiple_choice" name="myform_multiple_choice" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" value="Multiple Choice" class="form-control" name="question_type" id="question_type" required="">
                                                <div id="multiple_choice_question" data-text_qustion="0">
                                                <div class="row col-md-12 d-flex">            
                                                    <div class="form-group col-md-4 mt-2">
                                                        <h4><strong>Question</strong></h4>
                                                        <h4><strong><textarea style="border:1px solid" type="text"  name="name" class="form-control" placeholder="Enter Question" required></textarea></strong></h4>
                                                    </div>
                                                        <div class="col-md-2 mt-4">    
                                                            <h4><strong>Mandatory</strong></h4>
                                                            <h4><strong><input style="border:2px solid" type="checkbox" name="mandatory"></strong>
                                                            </h4>
                                                        </div>
            
                                                    <div class="col-md-2 mt-4">    
                                                        <span class="pull-right mt-4">
                                                            <a href="javascript:;" class="pull-right btn btn-primary" id="answers_rows_counter_add_multiple_choice">
                                                                Add Answer 
                                                                <!--<i class="fa fa-plus"></i>-->
                                                            </a>
                                                        </span>
                                                    </div>
            
                                                </div>
<!--             
                                                <h5> 
                                                    <h4><strong>Answers Section </strong></h4>
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_multiple_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5> -->
            
                                                <div id="answers_list_row_fields_multiple_choice"></div>
                                                    
                                                </div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_multiple_choice" placeholder="Enter Answers Rows" required="">
                                                <button type="submit" class="btn btn-primary px-5 float-right mt-4">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Multilevel_Choice">
                                        <div class="basic-form">
                                            <form action="/partners/multilevel_qs_add_data/{{$survey_list->survey_list_id}}" method="POST" id="myform_multilevel_choice" name="myform_multilevel_choice" enctype="multipart/form-data">
                                                @csrf
            
                                                <div id="multilevel_choice_question" class="multilevel_choice_question" data-text_qustion="0">
                                                <div class="row col-md-12 d-flex">            
                                                    <div class="form-group col-md-4 mt-2">
                                                        <h4><strong>Question</strong></h4>
                                                        <h4><strong><textarea style="border:1px solid" type="text" name="question" id="multilevel_p_question" class="form-control input" placeholder="Enter Question" required></textarea></strong></h4>
                                                    </div>
                                                        <div class="col-md-2 mt-4">    
                                                            <h4><strong>Mandatory</strong></h4>
                                                            <h4><strong><input style="border:2px solid" type="checkbox" name="mandatory"></strong>
                                                            </h4>
                                                        </div>
                                                    <div class="col-md-2 mt-4">    
                                                        <span class="pull-right mt-4">
                                                            <a href="javascript:;" class="pull-right btn btn-primary" id="answers_rows_counter_add_multilevel_choice">
                                                                 Add Answer
                                                                <!--<i class="fa fa-plus"></i>-->
                                                            </a>
                                                        </span>
                                                    </div>
            
                                                </div>
<!--             
                                                <h5>
                                                    <h4><strong>Answers Section </strong></h4>
                                                    <span class="pull-right">
                                                        <a href="javascript:;" class="pull-right btn btn-sm btn-danger" id="answers_rows_counter_add_multilevel_choice">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </h5> -->
            
                                                <div id="answers_list_row_fields_multilevel_choice"></div>
                                                
                                            </div>
                                            <div class="question_row"></div>
                                                <input type="hidden" value="0" class="form-control" name="question_counter_multilevel_choice" id="question_counter_multilevel_choice" required="">
                                                <div id="question_fields_multilevel_choice"></div>
                                                <input type="hidden" value="{{$survey_list->survey_list_id}}" class="form-control" name="survey_list_id" id="survey_list_id" required="">
                                                <input type="hidden" value="Multilevel Choice" class="form-control ml_choice" name="question_type" id="question_type" required="">

                                                <input type="hidden" value="0" class="form-control" name="answers_rows_counter" id="answers_rows_counter_multilevel_choice" placeholder="Enter Answers Rows" required="">
                                                <input type="hidden" class="form-control" name="survey_list_qs_id" id="survey_list_qs_id">
                                                
                                                <button type="button" class="btn btn-primary mb-3 addQuestionBtn_multilevel_choice" id="addQuestionBtn_multilevel_choice">
                                                    Add Question
                                                </button>

                                                <button type="submit" id="saveBtn" class="d-none btn btn-primary px-5 float-right mt-4">Save</button>
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
        $(document).ready(function(){
            
            
            $(document).on('click','.addQuestionBtn_multilevel_choice',function(e){
                    e.preventDefault();
                    const answersValues = [];
                    const answersInputs = $("input[name='multilevel_p_answers[]']");
                    answersInputs.each(function(index, element) {
                        answersValues.push($(element).val());   
                    });
                    var mandatoryValue = $('#mandatory').prop('checked') ? 1 : 0;
                    var settings = {
                        "headers": {
                            'X-CSRF-TOKEN': "{{csrf_token()}}",
                        },
                    "url": "/partners/multilevel_parent_qs_add",
                    "method": "POST",
                    "data": {
                        'survey_list_id':$('#survey_list_id').val(),
                        'question_type':$('.ml_choice').val(),
                        'question':$('#multilevel_p_question').val(),
                        'mandatory': mandatoryValue,
                        'answers':answersValues,
                    },
                };
                $.ajax(settings).done(function (response) {
                    console.log(response);
                    if (response.status == "success") {
                        $('#survey_list_qs_id').val(response.data.survey_list_qs_id);
                        $('#saveBtn').removeClass('d-none').addClass('d-block');
                        $('#addQuestionBtn_multilevel_choice').addClass('d-none');
                        $('.multilevel_choice_question').remove();
                        $( ".input" ).each(function() {
                            $(this).val("");
                        });
                        
                        question_row = '';            
                        question_row += '<div class="row col-md-12 mt-2">';
                        question_row += '<lable class="row col-md-12"><h3 class="m-2 p-1">'+response.data.name+'</h3></lable>';
                        question_row += '</div>';
                        
                        $(".question_row").html(question_row);

                        addQuestionMultiLevelChoice();
                        // toastr.success('done');

                    } else {
                        toastr.warning(response.message);
                    }
                });
            });

            // $(document).on('click', '.add_Question_Btn_multilevel_choice', function(e) {
            //     addQuestionMultiLevelChoice();
            // });
        }); 
    
        $("#answers_rows_counter_add_text").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_text = eval(parseInt($("#answers_rows_counter_text").val()) + 1);             
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_text +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:2px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

                    answers_list_row += '<div class="form-group col-md-1" style="padding: 30px;">';
                        answers_list_row += '<a href="javascript:;" class="btn btn-danger" onclick="remove_answers_rows('+ answers_rows_counter_text +')"><i class="fa fa-minus"></i></a>';
                    answers_list_row += '</div>';
                    answers_list_row += '<hr class="col-md-12" />';
                answers_list_row += '</div>';
            
            if(answers_rows_counter_text == 1){
                $("#answers_list_row_fields_text").html(answers_list_row);
                $("#answers_rows_counter_text").val(answers_rows_counter_text);
            } else {
                $("#answers_list_row_fields_text").append(answers_list_row);
                $("#answers_rows_counter_text").val(answers_rows_counter_text);
            }
        });
        $("#answers_rows_counter_add_single_choice").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_single_choice = eval(parseInt($("#answers_rows_counter_single_choice").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_single_choice +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:2px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
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
        $("#answers_rows_counter_add_multiple_choice").click(function(e) {
            answers_list_row = '';
                answers_rows_counter_multiple_choice = eval(parseInt($("#answers_rows_counter_multiple_choice").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_multiple_choice +'">';
                    answers_list_row += '<div class="form-group col-md-8">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:2px solid" type="text" name="answers_name[]" class="form-control" placeholder="Enter Answer Text" required>';
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

        $("#answers_rows_counter_add_multilevel_choice").click(function(e) {
            answers_list_row = '';
            answers_rows_counter_multilevel_choice = eval(parseInt($("#answers_rows_counter_multilevel_choice").val()) + 1);
                
                answers_list_row += '<div class="row col-md-12 answers_list_row_'+ answers_rows_counter_multilevel_choice +'">';
                    answers_list_row += '<div class="form-group col-md-3">';
                        answers_list_row += '<b>Answer Text</b>';
                        answers_list_row += '<input style="border:2px solid" type="text" name="multilevel_p_answers[]" class="form-control input" placeholder="Enter Answer Text" required>';
                    answers_list_row += '</div>';

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
        
        
        function addAnswersRowsMultiLevelChoice(quesCounter) {
            console.log(quesCounter);
            if ($("#answers_rows_counter_multilevel_choice_" + quesCounter).length === 0) {
                $('<a class="d-none" id="answers_rows_counter_multilevel_choice_' + quesCounter + '" value="' + quesCounter-1 + '"></a>').appendTo("body");
            }

            var answersRowsCounterMultiLevelChoice = parseInt($("#answers_rows_counter_multilevel_choice_" + quesCounter).val()) + 1;
            if (isNaN(answersRowsCounterMultiLevelChoice)) {
                answersRowsCounterMultiLevelChoice = 1;
            }
            let answersListRow = '';
            
            answersListRow += '<div class="d-none" id="answers_rows_counter_multilevel_choice_' + answersRowsCounterMultiLevelChoice + '"></div>';
            answersListRow += '<div class="row col-md-12 answers_list_row_' + quesCounter + '_' + answersRowsCounterMultiLevelChoice + '">';
            answersListRow += '<div class="form-group col-md-4">';
            answersListRow += '<b>Answer Text</b>';
            answersListRow += '<input style="border: 1px solid" type="text" name="answers_name['+quesCounter+'][]" id="answers_name_' + answersRowsCounterMultiLevelChoice + '" class="form-control" placeholder="Enter Answer Text">';
            answersListRow += '</div>';


            answersListRow += '<div class="form-group col-md-1" style="padding: 30px;">';
            answersListRow += '<a href="javascript:;" class="btn btn-danger" onclick="removeAnswersRows(' + quesCounter + ', ' + answersRowsCounterMultiLevelChoice + ')"><i class="fa fa-minus"></i></a>';
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


        async function addQuestionMultiLevelChoice() {
            const questionCounterMultiLevelChoice = eval(parseInt($("#question_counter_multilevel_choice").val()) + 1);

            var survey_list_qs_id=$('#survey_list_qs_id').val();
             await get_answers_list(survey_list_qs_id, questionCounterMultiLevelChoice );


            var questionMultiLevelChoiceRow = '';
            questionMultiLevelChoiceRow += '<div id="text_question" data-text_qustion="' + questionCounterMultiLevelChoice + '">';
                
                questionMultiLevelChoiceRow += '<input type="hidden" name="parent_qs_id['+questionCounterMultiLevelChoice+'][]" value="'+survey_list_qs_id+'">';
                questionMultiLevelChoiceRow += '<div class="row col-md-12">';
                    questionMultiLevelChoiceRow += '<div class="form-group col-md-12" id="parent_qs_answers_id_' + questionCounterMultiLevelChoice + '">';
            
            questionMultiLevelChoiceRow += '</div>';
            
            questionMultiLevelChoiceRow += '</div></div>';

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
            const questionCounterMultiLevelChoice = eval(parseInt($("#question_counter_multilevel_choice").val()));

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
            $("#answers_rows_counter_text").val(counter-1);
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
            $("#answers_list_row_fields_text").html('');
            $("#answers_rows_counter_text").val(0);
            //}
        }

        function get_answers_list(qs_id, answers_rows_counter){
            $.ajax({
                url: "/partners/get_list_answers/"+qs_id,data:{'answers_rows_counter':answers_rows_counter}, success: function(result){
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