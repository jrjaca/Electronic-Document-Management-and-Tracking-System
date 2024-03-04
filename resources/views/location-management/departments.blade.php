@extends('layouts.master')

@section('title') List of Departments @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') DEPARTMENT @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Departments @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @can('department_store')
                            <div style="float: left;">                            
                                <button class="btn btn-outline-primary btn-sm waves-effect waves-light" onclick="addDepartment()">Add New</button>
                                <br/><br/>
                            </div>
                        @endcan    

                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($departments as $key => $row)  
                                    <tr 
                                        @if( $row->deleted_at != null )
                                            class="table-warning"
                                        @endif
                                    >
                                        <td>{{ $key +1 }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td>{{ $row->remarks }}</td>
                                        <td>
                                            @if( $row->deleted_at == null )
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>               
                                            <a href="javascript:void(0)" onclick="showSectionsByDepartmentId('{{Hasher::encode($row->id)}}');"> 
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-search" title="Show {{ $row->title }} sctions"></i>
                                                </span>
                                            </a>&nbsp;

                                        @if( $row->deleted_at == null )
                                            @can('department_update')
                                                <a href="javascript:void(0)" onclick="editDepartmentDetails('{{Hasher::encode($row->id)}}');"> 
                                                    <span style="font-size: 1.2em; color: Dodgerblue;">
                                                        <i class="fa fa-edit" title="Edit {{ $row->title }}"></i>
                                                    </span>
                                                </a>&nbsp;
                                            @endcan    
                                            @can('department_disable')
                                                <a href="{{ route('manage.departments.disable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-enableSoftDelete">
                                                    <span style="font-size: 1.2em; color: Red;">
                                                        <i class="fa fa-lock" title="Disable {{ $row->title }}"></i>
                                                    </span></a>&nbsp;
                                            @endcan    
                                        @else
                                            @can('department_enable')
                                                <a href="{{ route('manage.departments.enable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disableSoftDelete">
                                                    <span style="font-size: 1.2em; color: Dodgerblue;">
                                                        <i class="fa fa-unlock" title="Enable {{ $row->title }}"></i>
                                                    </span></a>
                                            @endcan    
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
        <!-- end row -->        

        <!-- Store Modal -->
        <div id="addModal" class="modal fade addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">  
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">ADD NEW DEPARTMENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.departments.save') }}" method="POST" class="needs-validation" novalidate>                       
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
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control" name="remarks" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">           
                                <label for="sections">Select Sections</label>                         
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
        <div id="updateDepartmentDetailsModal" class="modal fade updateDepartmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="updateDepartmentDetailsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateDepartmentDetailsModalLabel">UPDATE DEPARTMENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.departments.update') }}" method="POST" class="needs-validation" novalidate>                       
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
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">           
                                <label for="sections">Select Sections</label>                         
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

        <!-- Show Sections Modal -->
        <div id="showSectionsModal" class="modal fade showSectionsModal" tabindex="-1" role="dialog" aria-labelledby="showSectionsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showSectionsModalLabel"><div id="header_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">                            
                            <div id="sectionsContainer">                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Show Sections Modal -->
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

    <!-- Add Role, Populate Permissions -->
    <script>        
        function addDepartment(){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/departments/create') }}",  
                dataType: "json",
                cache: false,
                success: function(result){ 
                    //var count = result.length;//Object.keys(result).length;
                    //var modal = $('#addModal');  
                    //modal.find('#title').val(result[0]['title']);  

                    //Clear Main div first
                    $("#checkbox_main_div").html("");
                    $.each(result, function(i, item) {
                        //create the checkbox_div+id  first
                        $('#checkbox_main_div') 
                            .append("<div class='custom-control custom-switch' dir='ltr'><div id='checkbox_div"+result[i].id+"'></div></div>")

                            //then create the checkbox
                            $("#checkbox_div"+result[i].id) 
                                //use [] for foreach in saving at model
                                .append("<input class='custom-control-input' type='checkbox' id='"+result[i].id+"' name='chkbx_departments[]' value='"+result[i].id+"'>")
                                .append("&nbsp;&nbsp;<label for='"+result[i].id+"' class='custom-control-label' title='"+result[i].remarks+"'>"+result[i].title+" ("+result[i].remarks+")"+"</label>")
                                .append('<br>');
                    });

                    $('#addModal').modal('show');
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
        function showSectionsByDepartmentId(hashdeptid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/sections-department/show/') }}"+"/"+hashdeptid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //------ result[0] = Get the first only ---------//
                    /*---- table ----*/
                    var tableBody="<table class='table table-centered'>";
                        tableBody+="<thead><tr>";
                            tableBody+="<th scope='col'>Title</th>";
                            tableBody+="<th scope='col'>Remarks</th>";
                        tableBody+="</tr></thead><tbody>";
                        for(var i=0; i < result.length; i++){
                            tableBody+="<tr>";
                                tableBody+="<td>"+result[i].title+"</td>";
                                tableBody+="<td>"+result[i].remarks+"</td>";
                            tableBody+="</tr>";
                        }
                        tableBody+='</tbody></table>';
                        $('#sectionsContainer').html(tableBody);
                        $('#header_title').html("LIST OF SECTION");    
                        /*---- /table ----*/      

                    if (result.length <= 0){
                        $('#sectionsContainer').html("NO SECTION FOUND.");
                        $('#header_title').html("");
                    }    
                    
                    // Display Modal
                    $('#showSectionsModal').modal('show');
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
        function editDepartmentDetails(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/departments/edit/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));

                    // alert(result.role[0].title);
                    // alert(result.permissions[0].title);
                    // alert(result.selected_permissions[0].title);
                     //------ result[0] = Get the first only ---------//

                    var modal = $('#updateDepartmentDetailsModal');
                    modal.find('#hashid').val(hashid);    
                    modal.find('#title').val(result.department[0].title);    
                    modal.find('#remarks').val(result.department[0].remarks);       

                    //Sections checkbox
                    //Clear Main div first
                    $("#checkbox_main_div_2").html("");
                    var isChecked; 
                    $.each(result.sections, function(i, item) {
                        //set checked if exist in section_department
                        isChecked = "";
                        for(var j=0; j < result.selected_sections.length; j++){                            
                            if (result.selected_sections[j].id == result.sections[i].id) { isChecked = " checked "; }
                        }

                        //create the checkbox_div+id first
                        $('#checkbox_main_div_2') 
                            .append("<div class='custom-control custom-switch' dir='ltr'><div id='checkbox_div2"+result.sections[i].id+"'></div></div>")

                        //then create the checkbox
                        $("#checkbox_div2"+result.sections[i].id) 
                            //use [] for foreach in saving at model                  
                            .append("<input class='custom-control-input' type='checkbox' id='"+result.sections[i].id+"' name='chkbx_departments[]' value='"+result.sections[i].id+"' "+isChecked+">")
                            .append("&nbsp;&nbsp;<label for='"+result.sections[i].id+"' class='custom-control-label' title='"+result.sections[i].remarks+"'>"+result.sections[i].title+" ("+result.sections[i].remarks+")"+"</label>")
                            .append('<br>');
                    });

                     // Display Modal
                    $('#updateDepartmentDetailsModal').modal('show');
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
