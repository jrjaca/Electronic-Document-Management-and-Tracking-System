@extends('layouts.master')

@section('title') List of Deactivated Users @endsection

@section('css') 
    <!-- DataTables -->        
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') DEACTIVATED USERS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Deactivated Users @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->    

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;">Role</th>
                                    <th style="text-align:center;">ID Number</th>
                                    <th style="text-align:center;">Name</th>
                                    <th style="text-align:center;">Email</th>
                                    <th style="text-align:center;">Registration Status</th>
                                    <th style="text-align:center;">User Status</th>
                                    @if(Gate::check('user_view') || Gate::check('user_update') || Gate::check('user_activate') || Gate::check('user_disable') || Gate::check('user_enable'))
                                        <th style="text-align:center;">Action</th>   
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $row)    
                                <tr 
                                    @if( $row->deleted_at != null )
                                        class="table-warning"
                                    @elseif( $row->activated == 0 )   
                                        class="table-info"
                                    @endif
                                >
                                    {{-- <td style="text-align:center;">{{ $key +1 }}</td> --}}
                                    <td style="text-align:center;">
                                        @if($row->avatar === null)
                                            <img src="{{Gravatar::src($row->username)}}" style="border-radius: 50%" width="25px" height="25px"></td>
                                        @else 
                                            <img src="{{asset('images/profile/'.$row->avatar)}}" style="border-radius: 50%" width="25px" height="25px"></td>
                                        @endif  
                                    <td style="text-align:left;">{{ $row->role_title }}</td>
                                    <td style="text-align:center;">{{ $row->username }}</td>
                                    <td style="text-align:left;">{{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}</td>
                                    <td style="text-align:left;">{{ $row->email }}</td>
                                    <td style="text-align:center;">
                                        @if( $row->activated == 1 )
                                            <span class="badge badge-success">Activated</span>
                                        @else
                                            <span class="badge badge-danger">For activation</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if( $row->deleted_at == null )
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-danger">Disabled</span>
                                        @endif
                                    </td>   
                                @if(Gate::check('user_view') || Gate::check('user_update') || Gate::check('user_activate') || Gate::check('user_disable') || Gate::check('user_enable'))
                                    <td style="text-align:left;">
                                        @if( $row->activated == 1 ) {{-- confirmed by the administrator --}}                                            
                                            @can('user_view')    
                                                <a href="javascript:void(0)" onclick="viewUserDetails('{{Hasher::encode($row->id)}}')"> 
                                                    <span style="font-size: 1.2em; color: Dodgerblue;"> 
                                                        <i class="fa fa-search" title="Show {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }} details."></i>
                                                    </span>
                                                </a>&nbsp;
                                            @endcan
                                            @can('user_update')
                                                @if( $row->deleted_at == null )
                                                    <a href="javascript:void(0)" onclick="editDetails('{{Hasher::encode($row->id)}}')">
                                                        <span style="font-size: 1.2em; color: Dodgerblue;">  
                                                            <i class="fa fa-edit" title="Edit {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}"></i>
                                                        </span>
                                                    </a>&nbsp;  
                                                @endif    
                                            @endcan           
                                        @else {{-- not yet confirmed by the administrator --}}
                                            @can('user_activate')    
                                                <a href="javascript:void(0)" onclick="activateUser('{{Hasher::encode($row->id)}}')"> 
                                                    <span style="font-size: 1.2em; color: Dodgerblue;"> 
                                                        <i class="fas fa-user-check" title="Activate {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}"></i>
                                                    </span>
                                                </a>&nbsp;
                                            @endcan           
                                        @endif      
                                        
                                        @if( $row->deleted_at == null ) {{-- user is enabled --}}
                                            @can('user_disable')                                              
                                                <a href="{{ route('manage.user.disable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-enableSoftDelete">
                                                <span style="font-size: 1.2em; color: Red;">
                                                    <i class="fa fa-lock" title="Disable {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}"></i>
                                                </span></a>&nbsp;
                                            @endcan
                                        @else {{-- user is deactivated by the admin --}}
                                            @can('user_enable')
                                                <a href="{{ route('manage.user.enable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disableSoftDelete">
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-unlock" title="Enable {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}"></i>
                                                </span></a>
                                            @endcan    
                                        @endif
                                    </td>    
                                @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        
        <!-- View User Details Modal-->
        <div id="viewUserDetailsModal" class="modal fade viewUserDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewUserDetailsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewUserDetailsModalLabel">USER DETAILS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            
                            {{-- <p class="mb-1">User Name: <span class="text-primary" id="username"></span></p> --}}
                            <div class="table-responsive">
                                {{-- <table cellspacing="0" cellpadding="0" style="width:100%"> --}}
                                <table class="table mb-0 table-bordered" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%"></td>
                                            <td style="text-align:left; width:70%">
                                                <img src="" id="avatar_add" name="avatar_add" style="border-radius: 50%" width="60px" height="60px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Role:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="role_desc"></span></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">ID Number:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="username_v"></span></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Name:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="name_v"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Email:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="email_v"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Region:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="office_v"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Dept./Unit/Branch:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="department_v"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">LHIO/Section:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="section_v"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <p class="text-muted mb-0">$ 145 x 1</p>
                            <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                            <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                            <h6 class="m-0 text-right">Sub Total:</h6> --}}

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
        <!-- /View User Details Modal-->

        <!-- Edit User Details Modal -->
        <div id="editModal" class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">EDIT USER</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.user.update') }}" method="POST" class="needs-validation" novalidate>                       
                        @csrf
                        <div class="modal-body">
                            
                            <input type="hidden" id="user_id_e" name="user_id_e" readonly required/>
                            {{-- <div class="invalid-feedback">
                                'user_id' is required. Please report IT administrator.
                            </div>    --}}

                            {{-- <p class="mb-1">User Name: <span class="text-primary" id="username"></span></p> --}}
                            <div class="table-responsive">
                                {{-- <table cellspacing="0" cellpadding="0" style="width:100%"> --}}
                                <table class="table mb-0 table-bordered" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Role:</td>
                                            <td style="text-align:left; width:70%">                                  
                                                <select class="custom-select" name="role_e" required autofocus></select>  
                                                <div class="invalid-feedback">
                                                    Please select user role.
                                                </div>                    
                                                {{-- @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror                       --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%"></td>
                                            <td style="text-align:left; width:70%">
                                                <img src="" id="avatar_edit" name="avatar_edit" style="border-radius: 50%" width="60px" height="60px">
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">ID Number:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="username_e"></span></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Name:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="name_e"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Email:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="email_e"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Region:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="office_e"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Dept./Unit/Branch:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="department_e"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">LHIO/Section:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="section_e"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <p class="text-muted mb-0">$ 145 x 1</p>
                            <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                            <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                            <h6 class="m-0 text-right">Sub Total:</h6> --}}

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Edit User Details Modal -->

        <!-- Activation Modal -->
        <div id="activationModal" class="modal fade activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateRoleDetailsLabel">USER ACTIVATION</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.user.activate') }}" method="POST" class="needs-validation" novalidate>                       
                        @csrf
                        <div class="modal-body">
                            
                            <input type="hidden" id="user_id" name="user_id" readonly required/>
                            {{-- <div class="invalid-feedback">
                                'user_id' is required. Please report IT administrator.
                            </div>    --}}

                            {{-- <p class="mb-1">User Name: <span class="text-primary" id="username"></span></p> --}}
                            <div class="table-responsive">
                                {{-- <table cellspacing="0" cellpadding="0" style="width:100%"> --}}
                                <table class="table mb-0 table-bordered" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Role:</td>
                                            <td style="text-align:left; width:70%">                                  
                                                <select class="custom-select" name="role" required autofocus></select>  
                                                <div class="invalid-feedback">
                                                    Please select user role.
                                                </div>                    
                                                {{-- @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror                       --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">ID Number:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="username"></span></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Name:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="name"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Email:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="email"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Region:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="office"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">Dept./Unit/Branch:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="department"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:right; width:30%">LHIO/Section:</td>
                                            <td style="text-align:left; width:70%"><span class="text-primary" id="section"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <p class="text-muted mb-0">$ 145 x 1</p>
                            <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                            <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                            <h6 class="m-0 text-right">Sub Total:</h6> --}}

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Activate</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Activation Modal -->

@endsection

@section('script')

        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

@endsection

@section('script-bottom')

    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 
    
    <!-- View User Details -->
    <script>        
        function viewUserDetails(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/user/details/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //alert(JSON.stringify(result.enabled_roles));
                    //alert(result.user_details.username);
                    //------ result[0] = Get the first only ---------//
                    
                    var role_desc = "";
                    $.each(result.enabled_roles, function(key, value){
                        if (result.user_details.role_id == value.id) { role_desc = value.title; }
                    });

                    //avatar
                    var url = "";
                    if (result.user_details.avatar === null){
                        // GRAVATAR NOT WORKING
                    } else { url = '{{ asset('images/profile/') }}'+'/'+result.user_details.avatar; }
                    document.getElementById("avatar_add").src = url;

                    var name =  result.user_details.last_name+", "+result.user_details.first_name+" "+result.user_details.middle_name+" "+result.user_details.suffix_name;
                    $('#role_desc').html(role_desc);
                    $('#username_v').html(result.user_details.username);
                    $('#name_v').html(name);
                    $('#email_v').html(result.user_details.email);
                    $('#office_v').html(result.user_details.office_title);
                    $('#department_v').html(result.user_details.department_title);
                    $('#section_v').html(result.user_details.section_title);
                    
                    // Display Modal
                    $('#viewUserDetailsModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /View User Details -->

    <!-- Edit User Details -->
    <script>        
        function editDetails(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/user/details/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //alert(JSON.stringify(result.enabled_roles));
                    //alert(result.user_details.username);
                    //------ result[0] = Get the first only ---------//
                    
                    //avatar
                    var url = "";
                    if (result.user_details.avatar === null){
                        // GRAVATAR NOT WORKING
                    } else { url = '{{ asset('images/profile/') }}'+'/'+result.user_details.avatar; }
                    document.getElementById("avatar_edit").src = url;
                    
                    $('#user_id_e').val(result.user_details.id);

                    $('select[name="role_e"]').empty();
                    $('select[name="role_e"]').append('<option value="">Select user role</option>');
                    $.each(result.enabled_roles, function(key, value){
                        if (result.user_details.role_id == value.id) { //selected
                            $('select[name="role_e"]').append('<option value="'+value.id+'" selected>'+value.title+'</option>');
                        } else { 
                            $('select[name="role_e"]').append('<option value="'+value.id+'">'+value.title+'</option>');
                        }                        
                    });

                    var name =  result.user_details.last_name+", "+result.user_details.first_name+" "+result.user_details.middle_name+" "+result.user_details.suffix_name;
                    $('#username_e').html(result.user_details.username);
                    $('#name_e').html(name);
                    $('#email_e').html(result.user_details.email);
                    $('#office_e').html(result.user_details.office_title);
                    $('#department_e').html(result.user_details.department_title);
                    $('#section_e').html(result.user_details.section_title);
                    
                    // Display Modal
                    $('#editModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Edit User Details -->

    <!-- Activation -->
    <script>        
        function activateUser(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/user/details/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //alert(JSON.stringify(result.enabled_roles));
                    //alert(result.user_details.username);
                    //------ result[0] = Get the first only ---------//
                    
                    $('#user_id').val(result.user_details.id);

                    $('select[name="role"]').empty();
                    $('select[name="role"]').append('<option value="">Select user role</option>');
                    $.each(result.enabled_roles, function(key, value){
                        $('select[name="role"]').append('<option value="'+value.id+'">'+value.title+'</option>');
                    });

                    var name =  result.user_details.last_name+", "+result.user_details.first_name+" "+result.user_details.middle_name+" "+result.user_details.suffix_name;
                    $('#username').html(result.user_details.username);
                    $('#name').html(name);
                    $('#email').html(result.user_details.email);
                    $('#office').html(result.user_details.office_title);
                    $('#department').html(result.user_details.department_title);
                    $('#section').html(result.user_details.section_title);
                    
                    // Display Modal
                    $('#activationModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Activation -->

    <!-- sweetalert -->
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script> --}}
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

    <script>
        $(document).on("click", "#sa-custom-enableSoftDelete", function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            swal({
                    title: "Are you sure want to disable?",
                    text: "This may restored again.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                            window.location.href = link;
                    } /*else {
                            swal("Safe Data!");
                    }*/
            });
        });
    </script>
    
    <script>
        $(document).on("click", "#sa-custom-disableSoftDelete", function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            swal({
                    title: "Are you sure want to enable?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                            window.location.href = link;
                    } /*else {
                            swal("Safe Data!");
                    }*/
            });
        });
    </script>

@endsection