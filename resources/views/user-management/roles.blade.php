@extends('layouts.master')

@section('title') List of Roles @endsection

@section('content')

   @component('common-components.breadcrumb')
        @slot('title') ROLES @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Roles @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">                        
                        @can('role_store')
                            <div style="float: left;">
                                {{-- <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                    data-toggle="modal" data-target=".saveRoleModal">Add New</button> --}}                                
                                <button class="btn btn-outline-primary btn-sm waves-effect waves-light" onclick="addRole()">Add New</button>
                                <br/><br/>                            
                            </div>
                        @endcan   

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Slug Name</th>
                                        <th>Status</th>
                                        @if(Gate::check('role_view') || Gate::check('role_update') || Gate::check('role_disable') || Gate::check('role_enable'))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($roles as $key => $row)  
                                    <tr 
                                        @if( $row->deleted_at != null )
                                            class="table-warning"
                                        @endif
                                    >
                                        <td>{{ $key +1 }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td>{{ $row->description }}</td>
                                        <td>{{ $row->slug }}</td>
                                        <td>
                                            @if( $row->deleted_at == null )
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    @if(Gate::check('role_view') || Gate::check('role_update') || Gate::check('role_disable') || Gate::check('role_enable'))  
                                        <td>                                           
                                                {{-- <a href="{{ route('manage.roles.edit', ['id' => Hasher::encode($row->id)]) }}" >
                                                    <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-edit" title="Edit {{ $row->title }}"></i>
                                                    </span>
                                                </a> --}}
                                                @can('role_view')
                                                    <a href="javascript:void(0)" onclick="showPermissionsByRoleId('{{Hasher::encode($row->id)}}');"> 
                                                        <span style="font-size: 1.2em; color: Dodgerblue;">
                                                            <i class="fa fa-search" title="Show {{ $row->title }} permissions"></i>
                                                        </span>
                                                    </a>&nbsp;
                                                @endcan
                                            @if( $row->deleted_at == null )
                                                @can('role_update')
                                                    <a href="javascript:void(0)" onclick="editRoleDetails('{{Hasher::encode($row->id)}}');"> 
                                                        <span style="font-size: 1.2em; color: Dodgerblue;">
                                                            <i class="fa fa-edit" title="Edit {{ $row->title }}"></i>
                                                        </span>
                                                    </a>&nbsp;
                                                @endcan    
                                                @can('role_disable')
                                                    <a href="{{ route('manage.roles.disable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-enableSoftDelete">
                                                        <span style="font-size: 1.2em; color: Red;">
                                                            <i class="fa fa-lock" title="Disable {{ $row->title }}"></i>
                                                        </span></a>&nbsp;
                                                @endcan    
                                            @else
                                                @can('role_enable')
                                                    <a href="{{ route('manage.roles.enable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disableSoftDelete">
                                                        <span style="font-size: 1.2em; color: Dodgerblue;">
                                                            <i class="fa fa-unlock" title="Enable {{ $row->title }}"></i>
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
                </div>
            </div>
        </div>
        <!-- end row -->        

        <!-- Store Modal -->
        <div id="addRoleModal" class="modal fade addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleLabel">ADD NEW ROLE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.roles.save') }}" method="POST" class="needs-validation" novalidate>                       
                        @csrf
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title"  placeholder="Title" required autofocus>
                                    <div class="invalid-feedback">
                                        Please provide title.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">           
                                <label for="permisions">Select Permissions</label>                         
                                <div id="checkbox_main_div"></div>
                            </div>    

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Store modal -->

        <!-- Update Modal-->
        <div id="updateRoleDetailsModal" class="modal fade updateRoleDetailsModal" tabindex="-1" role="dialog" aria-labelledby="updateRoleDetailsLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateRoleDetailsLabel">UPDATE ROLE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.roles.update') }}" method="POST" class="needs-validation" novalidate>                       
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" id="hashid" name="hashid" readonly required>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" required autofocus>
                                    <div class="invalid-feedback">
                                        Please provide title.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">           
                                <label for="permisions">Select Permissions</label>                         
                                <div id="checkbox_main_div_2"></div>
                            </div>   
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Update Modal -->

        <!-- Show Permissions Modal -->
        <div id="showPermissionsModal" class="modal fade showPermissionsModal" tabindex="-1" role="dialog" aria-labelledby="showPermissionsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showPermissionsModalLabel"><div id="header_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">                            
                            <div id="permissionsContainer">                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Show Permissions Modal -->
@endsection

@section('script-bottom')

    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 

    <!-- Add Role, Populate Permissions -->
    <script>        
        function addRole(){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/roles/create') }}",  
                dataType: "json",
                cache: false,
                success: function(result){
                    //var count = result.length;//Object.keys(result).length;
                    //var modal = $('#addRoleModal');  
                    //modal.find('#title').val(result[0]['title']);    
                    //id, title, slug, description

                    //Clear Main div first
                    $("#checkbox_main_div").html("");
                    $.each(result, function(i, item) {
                        //create the checkbox_div+id  first
                        $('#checkbox_main_div') 
                            .append("<div class='custom-control custom-switch' dir='ltr'><div id='checkbox_div"+result[i].slug+"'></div></div>")

                            //then create the checkbox
                            $("#checkbox_div"+result[i].slug) 
                                //use [] for foreach in saving at model
                                .append("<input class='custom-control-input' type='checkbox' id='"+result[i].slug+"' name='chkbx_roles[]' value='"+result[i].id+"'>")
                                .append("&nbsp;&nbsp;<label for='"+result[i].slug+"' class='custom-control-label' title='"+result[i].description+"'>"+result[i].title+"</label>")
                                .append('<br>');
                    });

                    $('#addRoleModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Add Role -->

    <!-- Show Permissions -->
    <script>        
        function showPermissionsByRoleId(hashroleid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/permissions-role/show/') }}"+"/"+hashroleid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //------ result[0] = Get the first only ---------//
                    /*---- table ----*/
                    var tableBody="<table class='table table-centered'>";
                        tableBody+="<thead><tr>";
                            tableBody+="<th scope='col'>Title</th>";
                            tableBody+="<th scope='col'>Description</th>";
                        tableBody+="</tr></thead><tbody>";
                        for(var i=0; i < result.length; i++){
                            tableBody+="<tr>";
                                tableBody+="<td>"+result[i].title+"</td>";
                                tableBody+="<td>"+result[i].description+"</td>";
                            tableBody+="</tr>";
                        }
                        tableBody+='</tbody></table>';
                        $('#permissionsContainer').html(tableBody);
                        $('#header_title').html("LIST OF PERMISSIONS");    
                        /*---- /table ----*/      

                    if (result.length <= 0){
                        $('#permissionsContainer').html("NO PERMISSION FOUND.");
                        $('#header_title').html("");
                    }    
                    
                    // Display Modal
                    $('#showPermissionsModal').modal('show');
                },
                error: function (request, status, error) {
                     //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Show Permissions -->

    <!-- Update Role -->
    <script>        
        function editRoleDetails(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('user-management/roles/edit/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));

                    // alert(result.role[0].title);
                    // alert(result.permissions[0].title);
                    // alert(result.selected_permissions[0].title);
                     //------ result[0] = Get the first only ---------//

                    var modal = $('#updateRoleDetailsModal');
                    modal.find('#hashid').val(hashid);    
                    modal.find('#title').val(result.role[0].title);    
                    modal.find('#description').val(result.role[0].description);       

                    //Permissions checkbox
                    //Clear Main div first
                    $("#checkbox_main_div_2").html("");
                    var isChecked; 
                    $.each(result.permissions, function(i, item) {
                        //set checked if exist in permission_role
                        isChecked = "";
                        for(var j=0; j < result.selected_permissions.length; j++){                            
                            if (result.selected_permissions[j].id == result.permissions[i].id) { isChecked = " checked "; }
                        }

                        //create the checkbox_div+id first
                        $('#checkbox_main_div_2') 
                            .append("<div class='custom-control custom-switch' dir='ltr'><div id='checkbox_div2"+result.permissions[i].slug+"'></div></div>")

                        //then create the checkbox
                        $("#checkbox_div2"+result.permissions[i].slug) 
                            //use [] for foreach in saving at model                  
                            .append("<input class='custom-control-input' type='checkbox' id='"+result.permissions[i].slug+"' name='chkbx_roles[]' value='"+result.permissions[i].id+"' "+isChecked+">")
                            .append("&nbsp;&nbsp;<label for='"+result.permissions[i].slug+"' class='custom-control-label' title='"+result.permissions[i].description+"'>"+result.permissions[i].title+"</label>")
                            .append('<br>');
                    });

                     // Display Modal
                    $('#updateRoleDetailsModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Update Role -->

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
