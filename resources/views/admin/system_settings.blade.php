@extends('layout.admin.list_master')

@section('content')
    <style>
        .imageUpload{
            display: none;
        }

        .profileImage{
            cursor: pointer;
            width: 100%;
        }

        #profile-container {
            margin: 20px auto;
            color: white;
            justify-content: center;
            overflow: hidden;
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
            display:none;
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
                        <span class="ml-2">Settings</span>
                        @endsection               
                    </ol>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    @include('layout.admin.settings')
                    <div class="card">
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/admin/system_settings_edit')}}">
                                    @csrf
                                    <div class="col-xl-12 form-group">
                                        <label class="col-sm-12 control-label">Invite text for app</label>
                                        <small class="col-sm-12 control-label">Add the text that will be forwarded when user invite others</small>
                                        <div class="col-sm-12">
                                            <textarea rows="5" class="input-mask form-control" name="{{ $system_settings[16]->type }}" required>{{ $system_settings[16]->description }}</textarea>
                                        </div>
                                    </div>
                       <legend class="col-xl-12">General Settings</legend>
                                    <div class="row">
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Contact Email</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[1]->type }}" value="{{ $system_settings[1]->description }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Contact Phone Number</label>
                                            <div class="col-sm-12">
                                                <input style="border:1px solid" type="tel"  name="{{ $system_settings[2]->type }}" id="phone" class="form-control" value="{{ $system_settings[2]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">System Name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[0]->type }}" value="{{ $system_settings[0]->description }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">Address</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[4]->type}}" value="{{ $system_settings[4]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 form-group">
                                            <label class="col-sm-12 control-label">App Social Login</label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[15]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[15]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[15]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Link Facebook</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[20]->type}}" value="{{ $system_settings[20]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Link Instagram</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[21]->type}}" value="{{ $system_settings[21]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Link Linked-In</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[22]->type}}" value="{{ $system_settings[22]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Link Twitter</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[23]->type}}" value="{{ $system_settings[23]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Guidelines</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[24]->type}}" value="{{ $system_settings[24]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Guidelines Video</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[25]->type}}" value="{{ $system_settings[25]->description }}" required>
                                            </div>
                                        </div>

                                        <hr class="col-md-12">
                                        
                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Eco Counter</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[30]->type}}" value="{{ $system_settings[30]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Prize Explanation Link</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[36]->type}}" value="{{ $system_settings[36]->description }}" required>
                                            </div>
                                        </div>                                        
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Tutorial Link(Enter Embeded Url)</label>
                                            <div class="col-sm-12">
                                                <input type="link"  id="link" class="input-mask form-control" name="{{ $system_settings[17]->type}}" value="{{ $system_settings[17]->description }}" placeholder="Enter Embeded Url" required>
                                            </div>                                            
                                            <div id="link_error" class="error"></div>
                                        </div>                                        

                                        <hr class="col-md-12">

                                        <div class="col-xl-12 form-group">
                                            <h3>Activate Questions Types</h3>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Text Questions </label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[32]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[32]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[32]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Single Questions </label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[33]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[33]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[33]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Multiple Questions </label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[34]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[34]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[34]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 form-group">
                                            <label class="col-sm-12 control-label">Multi Level Questions </label>
                                            <div class="col-sm-12">
                                                <select class="input-mask form-control" name="{{ $system_settings[35]->type }}" required>
                                                    <option value="Yes" <?php if($system_settings[35]->description == 'Yes') echo "selected"; ?>>Yes</option>
                                                    <option value="No" <?php if($system_settings[35]->description == 'No') echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr class="col-md-12">

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Welcome Heading</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[31]->type}}" value="{{ $system_settings[31]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Welcome Note</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[26]->type}}" value="{{ $system_settings[26]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Welcome Questions</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[28]->type}}" value="{{ $system_settings[28]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-group">
                                            <label class="col-sm-12 control-label">Welcome Users</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="input-mask form-control" name="{{ $system_settings[29]->type}}" value="{{ $system_settings[29]->description }}" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 form-group">
                                            <label class="col-sm-12 control-label">Welcome Background</label>
                                            <div class="col-sm-12">
                                                <div id="profile-container">
                                                    <img id="imagePreview1" class="imagePreview1" style="width: 200px; height: 200px;" src="{{asset('uploads/system_image/'.$system_settings[27]->description)}}" />
                                                </div>
                                                <input id="imageUpload1" class="imageUpload1" type="file" name="welcome_bg" placeholder="Image" onchange="loadFile(event)" capture>
                                                <label id="empty"></label>
                                            </div>
                                            <small class="col-sm-12 control-label"> Size Recommended (430 * 932)</small>
                                        </div>

                                        <hr class="col-md-12">
                                        
                                        <div class="col-xl-12 form-group">
                                            <label class="col-sm-12 control-label">Logo</label>
                                            <div class="col-sm-12">
                                                <div id="profile-container">
                                                    <img id="imagePreview" class="imagePreview" src="{{asset('uploads/system_image/'.$system_settings[5]->description)}}" />
                                                </div>
                                                <input id="imageUpload" class="imageUpload" type="file" name="image" placeholder="Image" onchange="loadFile(event)" capture>
                                                <label id="empty"></label>
                                            </div>
                                            <small class="col-sm-12 control-label"> Size Recommended (744 * 138)</small>
                                        </div>
                                    </div>
                                   
                                    <div class="col-xl-12 form-group">
                                        <div class="col-sm-12">
                                            <input type="hidden" class="input-mask form-control" name="page_name" value="system_settings" required>
                                            <button type="submit" class="btn btn-primary updateBtn" style="float: right;">Update</button>
                                        </div>
                                    </div>
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
        $(document).ready(function() {
            $(document).on('change', '#link', function(e) {
                e.preventDefault();
                
                var embeddedUrl = $('#link').val().trim();
                var allowedDomains = ['www.youtube.com', 'www.vimeo.com'];
                var urlParts = new URL(embeddedUrl);
                
                if (allowedDomains.indexOf(urlParts.hostname) === -1) {
                    toastr.error('Please enter Tutorial Link a valid embedded URL.');
                }
            });
        });
    </script>
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

        $("#imagePreview1").click(function(e) {
            $("#imageUpload1").click();
        });

        function loadFile(event) {
            var image = document.getElementById('imagePreview1');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection