<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <?php 
            $system_image=DB::table('system_settings')->select('description')->where('type', 'system_image')->get(); 
            $system_name=DB::table('system_settings')->select('description')->where('type', 'system_name')->get(); 
            $system_countries=DB::table('system_countries')->get(); 
            $system_states=DB::table('system_states')->get(); 
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">        
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <title><?php echo $system_name[0]->description; ?> :: Users Portal</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image" sizes="24x24" href="/public/uploads/system_image/{{$system_image[0]->description}}">
	    <link href="{{asset('assets/build/css/intlTelInput.css')}}" rel="stylesheet">
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
        
        <style>
            body{
               background-image: url("https://portal.countr.ai/public/uploads/system_image/bgpartner.jpg");
               
            }
            .iti--show-flags{
                width:100%!important;
            }
        </style>
    </head>
    <body class="h-100">
        <div class="authincation h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-md-6">
                        <div class="authincation-content">
                            <div class="row no-gutters">
                                <div class="col-xl-12">
                                    <div class="auth-form">
                                        <div class="text-center mb-4 logo">
                                            <img class="text-center mb-4" style="width: 40%;" src="/public/uploads/system_image/{{$system_image[0]->description}}" alt="image">
                                        </div>

                                        <h4 class="text-center mb-4" style="color:#fff">Welcome To The User's Portal Signup </h4>
                                        <form  method="POST" action="{{url('/users/signup_submit')}}">
                                            @csrf
                                            <div class="form-group">
                                                <label class="mb-1"><strong>First Name</strong></label>
                                                <input type="text" class="form-control"  id="first_name" name="first_name" placeholder="Enter First Name">
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>Last Name</strong></label>
                                                <input type="text" class="form-control"  id="last_name" name="last_name" placeholder="Enter Last Name">
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>Phone</strong></label>
                                                <input type="tel" class="form-control"  id="phone" name="phone_no" placeholder="Enter Phone">
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>Country</strong></label>
                                                <select class="form-control" name="system_countries_id" id="system_countries_id" required>
                                                    @foreach($system_countries as $system_country)
                                                        <option value="{{ $system_country->system_countries_id }}">{{ $system_country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>State</strong></label>
                                                <select class="form-control" name="system_states_id" id="system_states_id" required>
                                                   
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>Email</strong></label>
                                                <input type="email" class="form-control"  id="email" name="email"  pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" placeholder="Enter email"  title="Please enter only '@' character.">
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1"><strong>Password</strong></label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-block">Signup Now</button>
                                            </div>
                                        </form>

                                        <br>
                                        
                                        <div class="buttons text-center">
                                            <a href="{{url('/')}}">Back to login</a>
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
            Scripts
        ***********************************-->
        <!-- Required vendors -->
        <script src="{{asset('vendor/global/global.min.js')}}"></script>
    	<script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
        <script src="{{asset('js/custom.min.js')}}"></script>
        <script src="{{asset('js/deznav-init.js')}}"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <link href="{{asset('toasters/toastr.min.css')}}" rel="stylesheet" type="text/css" />   
        <script src="{{asset('toasters/toastr.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/build/js/intlTelInput.js')}}"></script>
        <script src="{{asset('assets/build/js/mask_list.js')}}"></script>
        <script>
            $(document).ready(function() {
            
            $('#system_countries_id').on('change', function() {
                var system_countries_id = this.value;
                $("#system_states_id").html('');
                    $.ajax({
                        url:"/users/getState",
                        type: "POST",
                        data: {
                            system_countries_id: system_countries_id,
                            _token: '{{csrf_token()}}' 
                        },
                        success: function(result){
                            $('#system_states_id').html('<option value="">Select State</option>'); 
                            $.each(result.states,function(key,value){
                                $("#system_states_id").append('<option value="'+value.system_states_id+'">'+value.name+'</option>');
                            });
                        }
                    });
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
              dropdownContainer: document.body,
              formatOnDisplay: true,
              hiddenInput: "phone",
              localizedCountries: { 'de': 'Deutschland' },
              nationalMode: false,
              separateDialCode: false,
              showFlags: true,
              utilsScript: "{{asset('assets/build/js/utils.js')}}"
            });
    
            const selector = "#phone";
            mask(selector);
    
          </script>
        <script>
            toastr.options = {
              "closeButton": true,
              "debug": false,
              "positionClass": "toast-top-right",
              "onclick": null,
              "showDuration": "1000",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            //Command: toastr['success']("hello");

            <?php if(Session::has('success')){ ?> Command: toastr['success']("<?php echo Session('success'); ?>"); <?php } ?>
            <?php if(Session::has('error')){ ?> Command: toastr['error']("<?php echo Session('error'); ?>"); <?php } ?>
            <?php if(Session::has('warning')){ ?> Command: toastr['warning']("<?php echo Session('warning'); ?>"); <?php } ?>
            <?php if(Session::has('info')){ ?> Command: toastr['info']("<?php echo Session('info'); ?>"); <?php } ?>
        </script>
    </body>
</html>