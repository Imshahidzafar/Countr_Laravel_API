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
                        <span class="ml-2">Manage Blogs</span>
                        @endsection
                    </div>
                </div>

                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="basic-form" style="width: 100%;">
                                    <legend style="float: right;"><a style="float: right;" class="btn btn-primary" href="{{url('/admin/blogs_add')}}"> Add Blog </a></legend>
                                    <div class="table-responsive">
                                        <table id="example" class="table dt-responsive nowrap display min-w850">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Featured</th>
                                                    <th>Image</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($blogs as $key => $items)
                                                <tr class="odd gradeX">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $items->title }}</td>
                                                    <td>{{ $items->description }}</td>
                                                    <td>{{ $items->is_featured }}</td>
                                                    <td> @if($items->image)  
                                                        <img src="{{ asset($items->image)}}" width="80px" height="80px">
                                                        @else
                                                        <img src="{{asset('uploads/placeholder/default.png')}}" height="80px" width="80px">
                                                        @endif
                                                    </td>
                                                    <td>{{ $items->date_added }}</td>
                                                    <td>{{ $items->date_modified }}</td>
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
                                                        <a class="btn btn-info" href="{{url('/admin/blogs_edit/' . $items->blogs_id)}}">
                                                            <i class="fa fa-pencil"></i> 
                                                        </a>

                                                        <a class="btn btn-secondary" href="{{url('/admin/blogs_update/' . $items->blogs_id . '/Active')}}">
                                                            <i class="fa fa-check"></i> 
                                                        </a>

                                                        <a class="btn btn-warning" href="{{url('/admin/blogs_update/' . $items->blogs_id . '/Inactive')}}">
                                                            <i class="fa fa-times"></i> 
                                                        </a>

                                                        <a class="btn btn-danger" href="{{url('/admin/blogs_delete/' . $items->blogs_id)}}">
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