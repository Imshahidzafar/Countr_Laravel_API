@extends('layout.admin.list_master')

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
                        <span class="ml-2">Manage Countries</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">                       
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <legend style="float: right;"><a style="float: right;" class="btn btn-primary" href="{{url('/admin/system_countries_add')}}"> Add Country </a></legend>
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Info</th>
                                                    <th>Phone Code</th>
                                                    <th>Capital</th>
                                                    <th>Currency</th>
                                                    <th>Location</th>
                                                    <th>Emoji</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($system_countries as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $items->system_countries_id }}</td>
                                                    <td>{{ $items->name }}</td>
                                                    <td>
                                                        ISO2: {{ $items->iso2 }}<br>
                                                        ISO3: {{ $items->iso3 }}<br>
                                                        Numeric Code: {{ $items->numeric_code }}
                                                    </td>
                                                    <td>{{ $items->phonecode }}</td>
                                                    <td>{{ $items->capital }}</td>
                                                    <td>
                                                        Currency: {{ $items->currency }}<br>
                                                        Currency Name: {{ $items->currency_name }}<br>
                                                        Currency Symbol: {{ $items->currency_symbol }}
                                                    </td>
                                                    <td>
                                                        Latitude: {{ $items->latitude }}<br>
                                                        Longitude: {{ $items->longitude }}
                                                    </td>
                                                    <td>{{ $items->emoji }}</td>
                                                    <td>{{ $items->created_at }}</td>
                                                    <td>{{ $items->updated_at }}</td>
                                                    <td>
                                                        @if ($items->status=='Active')
                                                        <span class="btn btn-success">Active</span>
                                                        @elseif ($items->status=='Deleted')
                                                        <span class="btn btn-danger">Deleted</span>
                                                        @else 
                                                        <span class="btn btn-warning">In Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary" href="{{url('/admin/system_states/' . $items->system_countries_id)}}">
                                                            <i class="fa fa-eye"></i> 
                                                        </a>

                                                        <a class="btn btn-info" href="{{url('/admin/system_countries_edit/' . $items->system_countries_id)}}">
                                                            <i class="fa fa-pencil"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/admin/system_countries_update/' . $items->system_countries_id . '/Active')}}">
                                                            <i class="fa fa-check"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" href="{{url('/admin/system_countries_update/' . $items->system_countries_id . '/Inactive')}}">
                                                            <i class="fa fa-times"></i> 
                                                        </a>

                                                        <a class="btn btn-danger" href="{{url('/admin/system_countries_delete/' . $items->system_countries_id)}}">
                                                            <i class="fa fa-trash"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
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
@endsection