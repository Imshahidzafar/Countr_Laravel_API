@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
        }
        .iti--show-flags{
            width:100%!important;
        }
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
                <ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Settings</span>
                    @endsection
                </ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    @include('layout.admin.settings')
                    <div class="card custom-tab-1">
                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end">
                                <li class="nav-item mr-3">
                                    <a class="nav-link active bg-transparent px-2" data-toggle="tab" href="#general">
                                        General Settings
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="general" role="tabpanel">
                                    <div class="post-details">
                                        <div class="row">
                                            @foreach ($fetch_data as $key => $items)
                                            <form class="form-valide" action="{{ url('/admin/account_settings_update', [$items->users_system_id]) }}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Name <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter a Name" value="{{ $items->first_name }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Email <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter a Email" value="{{ $items->email }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Password <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter a Password" value="{{ $items->password }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Mobile <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12"> 
                                                            <input style="border:1px solid" type="tel"  name="mobile_number" id="phone" class="form-control" placeholder="Enter a Mobile" value="{{ $items->mobile }}"  required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">City <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter a city" value="{{ $items->city }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Address <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter a address" value="{{ $items->address }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 form-group">
                                                        <label class="col-lg-4 col-form-label" for="val-username">Image <span class="text-danger">*</span> </label>
                                                        <div class="col-lg-12">
                                                            <img src="{{ url('public') }}/{{ $items->user_image }}" width="150px" height="150px">
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <input type="file" class="form-control" id="image" name="image">
                                                        </div>

                                                        
                                                    </div>
                                                
                                                    <div class="col-xl-12 form-group row">
                                                        <div class="col-lg-12 ml-auto">
                                                            <input type="hidden" class="form-control" id="users_system_id" name="users_system_id" value="{{ $items->users_system_id }}">
                                                            <button type="submit" class="btn btn-primary pull-right">Update Profile</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            @endforeach
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
@endsection