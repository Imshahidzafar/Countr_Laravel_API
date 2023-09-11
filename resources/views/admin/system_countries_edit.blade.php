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
                        <span class="ml-2">Edit Countries</span>
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
                                <form action="/admin/system_countries_edit_data" method="POST" id="myform" name="myform" onsubmit="return validation()" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6">
                                            <b>Name</b>
                                            <b><input style="border:1px solid" type="text" name="name" value="{{$system_countries->name}}" class="form-control" placeholder="Enter Name" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>ISO2</b>
                                            <b><input style="border:1px solid" type="text" name="iso2" value="{{$system_countries->iso2}}" class="form-control" placeholder="Enter ISO2" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>ISO3</b>
                                            <b><input style="border:1px solid" type="text" name="iso3" value="{{$system_countries->iso3}}" class="form-control" placeholder="Enter ISO3" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Numeric Code</b>
                                            <b><input style="border:1px solid" type="text" name="numeric_code" value="{{$system_countries->numeric_code}}" class="form-control" placeholder="Enter Numeric Code" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Phone Code</b>
                                            <b><input style="border:1px solid" type="text" name="phonecode" value="{{$system_countries->phonecode}}" class="form-control" placeholder="Enter Phone Code" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Capital</b>
                                            <b><input style="border:1px solid" type="text" name="capital" value="{{$system_countries->capital}}" class="form-control" placeholder="Enter Capital" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Currency</b>
                                            <b><input style="border:1px solid" type="text" name="currency" value="{{$system_countries->currency}}" class="form-control" placeholder="Enter Currency" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Currency Name</b>
                                            <b><input style="border:1px solid" type="text" name="currency_name" value="{{$system_countries->currency_name}}" class="form-control" placeholder="Enter Currency Name" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Currency Symbol</b>
                                            <b><input style="border:1px solid" type="text" name="currency_symbol" value="{{$system_countries->currency_symbol}}" class="form-control" placeholder="Enter Currency Symbol" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Latitude</b>
                                            <b><input style="border:1px solid" type="text" name="latitude" value="{{$system_countries->latitude}}" class="form-control" placeholder="Enter Latitude" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Longitude</b>
                                            <b><input style="border:1px solid" type="text" name="longitude" value="{{$system_countries->longitude}}" class="form-control" placeholder="Enter Longitude" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Emoji</b>
                                            <b><input style="border:1px solid" type="text" name="emoji" value="{{$system_countries->emoji}}" class="form-control" placeholder="Enter Emoji" required></b>
                                        </div>
                                    </div>

                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6">
                                            <b>Status</b>
                                            <b>
                                                <select style="border:1px solid" name="status" class="form-control" required>
                                                    <option value="Active" <?php if($system_countries->status == 'Active') echo "selected"; ?>>Active</option>
                                                    <option value="Inactive" <?php if($system_countries->status == 'Inactive') echo "selected"; ?>>Inactive</option>
                                                </select>
                                            </b>
                                        </div>
                                    </div>
                                    <input type="hidden" name="system_countries_id" value="{{$system_countries->system_countries_id}}" required>
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