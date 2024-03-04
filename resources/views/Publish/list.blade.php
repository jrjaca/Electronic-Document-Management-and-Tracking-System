@extends('layouts.master')

@section('title') List of Published Documents @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') PUBLISHED DOCUMENTS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Published Documents @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Document ID</th> <!--Published from submitted docs-->
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Attachment Name</th>
                                    {{-- <th>Published To</th> --}}
                                    <th>Date and Time Published</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($published_docs as $key => $row)  
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td>{{ $row->document_id }}</a></td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->document_type_desc }}</td>     
                                    <td><a href="{{ asset('').$row->path }}" target="_blank">{{ $row->attachment_name }}</a></td>
                                    
                                    {{-- @php
                                        if ($row->office_id != null || $row->department_id != null ){
                                            if ($row->department_id != null){
                                                $publishedTo = $row->office_desc.' / '.$row->department_desc;
                                            } else {
                                                $publishedTo = $row->office_desc;
                                            }
                                        } else {
                                            $publishedTo = 'All';
                                        }                                        
                                    @endphp

                                    <td>{{ $publishedTo }}</td> --}}
                                    <td>{{ date('M. d Y, h:i A', strtotime($row->published_at)) }}</td>   
                                    <td>  
                                        @if(Gate::check('view_publish_document'))
                                            <a href="javascript:void(0)" onclick="showDocumentFullDetails('{{ Hasher::encode($row->published_id) }}')">
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-search" title="Details"></i>
                                                </span></a>&nbsp;
                                        @endif
                                        @if(Gate::check('update_publish_document'))
                                            <a href="{{ route('publish.edit', ['id' => Hasher::encode($row->published_id)]) }}" > 
                                                <span style="font-size: 1.2em; color: Blue;">
                                                    <i class="fa fa-edit" title="Edit {{ $row->title }}."></i>
                                                </span></a>&nbsp;
                                        @endif
                                        @if(Gate::check('unpublish_document'))
                                            <a href="{{ route('publish.disable', ['id' => Hasher::encode($row->published_id)]) }}" id="sa-custom-delete">
                                                <span style="font-size: 1.2em; color: Red;">
                                                    <i class="fa fa-lock" title="Unpublish/disable {{ $row->title }}."></i>
                                                </span></a>&nbsp;
                                        @endif  
                                            <a href="{{ route('downloadfile', ['id' => Hasher::encode($row->published_id)]) }}">
                                                <span style="font-size: 1.2em; color: black;">
                                                    <i class="fas fa-download" title="Download {{ $row->attachment_name }}"></i>
                                                </span></a>    
                                    </td>    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

        <!-- Details Modal -->
        <div id="documentFullDetails" class="modal fade documentFullDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentFullDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="documentFullDetailsModalLabel">Published Document Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1">Document ID: <span class="text-primary" id="document_id"></span></p>
                        <p class="mb-1">Document Name: <span class="text-primary" id="document_name"></span></p>
                        <p class="mb-1">Document Type: <span class="text-primary" id="document_type_desc"></span></p>
                        <p class="mb-1">Remarks: <span class="text-primary" id="remarks"></span></p>
                        {{-- <p class="mb-1">Attachment:<br/>
                            <a href="#" id="path" target="_blank"><span class="text-primary" id="attachment_name"></span></a></p> --}}
                        <hr>
                        <p class="mb-1">Published File (Click to view): <a href="#" id="path" target="_blank"><span class="text-primary" id="attachment_name"></span></a></p>
                        <p class="mb-1">Published To: <span class="text-primary" id="published_to"></span></p>       
                        {{-- <p class="mb-1">Published To:<div style="overflow-wrap: break-word;"><span class="text-primary" id="published_to"></span></div></p>                        --}}
                        <hr>
                        <p class="mb-1">Created By (Date): 
                                <span class="text-primary" id="created_by"></span> (<span class="text-primary" id="created_at"></span>)</p>
                        <p class="mb-1">Published By (Date): 
                                <span class="text-primary" id="published_by"></span> (<span class="text-primary" id="published_at"></span>)</p>
                        <p class="mb-1">Updated By (Date): 
                            <span class="text-primary" id="edited_by"></span> (<span class="text-primary" id="updated_at"></span>)</p>
                        <p class="mb-1">Deleted By (Date): 
                            <span class="text-primary" id="deleted_by"></span> (<span class="text-primary" id="deleted_at"></span>)</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Details Modal -->
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
    {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
    <!-- Plugins js -->
    {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

<!-- sweetalert -->
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

        
        <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>

        <script>

            // <!--details -->
            function showDocumentFullDetails(hashedPubId){
                spinner('Loading for details...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('publish/details/') }}"+"/"+hashedPubId,
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        //alert(JSON.stringify(result));
                        //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'

                        $('#document_id').html(result[0]['document_id']);
                        $('#document_name').html(result[0]['title']);
                        $('#remarks').html(result[0]['remarks']);
                        $('#document_type_desc').html(result[0]['document_type_desc']);

                        $('#attachment_name').html(result[0]['attachment_name']);              
                        var pathUrl = '{{ asset('') }}'+result[0]['path']; 
                        $("#path").attr("href", pathUrl);

                        $('#created_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']);
                        $('#published_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']); //same who created
                        editedBy = result[0]['ue_last_name']+', '+result[0]['ue_first_name']+' '+result[0]['ue_middle_name']+' '+result[0]['ue_suffix_name'];
                        deletedBy = result[0]['ud_last_name']+', '+result[0]['ud_first_name']+' '+result[0]['ud_middle_name']+' '+result[0]['ud_suffix_name'];
                        if (result[0]['ue_last_name'] == null) { editedBy = ""; }
                        if (result[0]['ud_last_name'] == null) { deletedBy = ""; }
                        $('#edited_by').html(editedBy);
                        $('#deleted_by').html(deletedBy);

                        //published to
                        if (result[0]['office_id'] != null || result[0]['department_id'] != null ){
                            if (result[0]['department_id'] != null){
                                $publishedTo = result[0]['office_desc']+' / '+result[0]['department_desc'];
                            } else {
                                $publishedTo = result[0]['office_desc'];
                            }
                        } else {  $publishedTo = 'All'; }     
                        $('#published_to').html($publishedTo);

                        var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
                        var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
                        var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
                        var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
                        $('#created_at').html(createdAt);
                        $('#published_at').html(publishedAt);
                        
                        if (updatedAt == "January 01, 1970") { updatedAt = ""; }
                        if (deletedAt == "January 01, 1970") { deletedAt = ""; }
                        $('#updated_at').html(updatedAt);
                        $('#deleted_at').html(deletedAt);
                        //is_published
                        // if (result[0]['email_notification'] == true){ 
                        //     $('#email_notification').html('Yes');
                        // } else {  $('#email_notification').html('No'); }

                        // Display Modal
                        $('#documentFullDetails').modal('show');
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    },
                });
            }
            // <!-- /details -->
        </script>

        <!-- delete draft document -->
        <script>
            $(document).on("click", "#sa-custom-delete", function(e){
                e.preventDefault();
                var link = $(this).attr("href");
                swal({
                        title: "Are you sure want to unpublish and disable this document?",
                        text: "This can be restore.",
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
        <!-- //delete draft document -->
        
@endsection