@extends('layout.admin.list_master')

@section('content')

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
                <input type="hidden" id="s_id" value="{{ $s_id }}">
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
                                                    <th>Reward</th>
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
    <button class="hide-modal-btn" style="display: none;"></button>
        <!-- Add Reward -->
        <div class="modal fade" id="exampleModalAddReward">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                @section('titleBar')
                <span class="ml-2">Add Reward</span>
                @endsection 
                    <div class="modal-header">
                        <h5 class="modal-title">Add Reward</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <div class="row col-md-12"> 
                                <div class="form-group col-md-12">
                                    <b>Description</b>
                                    <b><textarea rows="4" cols="50" name="description" id="description" class="form-control description input" ></textarea></b>
                                    <span class="error_msg" id="description_error"></span>
                                </div>
                                <div class="form-group col-md-12" id="reward">          
                                </div>
                                {{-- <div class="form-group col-md-12" id="checktext">                                    
                                    <b><input type="checkbox" name="checkbox" id="togglecheckbox"></b>
                                    <span class="error_msg" id="checkbox_error"></span>
                                </div>
                                <div class="form-group col-md-12 d-block" id="div2">                                    
                                    <b>Enter Text</b>
                                    <b><input type="text" class="form-control rew_data input" name="reward" id="rew_data_text"></b>
                                    <span class="error_msg" id="reward_error"></span>
                                </div>
                                <div class="form-group col-md-12 d-none" id="div1">                                    
                                    <b>Enter Number</b>
                                    <b><input type="number" class="form-control rew_data input" name="reward" id="rew_data_number"></b>
                                    <span class="error_msg" id="reward_error"></span>
                                </div> --}}
                            </div>
                            </div>
                            <input  type="hidden" name="users_customers_id" class="form-control users_customers_id input" >
                            <input  type="hidden" name="survey_list_id" class="form-control survey_list_id input" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary add_reward">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Reward -->
    
@endsection
@section('script')
    <script>
        $(document).ready(function(){
                fetch();
                // $('#togglecheckbox').change(function() {
                //     if ($(this).is(':checked')) {
                //         $('#div1').addClass('d-block');
                //         $('#div1').removeClass('d-none');
                //         $('#div2').addClass('d-none');
                //         $('#div2').removeClass('d-block');
                //     } else {
                //         $('#div1').addClass('d-none');
                //         $('#div1').removeClass('d-block');
                //         $('#div2').addClass('d-block');
                //         $('#div2').removeClass('d-none');
                //     }
                // });
                function closeModal(){
                    const dismissElements = document.querySelectorAll('[data-dismiss="modal"]');
                        dismissElements.forEach((element) => {
                        element.addEventListener('click', () => {
                            const modalDialog = element.closest('.modal-dialog');
                            if (modalDialog) {
                            modalDialog.classList.remove('show');
                            document.body.classList.remove('modal-open');
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) {
                                backdrop.parentNode.removeChild(backdrop);
                            }
                            }
                        });
                        });
                }
            function fetch() {
                var id = $('#s_id').val();
                var settings = {
                    "url":  "/admin/survey_list_responses",
                    "method": "GET",
                    "data":{
                        "s_id":id,
                    },
                };
                $.ajax(settings).done(function (response) {
                    console.log(response.data);
                                $('tbody').html("");
                                $.each(response.data, function (key, items) {
                                    var rewardButton = items.reward_assigned == "yes"
                                    ? `<lable class="btn btn-success">Reward Assigned</lable>`
                                    : `<button class="btn btn-primary add_User_Reward" data-surveyid="${items.survey_list_reponses_id}">Assign Reward</button>`;
                                var surveyAnswers = items.survey_answers && items.survey_answers.survey_list_qs_id ?
                                    (items.answers_reponses[0] ?
                                        items.answers_reponses.map((answers, counter) => `${counter + 1} - ${answers.name}`).join('<br>') : '') : '';

                                var tbody = `<tr class="odd gradeX">
                                    <td>${key + 1}</td>
                                    <td>${rewardButton}</td>
                                    <td>
                                        ${items.data_survey.name} <br>
                                        ${items.data_survey_categories.name} <br>
                                        ${items.data_survey_rewards.name} 
                                        ${items.data_survey_rewards.reward}
                                    </td>
                                    <td id="users_id">
                                        <input type="hidden" name="survey_id_data" class="form-control  input" value="${items.users_data.id}">
                                        ${items.users_data.first_name} <br>
                                        ${items.users_data.email} <br>
                                        ${items.users_data.phone}
                                    </td>
                                    <td>
                                        ${items.survey_answers ? items.survey_questions.name : ''}
                                    </td>
                                    <td>
                                        ${surveyAnswers}
                                    </td>
                                    <td>${items.answer}</td>
                                    <td>${items.created_at}</td>
                                    <td>${items.updated_at}</td>
                                </tr>`;
                        $('tbody').append(tbody);
                    });

                });
               
            }
            $(document).on("click",'.add_User_Reward', function (e) {
                e.preventDefault();
                var survey_id=$(this).data("surveyid");
                $('#exampleModalAddReward').modal('show');
                console.log(survey_id);
                var settings = {
                    "url": "/admin/get_single_survey_data",
                    "method": "GET",
                    "timeout": 0,
                    "data":{
                        "survey_id":survey_id,
                    },
                };

                $.ajax(settings).done(function (response) {
                    console.log(response);
                    $('#reward').html('');
                    if(response.status == "error"){
                        toastr.success(response.message);
                    }else{
                        
                        $('#reward').append('<label><b>Reward: </b>' + response.data.reward_data.name + '('+ response.data.reward_data.reward+')</label>');
                        $('.survey_list_id').val(response.data.survey_list_id);
                        $('.users_customers_id').val(response.data.users_customers_id);
                    }
                });
            });
        $(document).on('click','.add_reward',function(e){
                e.preventDefault();
                
                $('.add_reward').attr('data-dismiss', 'modal'); 
                var settings = {
                "url": "/admin/add_user_reward",
                "method": "POST",
                "timeout": 0,
                "data": {
                    'survey_list_id':$('.survey_list_id').val(),
                    'users_customers_id':$('.users_customers_id').val(),
                    'description':$('#description').val(),
                },
            };
            $.ajax(settings).done(function (response) {
                console.log(response);
                if (response.status == "success") {
                    $( ".input" ).each(function() {
                        $(this).val("");
                    });
                    fetch();
                    toastr.success(response.message);
                    

                } else {
                    $( ".input" ).each(function() {
                        $(this).val("");
                    });
                    fetch();
                    toastr.error(response.message);
                }
                // closeModal();
                $('#exampleModalAddReward').modal('hide');
            });
        });


        });
    </script>
@endsection


