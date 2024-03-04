@extends('layouts.master')

@section('title') List of Document Types @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') DOCUMENT TYPES @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Document Types @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @can('documenttype_store')
                            <div style="float: left;">
                                <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                    data-toggle="modal" data-target=".addModal"> Add New
                                </button>
                            </div><br/><br/><br/>
                        @endcan   

                        {{-- <img src="data:image/png;base64,{{ base64_encode($barcode) }}"> --}}

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    @if(Gate::check('documenttype_update') || Gate::check('documenttype_disable') || Gate::check('documenttype_enable'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctypes as $key => $row)  
                                <tr 
                                    @if( $row->deleted_at != null )
                                        class="table-warning"
                                    @endif
                                >
                                    <td>{{ $key +1 }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>
                                        @if( $row->deleted_at == null )
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                @if(Gate::check('documenttype_update') || Gate::check('documenttype_disable') || Gate::check('documenttype_enable'))
                                    <td>      

                                    @if( $row->deleted_at == null )
                                        @can('documenttype_update')
                                            <a href="javascript:void(0)" onclick="showDocTypeDetails('{{Hasher::encode($row->id)}}');"> 
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-edit" title="Edit {{ $row->title }}"></i>
                                                </span>
                                            </a>&ensp;
                                        @endcan     
                                        @can('documenttype_disable')
                                            <a href="{{ route('manage.doctypes.disable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-enableSoftDelete">
                                                <span style="font-size: 1.2em; color: Red;">
                                                    <i class="fa fa-lock" title="Disable {{ $row->title }}"></i>
                                                </span></a>&ensp;
                                        @endcan   
                                    @else
                                        @can('documenttype_enable')
                                            <a href="{{ route('manage.doctypes.enable', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disableSoftDelete">
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
            </div> <!-- end col -->
        </div> <!-- end row -->

        <!-- Add Modal -->
        <div class="modal fade addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">DOCUMENT TYPE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.doctypes.save') }}" method="POST" class="needs-validation" novalidate>                       
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Add modal -->

        <!-- Edit Modal-->
        <div id="editModal" class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">UPDATE DOCUMENT TYPE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="my_form" action="{{ route('manage.doctypes.update') }}" method="POST" class="needs-validation" novalidate>                       
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->

@endsection

@section('script')

        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

    <!-- Edit Permission -->
    <script>        
        function showDocTypeDetails(id){
            spinner('Loading...');
            $.ajax({
                type:"GET",
                url: "{{ url('document-type-management/doc-types/edit/') }}"+"/"+id,  
                dataType: "json",
                cache: false,
                success: function(result){
                    //alert(JSON.stringify(result));
                     //------ result[0] = Get the first only ---------//
                    var modal = $('#editModal');
                    modal.find('#hashid').val(id);    
                    modal.find('#title').val(result[0]['title']);    
                    modal.find('#description').val(result[0]['description']);                
                     // Display Modal
                    $('#editModal').modal('show');
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
            });
        }
    </script>
    <!-- /Edit Permission -->

@endsection


@section('script-bottom')

    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 

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