@extends('layouts.master')

@section('title') List of Offices @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') OFFICES @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Offices @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @can('office_store')
                            <div style="float: left;">
                                <button class="btn btn-outline-primary btn-sm waves-effect waves-light" onclick="addOffice()">Add New</button>
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

                                @foreach($offices as $key => $row)  
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
                                            <a href="javascript:void(0)" onclick="showDepartmentsByOfficeId('{{Hasher::encode($row->id)}}');"> 
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-search" title="Show {{ $row->title }} departments"></i>
                                                </span>
                                            </a>&nbsp;

                                        @if( $row->deleted_at == null )
                                            @can('office_update')
                                                <a href="javascript:void(0)" onclick="editOfficeDetails('{{Hasher::encode($row->id)}}');"> 
                                                    <span style="font-size: 1.2em; color: Dodgerblue;">
                                                        <i class="fa fa-edit" title="Edit {{ $row->title }}"></i>
                                                    </span>
                                                </a>&nbsp;
                                            @endcan    
                                            @can('office_disable')
                                                <a href="{{ route('manage.offices.disable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-enableSoftDelete">
                                                    <span style="font-size: 1.2em; color: Red;">
                                                        <i class="fa fa-lock" title="Disable {{ $row->title }}"></i>
                                                    </span></a>&nbsp;
                                            @endcan    
                                        @else
                                            @can('office_enable')
                                                <a href="{{ route('manage.offices.enable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disableSoftDelete">
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
                        <h5 class="modal-title" id="addModalLabel">ADD NEW OFFICE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.offices.save') }}" method="POST" class="needs-validation" novalidate>                       
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
                                <label for="departments">Select Departments</label>                         
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
        <div id="updateModal" class="modal fade updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">UPDATE OFFICE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.offices.update') }}" method="POST" class="needs-validation" novalidate>                       
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
                                <label for="departments">Select Departments</label>                         
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

        <!-- Show Departments Modal -->
        <div id="showDepartmentsModal" class="modal fade showDepartmentsModal" tabindex="-1" role="dialog" aria-labelledby="showDepartmentsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showDepartmentsModalLabel"><div id="header_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">                            
                            <div id="departmentsContainer">                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Show Departments Modal -->
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

    <!-- Add Office, Populate Departments -->
    <script>        
        function addOffice(){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/offices/create') }}",  
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
                                .append("<input class='custom-control-input' type='checkbox' id='"+result[i].id+"' name='chkbx_offices[]' value='"+result[i].id+"'>")
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
    <!-- /Add Office -->

    <!-- Show Departments -->
    <script>        
        function showDepartmentsByOfficeId(hashdeptid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/departments-office/show/') }}"+"/"+hashdeptid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                    //------ result[0] = Get the first only ---------//
                    /*---- table ----*/
                    // table-nowrap
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
                        $('#departmentsContainer').html(tableBody);
                        $('#header_title').html("LIST OF DEPARTMENTS");    
                        /*---- /table ----*/      

                    if (result.length <= 0){
                        $('#departmentsContainer').html("NO DEPARTMENT FOUND.");
                        $('#header_title').html("");
                    }    
                    
                    // Display Modal
                    $('#showDepartmentsModal').modal('show');
                },
                error: function (request, status, error) {
                     //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Show Departments -->

    <!-- Update Office -->
    <script>        
        function editOfficeDetails(hashid){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('location-management/offices/edit/') }}"+"/"+hashid,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));

                    // alert(result.office[0].title);
                    // alert(result.departments[0].title);
                    // alert(result.selected_departments[0].title);
                     //------ result[0] = Get the first only ---------//

                    var modal = $('#updateModal');
                    modal.find('#hashid').val(hashid);    
                    modal.find('#title').val(result.office[0].title);    
                    modal.find('#remarks').val(result.office[0].remarks);       

                    //Departments checkbox
                    //Clear Main div first
                    $("#checkbox_main_div_2").html("");
                    var isChecked; 
                    $.each(result.departments, function(i, item) {
                        //set checked if exist in department_office
                        isChecked = "";
                        for(var j=0; j < result.selected_departments.length; j++){                            
                            if (result.selected_departments[j].id == result.departments[i].id) { isChecked = " checked "; }
                        }

                        //create the checkbox_div+id first
                        $('#checkbox_main_div_2') 
                            .append("<div class='custom-control custom-switch' dir='ltr'><div id='checkbox_div2"+result.departments[i].id+"'></div></div>")

                        //then create the checkbox
                        $("#checkbox_div2"+result.departments[i].id) 
                            //use [] for foreach in saving at model                  
                            .append("<input class='custom-control-input' type='checkbox' id='"+result.departments[i].id+"' name='chkbx_offices[]' value='"+result.departments[i].id+"' "+isChecked+">")
                            .append("&nbsp;&nbsp;<label for='"+result.departments[i].id+"' class='custom-control-label' title='"+result.departments[i].remarks+"'>"+result.departments[i].title+" ("+result.departments[i].remarks+")"+"</label>")
                            .append('<br>');
                    });

                     // Display Modal
                    $('#updateModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Update Office -->

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
