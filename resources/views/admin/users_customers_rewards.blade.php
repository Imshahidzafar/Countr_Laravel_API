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
                    <span class="ml-2">Users Rewards</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers" style="color: white; margin-bottom: 20px;">All</a>
                    <a class="btn <?php if($filter == 'unclaimed') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=unclaimed" style="color: white; margin-bottom: 20px;">Unclaimed</a>
                    <a class="btn <?php if($filter == 'claimed') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="users_customers?filter=claimed" style="color: white; margin-bottom: 20px;">Claimed</a>   
                    <br>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Survey Name</th>
                                            <th>Survey Reward Name</th>
                                            <th>description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_data as $key => $items)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $items->survey_list->name ?? "N/A" }}</td>
                                            <td>{{ $items->survey_rewards->name ?? "N/A" }}</td>
                                            <td>{{ $items->description }}</td>
                                            <td>
                                                @if ($items->status=='unclaimed')
                                                <span class="btn btn-info">Unclaimed</span>
                                                @elseif ($items->status=='claimed')
                                                <span class="btn btn-success">Claimed</span>
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