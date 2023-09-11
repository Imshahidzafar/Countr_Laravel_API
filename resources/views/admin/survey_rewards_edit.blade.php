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
                        <span class="ml-2">Edit Rewards</span>
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
                                <form action="/admin/survey_rewards_edit_data" method="POST" id="myform" name="myform" onsubmit="return validation()" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6">
                                            <b>Name</b>
                                            <b><input style="border:1px solid" type="text" name="name" class="form-control" placeholder="Enter Name" value="{{$survey_rewards->name}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Reward</b>
                                            <b><input style="border:1px solid" type="text" name="reward" class="form-control" placeholder="Enter Reward" value="{{$survey_rewards->reward}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Reward Type</b>
                                            <b><select class="form-control" id="reward_type" name="reward_type">
                                                <option value="Fixed" {{ $survey_rewards->reward_type == 'Fixed' ? 'selected' : '' }} >Fixed</option>
                                                <option value="Points" {{ $survey_rewards->reward_type == 'Points' ? 'selected' : '' }}>Points</option>
                                            </select></b>
                                        </div>
                                        @if($survey_rewards->reward_type== "Points")
                                        <div class="form-group col-md-6 d-block" id="points_div">                                    
                                            <b>Points</b>
                                            <b><input type="number" class="form-control quantity input" value="{{$survey_rewards->quantity}}" name="quantity" ></b>
                                            <span class="error_msg" id="reward_error"></span>
                                        </div>
                                        @else
                                        <div class="form-group col-md-6 d-none" id="points_div">                                    
                                            <b>Points</b>
                                            <b><input type="number" class="form-control quantity input" value="{{$survey_rewards->quantity}}" name="quantity" ></b>
                                            <span class="error_msg" id="reward_error"></span>
                                        </div>

                                        @endif
                                        <div class="form-group col-md-6">
                                            <b>Status</b>
                                            <b>
                                                <select style="border:1px solid" name="status" class="form-control" required>
                                                    <option value="Active" <?php if($survey_rewards->status == 'Active') echo "selected"; ?>>Active</option>
                                                    <option value="Inactive" <?php if($survey_rewards->status == 'Inactive') echo "selected"; ?>>Inactive</option>
                                                </select>
                                            </b>
                                        </div>
                                        
                                        <div class="row col-md-6"> 
                                            <div id="profile-container">
                                                <img id="imagePreview" class="imagePreview" src="{{asset($survey_rewards->image)}}" />
                                            </div>
                                            <input id="imageUpload" class="imageUpload" type="file" name="image" placeholder="Image" onchange="loadFile(event)" capture>
                                            <label id="empty"></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="survey_rewards_id" value="{{$survey_rewards->survey_rewards_id}}" required>
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
@section('script')			
<script>
        $(document).ready(function() {
            $('#reward_type').change(function() {
                var selectedOption = $(this).val(); 
            
                if (selectedOption === 'Points') {
                $('#points_div').addClass('d-block').removeClass('d-none');
                } else if (selectedOption === 'Fixed') {
                $('#points_div').removeClass('d-block').addClass('d-none');
                }
            });
        });
        </script>
@endsection