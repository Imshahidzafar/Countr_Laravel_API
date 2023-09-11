@extends('layout.partners.list_master')

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

        .hidden {
            display: none !important;
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
                        <span class="ml-2">Edit Survey</span>
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
                                <form action="/partners/survey_list_edit_data" method="POST" id="myform" name="myform" onsubmit="return validation()" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <h3> General Section </h3><hr class="col-md-12" />

                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6 hidden">
                                            <b>Select Partner</b>
                                            <b>
                                                <select style="border:1px solid" name="users_partners_id" class="form-control" required>
                                                    <?php foreach($users_partners as $data){ ?>
                                                    <option value="{{$data->users_partners_id}}" <?php if($survey_list->users_partners_id == $data->users_partners_id) echo "selected"; ?>>{{$data->first_name}} ({{$data->email}})</option>
                                                    <?php } ?>
                                                </select>
                                            </b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Select Category</b>
                                            <b>
                                                <select style="border:1px solid" name="survey_categories_id" class="form-control" required>
                                                    <?php foreach($survey_categories as $data){ ?>
                                                    <option value="{{$data->survey_categories_id}}" <?php if($survey_list->survey_categories_id == $data->survey_categories_id) echo "selected"; ?>>{{$data->name}}</option>
                                                    <?php } ?>
                                                </select>
                                            </b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Select Rewards</b>
                                            <b>
                                                <select style="border:1px solid" name="survey_rewards_id" class="form-control" required>
                                                    <?php foreach($survey_rewards as $data){ ?>
                                                    <option value="{{$data->survey_rewards_id}}">{{$data->name}} ({{$data->reward}})</option>
                                                    <?php } ?>
                                                </select>
                                            </b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Survey Name</b>
                                            <b><input style="border:1px solid" type="text" name="name" class="form-control" placeholder="Enter Survey Name" value="{{$survey_list->name}}" required></b>
                                        </div>
                                        
                                        <div class="row col-md-6"> 
                                            <div id="profile-container">
                                                <image id="imagePreview" class="imagePreview" src="{{asset($survey_list->image)}}" />
                                            </div>
                                            <input id="imageUpload" class="imageUpload" type="file" name="image" placeholder="Image" onchange="loadFile(event)" capture>
                                            <label id="empty"></label>
                                        </div>                                        

                                        <div class="form-group col-md-6">
                                            <b>Survey Status</b>
                                            <b>
                                                <select style="border:1px solid" name="status" class="form-control" required>
                                                    <option value="Active" {{ $survey_list->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ $survey_list->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </b>
                                        </div>
                                    </div>
                                    <input type="hidden" name="survey_list_id" value="{{$survey_list->survey_list_id}}" required>
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
        $("#imagePreview").click(function(e) {
            $("#imageUpload").click();
        });

        function loadFile(event) {
        	var image = document.getElementById('imagePreview');
        	image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection