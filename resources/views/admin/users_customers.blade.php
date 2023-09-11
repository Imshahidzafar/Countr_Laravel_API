@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
        }
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Manage Users</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers" style="color: white; margin-bottom: 20px;">All</a>
                    <a class="btn <?php if($filter == 'Pending') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Pending" style="color: white; margin-bottom: 20px;">Pending</a>
                    <a class="btn <?php if($filter == 'Active') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Active" style="color: white; margin-bottom: 20px;">Active</a>
                    <a class="btn <?php if($filter == 'Inactive') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Inactive" style="ccolor: white; margin-bottom: 20px;">Inactive</a>
                    <a class="btn <?php if($filter == 'Deleted') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=Deleted" style="color: white; margin-bottom: 20px;">Deleted</a>     
                    <br>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Survey Responses</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Rewards</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $items)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $items->users_customers_id }}</td>
                                            <td>
                                                @if($items->profile_pic)  
                                                <img src="{{ asset($items->profile_pic)}}" width="80px" height="80px">
                                                @else
                                                <img src="{{asset('uploads/placeholders/default.png')}}" height="80px" width="80px">
                                                @endif

                                                {{ $items->first_name }} {{ $items->last_name }}
                                            </td>
                                            <td>{{ $items->email }}</td>
                                            <td>{{ $items->phone }}</td>
                                            <td><?php echo $total_reponses = DB::table('survey_list_reponses')->where('users_customers_id', $items->users_customers_id)->count(); ?></td>
                                            <td><?php  $country = DB::table('system_countries')->where('system_countries_id', $items->system_countries_id)->first();
                                            echo $country->name ?></td>
                                            <td>
                                                @if ($items->status=='Pending')
                                                <span class="btn btn-info">Pending</span>
                                                @elseif ($items->status=='Active')
                                                <span class="btn btn-success">Active</span>
                                                @elseif ($items->status=='Inactive')
                                                <span class="btn btn-warning">Inactive</span>
                                                @else 
                                                <span class="btn btn-danger">Deleted</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-secondary" href="{{url('/admin/users_customers_rewards/' . $items->users_customers_id)}}"> 
                                                    <i class="fa fa-eye"></i> 
                                                </a>
                                            </td>

                                            <td>
                                               

                                                @if ($items->status=='Active')
                                                <a class="btn btn-warning" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>
                                                @elseif ($items->status=='Inactive')
                                                <a class="btn btn-success" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($items->status=='Pending' || $items->status=='Deleted')
                                                <a class="btn btn-warning" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>

                                                <a class="btn btn-success" href="{{url('/admin/users_customers_update/' . $items->users_customers_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($items->status!='Deleted')
                                                <a class="btn btn-danger" href="{{url('/admin/users_customers_delete/' . $items->users_customers_id)}}"> 
                                                    <i class="fa fa-trash"></i> 
                                                </a>
                                                @endif
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
@endsection