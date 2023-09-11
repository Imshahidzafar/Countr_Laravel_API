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
                        <span class="ml-2">Manage Users Partners</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_partners" style="color: white; margin-bottom: 20px;">All</a>
                        <a class="btn <?php if($filter == 'Pending') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_partners?filter=Pending" style="color: white; margin-bottom: 20px;">Pending</a>
                        <a class="btn <?php if($filter == 'Active') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_partners?filter=Active" style="color: white; margin-bottom: 20px;">Active</a>
                        <a class="btn <?php if($filter == 'Inactive') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_partners?filter=Inactive" style="ccolor: white; margin-bottom: 20px;">Inactive</a>
                        <a class="btn <?php if($filter == 'Deleted') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_partners?filter=Deleted" style="color: white; margin-bottom: 20px;">Deleted</a>     
                        <br>

                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <legend style="float: right;"><a style="float: right;" class="btn btn-primary" href="{{url('/admin/users_partners_add')}}"> Add Partners </a></legend>
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Status</th>
                                                    <th>ID</th>
                                                    <th>Partner Info</th>
                                                    <th>Address</th>
                                                    <th>Verified</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if ($items->status=='Active')
                                                        <span class="btn btn-success">Active</span>
                                                        @elseif ($items->status=='Deleted')
                                                        <span class="btn btn-danger">Deleted</span>
                                                        @else 
                                                        <span class="btn btn-warning">In Active</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $items->users_partners_id }}</td>
                                                    <td>
                                                        @if($items->user_image)  
                                                        <img src="{{ asset($items->user_image)}}" width="80px" height="80px">
                                                        @else
                                                        <img src="{{asset('uploads/placeholders/default.png')}}" height="80px" width="80px">
                                                        @endif
                                                        <br>

                                                        Name: {{ $items->first_name }}<br>
                                                        Mobile: {{ $items->mobile }}<br>
                                                        Email: {{ $items->email }}
                                                    </td>
                                                    <td>{{ $items->address }} <br> {{ $items->city }}</td>
                                                    <td>{{ $items->verified_badge }}</td>

                                                    <td>
                                                        <a class="btn btn-info" href="{{url('/admin/users_partners_edit/' . $items->users_partners_id)}}">
                                                            <i class="fa fa-pencil"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/admin/users_partners_update/' . $items->users_partners_id . '/Active')}}">
                                                            <i class="fa fa-check"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" href="{{url('/admin/users_partners_update/' . $items->users_partners_id . '/Inactive')}}">
                                                            <i class="fa fa-times"></i> 
                                                        </a>

                                                        <a class="btn btn-danger" href="{{url('/admin/users_partners_delete/' . $items->users_partners_id)}}">
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