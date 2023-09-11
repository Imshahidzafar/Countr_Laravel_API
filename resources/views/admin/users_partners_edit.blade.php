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
        .iti--show-flags{
            width:100%!important;
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
                        <span class="ml-2">Edit Partner Users</span>
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
                                <form action="/admin/users_partners_edit_data" method="POST" id="myform" name="myform" onsubmit="return validation()" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6">
                                            <b>Partner First Name</b>
                                            <b><input style="border:1px solid" type="text" name="first_name" class="form-control" placeholder="Enter Partner Name" value="{{$users_partners->first_name}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Partner Email</b>
                                            <b><input style="border:1px solid" type="email" name="email" class="form-control" placeholder="Enter Partner Email" value="{{$users_partners->email}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Partner Password</b>
                                            <b><input style="border:1px solid" type="password" name="password" class="form-control" placeholder="Enter Partner Password" value="{{$users_partners->password}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Partner Mobile</b>
                                            <b><input style="border:1px solid" type="tel"  name="mobile_number" id="phone" class="form-control" placeholder="Enter Mobile" value="{{$users_partners->mobile}}"  required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Partner City</b>
                                            <b><input style="border:1px solid" type="text" name="city" class="form-control" placeholder="Enter Partner City" value="{{$users_partners->city}}" required></b>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <b>Partner Address</b>
                                            <b><textarea style="border:1px solid" name="address" class="form-control" placeholder="Enter Partner Address" required>{{$users_partners->address}}</textarea></b>
                                        </div>
                                    </div>

                                    <div class="row col-md-12"> 
                                        <div class="form-group col-md-6">
                                            <b>Partner Status</b>
                                            <b>
                                                <select style="border:1px solid" name="status" class="form-control" required>
                                                    <option value="Active" <?php if($users_partners->status == 'Active') echo "selected"; ?>>Active</option>
                                                    <option value="Inactive" <?php if($users_partners->status == 'Inactive') echo "selected"; ?>>Inactive</option>
                                                </select>
                                            </b>
                                        </div>
                                        
                                        <div class="row col-md-6"> 
                                            <div id="profile-container">
                                                <img id="imagePreview" class="imagePreview" src="{{asset($users_partners->user_image)}}" />
                                            </div>
                                            <input id="imageUpload" class="imageUpload" type="file" name="image" placeholder="Image" onchange="loadFile(event)" capture>
                                            <label id="empty"></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="users_partners_id" value="{{$users_partners->users_partners_id}}" required>
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
    <script src="{{asset('assets/build/js/intlTelInput.js')}}"></script>
    <script src="{{asset('assets/build/js/mask_list.js')}}"></script>
    <script>
      
        
        const mask = (selector) => {
            function setMask() {
                let matrix = '+###############';
                let phone = this.value.replace(/[\s#-)(]/g, '');

                maskList.forEach(item => {
                    let code = item.code.replace(/[\s#]/g, '');
                    if (phone.startsWith(code)) {
                        matrix = item.code;
                    }
                });

                let i = 0;
                let countryCode = matrix.match(/\+/g).length;
                let val = phone.replace(/\D/g, '');

                this.value = matrix.replace(/(?!\+)./g, function(a) {
                    if (a === '+') {
                        return a;
                    }
                    return /[#\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? '' : a;
                });
            }

            let inputs = document.querySelectorAll(selector);

            inputs.forEach(input => {
                if (!input.value) input.value = '+';
                input.addEventListener('input', setMask);
                input.addEventListener('focus', setMask);
                input.addEventListener('blur', setMask);
            });
        };


        // Usage:
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
          allowDropdown: true,
          autoInsertDialCode: true,
          // autoPlaceholder: "off",
          dropdownContainer: document.body,
          // excludeCountries: ["us"],
          formatOnDisplay: true,
          // geoIpLookup: function(callback) {
          //   fetch("https://ipapi.co/json")
          //     .then(function(res) { return res.json(); })
          //     .then(function(data) { callback(data.country_code); })
          //     .catch(function() { callback("us"); });
          // },
          hiddenInput: "mobile",
          // initialCountry: "auto",
          localizedCountries: { 'de': 'Deutschland' },
          nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
        //   preferredCountries: ['ng'],
          separateDialCode: false,
          showFlags: true,
          utilsScript: "{{asset('assets/build/js/utils.js')}}"
        });

        const selector = "#phone";
        mask(selector);

      </script>
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