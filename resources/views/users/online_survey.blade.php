@extends('layout.users.list_master')

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
                        <span class="ml-2">Fill Out Survey</span>
                        @endsection
                    </div>
                </div>
                @php
                    $userId = session('users_id');
                @endphp
                <input type="hidden" id="userId" value="{{ $userId }}">
                <input type="hidden" id="s_id" value="{{ $s_id }}">

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12" id="completeSurveyquestion">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <div class="col-lg-12" id="survey_data">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- row -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-sm-flex d-block pb-0 border-0">
                                <div class="d-flex align-items-center">
                                    <span class="p-3 mr-3 rounded bg-warning">
                                        <i class="fa fa-usd" aria-hidden="true" style="color: white;"></i>
                                    </span>
                                    <div class="mr-auto pr-3" >
                                        <h4 class="text-black fs-20">Survey Answers</h4>
                                        <p class="fs-13 mb-0 text-black">Take a look at your survey answers.</p> 
                                    </div>
                                </div>                                
                            </div>
                            </div>
                            <div class="card-body pb-0">
                                <div id="chartBar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                    <!-- row -->
                    <div class="col-lg-12 " id="completeSurvey">
                        <div class="card">
                            <div class="card-header d-block pb-0 border-0">
                                <div class="my-3" id="question">
                                    
                                </div>
                                <div  class="d-flex">
                                    {{-- <div class="my-3"><button id="previousBtn" class="btn btn-primary">Previous</button></div>                           --}}
                                    <div class="my-3"><button id="nextBtn" class="btn btn-primary">Next</button></div>                      
                                </div>     
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
                <!-- row -->                    
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <link href="{{url('public')}}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css"/>
    <script src="{{url('public')}}/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>
    <script src="{{url('public')}}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <script src="{{url('public')}}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            var currentQuestionIndex = 0;
            var questionsArray = [];
            var isFormSubmitted = false;
            var questionAnswers = [];
            var ans_data;

            fetch();

            function fetch() {
                var id = $('#s_id').val();
                var settings = {
                    "url": "/users/online_survey_data",
                    "method": "GET",
                    "data": {
                        "s_id": id
                    },
                };

                $.ajax(settings).done(function(response) {
                    // console.log(response);
                    $('#survey_data').html("");

                    if (response.status == "success") {
                        if (response.data.survey_attempt_status== "Completed") {
                            $("#completeSurvey").addClass("d-none");
                            $("#completeSurveyquestion").addClass("d-none");
                           toastr.success('You have already submitted your answer');
                        } else{
                            var surveylist_id = response.data.survey_list_id;

                            var nestedSettings = {
                                "url": "/users/survey_list_questions",
                                "method": "GET",
                                "data": {
                                    "survey_list_id": surveylist_id
                                },
                            };

                            $.ajax(nestedSettings).done(function(quesResponse) {
                                // console.log(quesResponse);
                                questionsArray = quesResponse.data;
                                displayQuestion(currentQuestionIndex);
                                updateButtonText();

                                var imageSrc = response.data.image ? response.data.image : 'uploads/placeholder/default.png';
                                var image = "{{ url('/public') }}" + "/" + imageSrc;
                                $('#survey_data').append('<img src="' + image + '" height="80px" width="80px">' +
                                    response.data.name + ' <label class="btn btn-primary pull-right">' +
                                    response.data.status + '</label><br><label class="pull-right">Last Updated:' + response.data.updated_at + '</label>');
                            });
                        }
                    }
                });
            }

            function displayQuestion(index) {
                $('#question').html('');
                var currentQuestion = questionsArray[index];

                if (currentQuestion.question_type == 'Single Choice') {
                    var question = '<div class="mb-1"><h4 class="text-black fs-15">' + currentQuestion.name + '</h4></div>';

                    $.each(currentQuestion.answers, function(i, ans) {
                        question += '<div class="mb-1">';
                        question += '<input type="radio" name="Single_Choice" class="Single_Choice question input" value="' + ans.name  + '" />';
                        question += '<input type="hidden" class="survey_list_qs_id" value="' + ans.survey_list_qs_id + '" />';
                        question += '<input type="hidden" class="survey_list_qs_answers_id" value="' + ans.survey_list_qs_answers_id + '" />';
                        question += '<span class="mr-auto ml-2">' + ans.name + '</span>';
                        question += '</div>';
                    });
                } else if (currentQuestion.question_type == 'Text') {
                    var question = '<div class="mb-1"><h4 class="text-black fs-15">' + currentQuestion.name + '</h4></div>';
                    question += '<div>';
                    question += '<input type="text" name="text" class="form-control input" />';
                    question += '</div>';
                } else if (currentQuestion.question_type == 'Multiple Choice') {
                    var question = '<div class="mb-1"><h4 class="text-black fs-15">' + currentQuestion.name + '</h4></div>';

                    $.each(currentQuestion.answers, function(i, ans) {
                        question += '<div>';
                        question += '<input type="checkbox" name="Multiple_Choice" class="Multiple_Choice question input" value="' + ans.name  + '" />';
                        question += '<input type="hidden" class="survey_list_qs_id" value="'+ ans.survey_list_qs_id + '" />';
                        question += '<input type="hidden" class="survey_list_qs_answers_id" value="' + ans.survey_list_qs_answers_id + '" />';
                        question += '<span class="mr-auto ml-2">' + ans.name + '</span>';
                        question += '</div>';
                    });
                } else if (currentQuestion.question_type == 'Multilevel Choice') {
                    var question = '<div class="mb-1"><h4 class="text-black fs-15">' + currentQuestion.name + '</h4></div>';

                    $.each(currentQuestion.answers, function(i, ans) {
                        question += '<div>';
                        question += '<input type="radio" name="Multilevel_Choice" class="Multilevel_Choice question input" value="' + ans.name  + '" />';
                        question += '<input type="hidden" class="survey_list_qs_id" value="'+ ans.survey_list_qs_id + '" />';
                        question += '<input type="hidden" class="survey_list_qs_answers_id" value="' + ans.survey_list_qs_answers_id + '" />';
                        question += '<span class="mr-auto ml-2">' + ans.name + '</span>';
                        question += '</div>';
                    });
                }

                $('#question').html(question);
            }

            function updateButtonText() {
                if (currentQuestionIndex === 0) {
                    $('#previousBtn').prop('disabled', true);
                } else {
                    $('#previousBtn').prop('disabled', false);
                }

                if (currentQuestionIndex === questionsArray.length - 1) {
                    $('#nextBtn').text('Submit');
                } else {
                    $('#nextBtn').text('Next');
                }
            }

            $('#nextBtn').on('click', function() {
                // Get the user's answer for the current question
                var answer;
                currentQuestion=questionsArray[currentQuestionIndex];

                if (currentQuestion.question_type === 'Single Choice') {
                    var selectedRadio = $('input[name="Single_Choice"]:checked');
                    answer = selectedRadio.val();
                    var surveyListQsId = selectedRadio.siblings('.survey_list_qs_id').val();
                    var surveyListQsAnswersId = selectedRadio.siblings('.survey_list_qs_answers_id').val();
                } else if (currentQuestion.question_type === 'Text') {
                    answer = $('input[name="text"]').val();
                    var surveyListQsId = 0;
                    var surveyListQsAnswersId = 0;
                } else if (currentQuestion.question_type === 'Multiple Choice') {
                answer = [];

                $('input[name="Multiple_Choice"]:checked').each(function() {
                    answer.push($(this).val());
                });

                var surveyListQsId = [];
                var surveyListQsAnswersId = [];

                $('input[name="Multiple_Choice"]:checked').each(function() {
                    surveyListQsId.push($(this).siblings('.survey_list_qs_id').val());
                    surveyListQsAnswersId.push($(this).siblings('.survey_list_qs_answers_id').val());
                });
                } else if (currentQuestion.question_type === 'Multilevel Choice') {
                    var selectedRadio = $('input[name="Multilevel_Choice"]:checked');
                    answer = selectedRadio.val();
                    var surveyListQsId = selectedRadio.siblings('.survey_list_qs_id').val();
                    var surveyListQsAnswersId = selectedRadio.siblings('.survey_list_qs_answers_id').val();
                }

                if (isFormSubmitted) {

                    // The form has already been submitted, do nothing
                    return;
                }
                var ans_data = {
                    survey_list_qs_id: surveyListQsId,
                    survey_list_qs_answers_id: surveyListQsAnswersId,
                    answer: answer
                };

                if (currentQuestionIndex < questionsArray.length - 1) {
                    questionAnswers.push(ans_data);
                    currentQuestionIndex++;
                    displayQuestion(currentQuestionIndex);
                    updateButtonText();
                } else {
                    // Submit the survey
                    if (!isFormSubmitted) {
                        questionAnswers.push(ans_data);
                        // Push answers to the array
                        // Write your code here to handle pushing answers to the array
                        
                        isFormSubmitted = true; // Set the form submission flag
                        submitSurvey();
                    }
                }
                // console.log(questionAnswers);
            });
            
            $('#previousBtn').on('click', function() {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    displayQuestion(currentQuestionIndex);
                    updateButtonText();
                    questionAnswers.pop();
                }
            });
            $(document).off('click', '.question').on('click', '.question', function() {
                var qs_id = $(this).siblings('.survey_list_qs_id').val();
                var qs_answers_id = $(this).siblings('.survey_list_qs_answers_id').val();
                multipleQuestions(qs_id, qs_answers_id);
            });
            
            function multipleQuestions(qs_id,qs_answers_id){
                var multipleQuestionSettings = {
                    "url": "/users/get_child_qs",
                    "method": "GET",
                    "data": {
                        "parent_qs_id": qs_id,
                        "parent_qs_answers_id": qs_answers_id
                    },
                };
                $.ajax(multipleQuestionSettings).done(function(multipleQuesResponse) {
                        // console.log(multipleQuesResponse);
                        // console.log(questionsArray);
                        if (multipleQuesResponse.status == "success") {
                            // Find the index of the next multiple question
                            var nextMultipleIndex = currentQuestionIndex + 1;
                            while (nextMultipleIndex < questionsArray.length && questionsArray[nextMultipleIndex].question_type !== 'Multilevel Choice') {
                                nextMultipleIndex++;
                            }

                            // Remove all multiple questions from the current index onward
                            questionsArray.splice(currentQuestionIndex + 1, nextMultipleIndex - currentQuestionIndex);
                            $.each(multipleQuesResponse.data, function(i, ques) {
                                questionsArray.splice(currentQuestionIndex + 1, 0, ques);
                            });    
                            updateButtonText();
                            // console.log(questionsArray);
                        }
                });
            } 
            
            function submitSurvey() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // console.log('button clicked');
                var userId = $('#userId').val();
                var survey_list_id = $('#s_id').val();
                var SurveyCompletionSettings = {
                    "url": "/users/survey_list_reponses",
                    "method": "POST",
                    "data": {
                        "survey_list_id": survey_list_id,
                        "users_customers_id": userId,
                        "survey_list_qs_answers": questionAnswers
                    },
                };
                $.ajax(SurveyCompletionSettings).done(function(surveyResponse) {
                    if (surveyResponse.status == "success") {      
                        $('#survey_data').html("");
                        $("#completeSurvey").addClass("d-none");
                        $("#completeSurveyquestion").addClass("d-none");
                        toastr.success(surveyResponse.message);
                    }  else {
                        $("#completeSurveyquestion").addClass("d-none");
                        $("#completeSurvey").addClass("d-none");
                        $('#survey_data').html("");
                        toastr.error(surveyResponse.message);
                    }
                    // console.log(surveyResponse);
                });
            }

        });


    </script>
@endsection