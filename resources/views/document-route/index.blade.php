@extends('layouts.master')

@section('title') Routing Documents @endsection

@section('content')

    @component('common-components.breadcrumb')
    @slot('title') ROUTING DOCUMENT @endslot
    @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
    @slot('li_2') Route document @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
    
    <!-- Search and route document -->
    @if(Auth::check())
        <div class="row"> 
            <div class="col-lg-12">
                <div class="card"> 
                    <div class="card-body">    
                        <br /> 
                        @if(Gate::check('generate_barcode'))
                            <div class="form-group row mb-3">
                                <label for="horizontal-remarks-input" class="col-sm-1 col-form-label">&nbsp;</label>
                                <div class="col-sm-3">
                                    <div class="input-group mb-3">
                                        <button type="submit" class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light"
                                                onclick="location.href='{{ route('route.create') }}'">
                                            <span style="font-size: 1.1em; color: white;">Add document for routing &nbsp;&nbsp;
                                                <i class="fas fa-route" title="Routes"></i>&nbsp;
                                            </span></i></button>
                                    </div>                                        
                                </div>
                            </div>
                        @endif 

                        @if(Gate::check('track_document'))
                            <form id="my_form" action="{{ route('route.search') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row mb-3">
                                    <label for="horizontal-remarks-input" class="col-sm-1 col-form-label">Search</label>
                                    <div class="col-sm-3">
                                        <div class="input-group mb-3">
                                            <input name="searching_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number" autofocus>
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fas fa-search-location" title="Search document"></i>
                                                </button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </form>
                        @endif    
                    
                        @if(Gate::check('received_document'))
                            <form id="my_form" action="{{ route('route.receiving') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row mb-3">
                                    <label for="horizontal-remarks-input" class="col-sm-1 col-form-label">Received</label>
                                    <div class="col-sm-3">
                                        <div class="input-group mb-3">
                                            <input name="receiving_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fas fa-file-import" title="Receive document"></i>
                                                </button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </form>
                        @endif    

                        @if(Gate::check('released_document'))
                            <form id="my_form" action="{{ route('route.create.released') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row mb-3">
                                    <label for="horizontal-remarks-input" class="col-sm-1 col-form-label">Released</label>
                                    <div class="col-sm-3">
                                        <div class="input-group mb-3">
                                            <input name="releasing_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fas fa-file-export" title="Release document"></i>
                                                </button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </form>
                        @endif   
                        
                        @if(Gate::check('tag_as_terminal'))
                            <form id="my_form" action="{{ route('route.terminal.tagpost') }}" method="POST" class="needs-validation" novalidate>
                                @csrf 
                                <div class="form-group row mb-3">
                                    <label for="horizontal-remarks-input" class="col-sm-1 col-form-label">Tag as Terminal</label>
                                    <div class="col-sm-3">
                                        <div class="input-group mb-3">
                                            <input name="terminal_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="submit" >
                                                    <i class="fas fa-file-medical-alt" title="Release document"></i>
                                                </button>
                                                {{-- fas fa-dolly   fas fa-file-medical-alt --}}
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>                
        </div> <!-- end row -->
    @endif        

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
                    {{-- <p class="mb-1">Created By (Date): 
                            <span class="text-primary" id="created_by"></span> (<span class="text-primary" id="created_at"></span>)</p> --}}
                    <p class="mb-1">Published By (Date): 
                            <span class="text-primary" id="published_by"></span> (<span class="text-primary" id="published_at"></span>)</p>
                    {{-- <p class="mb-1">Updated By (Date): 
                        <span class="text-primary" id="edited_by"></span> (<span class="text-primary" id="updated_at"></span>)</p>
                    <p class="mb-1">Deleted By (Date): 
                        <span class="text-primary" id="deleted_by"></span> (<span class="text-primary" id="deleted_at"></span>)</p> --}}
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
        {{-- <!-- plugin js -->
        <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Calendar init -->
        <script src="{{ URL::asset('assets/js/pages/dashboard.init.js')}}"></script> --}}

        <!-- form mask -->
        <script src="{{ URL::asset('assets/libs/inputmask/inputmask.min.js')}}"></script>

        <!-- form mask init -->
        <script src="{{ URL::asset('assets/js/pages/form-mask.init.js')}}"></script>

@endsection

@section('script-bottom')
    <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>

    <script>

        // <!--details -->
        function showPublishedDocumentFullDetails(hashedPubId){
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

                    //var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
                    var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
                    //var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
                    //var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
                    //$('#created_at').html(createdAt);
                    $('#published_at').html(publishedAt);
                    
                    // if (updatedAt == "January 01, 1970") { updatedAt = ""; }
                    // if (deletedAt == "January 01, 1970") { deletedAt = ""; }
                    // $('#updated_at').html(updatedAt);
                    // $('#deleted_at').html(deletedAt);
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

@endsection