@extends('layouts.master')

@section('title') List of Received Documents @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') RECEIVED DOCUMENTS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Received Documents @endslot
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
                                    <th>Barcode</th> <!--allow to generate barcode here-->
                                    <th>Sender</th>
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>For</th>
                                    <th>Date and Time Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($received_docs as $key => $row)  
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td><a href="javascript:void(0)" onclick="reGenerateBarcode('{{ $row->barcode }}', '{{ $row->doc_title }}');" title="Re-generate barcode"> 
                                        {{ $row->barcode }}</a></td>
                                    <td>{{ $row->s_last_name.', '.$row->s_first_name.' '.$row->s_middle_name.' '.$row->s_suffix_name }}</td>
                                    <td>{{ $row->doc_title }}</td>
                                    <td>{{ $row->doc_type_desc }}</td>       
                                    <td>{{ $row->doc_action_desc }}</td>
                                    <td>{{ date('M. d, Y. h:i A', strtotime($row->sl_submitted_at)) }}</td> 
                                    <td>     
                                        @if(Gate::check('create_document'))                                      
                                            <a href="{{ route('document.reply', ['docid' => Hasher::encode($row->document_id), 'senderid' => Hasher::encode($row->sender_id)]) }}">
                                                <span style="font-size: 1.2em; color: darkblue;">
                                                    <i class="fa fa-reply" title="Reply"></i>
                                                </span></a>&nbsp;
                                        @endif     
                                        
                                        @if(Gate::check('search_document'))   
                                            <a href="javascript:void(0)" onclick="showDocumentBasicDetails('{{ Hasher::encode($row->document_id) }}')">
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-search" title="Details"></i>
                                                </span></a>&nbsp;

                                            <a href="javascript:void(0)" onclick="showDocumentRoutes('{{ Hasher::encode($row->document_id) }}')">
                                                <span style="font-size: 1.2em; color: green;">
                                                    <i class="fas fa-route" title="Routes"></i>
                                                </span></a>&nbsp;    

                                            <a href="javascript:void(0)" onclick="showSeenLogs('{{ Hasher::encode($row->document_id) }}')">
                                                <span style="font-size: 1.2em; color: gray;">
                                                    <i class="fas fa-user-tag" title="Recipients"></i>
                                                </span></a>&nbsp;
                                        @endif     

                                        @if(Gate::check('publish_document'))    
                                            <a href="{{ route('publish.routeddoc.create', ['id' => Hasher::encode($row->document_id)]) }}">
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fas fa-cloud-upload-alt" title="Publish {{ $row->doc_title }}"></i>
                                                </span></a>
                                        @endif     
                                            
                                        {{-- <a href="{{ route('document.draft.delete', ['id' => Hasher::encode($row->document_id)]) }}" id="sa-custom-delete">
                                            <span style="font-size: 1.2em; color: Red;">
                                                <i class="far fa-trash-alt" title="Delete {{ $row->title }}."></i>
                                            </span></a>   --}}
                                    </td>    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

        <!-- Re-generate barcode-->
        <div id="barcodeModal" class="modal fade barcodeModal" tabindex="-1" role="dialog" aria-labelledby="barcodeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" id="title_div"></label>
                                @php
                                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                                    $bcode = "<div id='bcode_div1'></div>";
                                @endphp     
                                <div style="margin: auto; text-align: center; width: 200px; height: 62px; border: 1px solid grey; border-style: dashed; padding: 9px;">
                                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($bcode, $generatorPNG::TYPE_CODE_128)) }}", width="180px" height="30px"> <br />
                                    <h1 style="font-size:9px;"><div id="bcode_div1"></div></h1>      
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Re-generate barcode -->

@endsection

@section('script')

        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

    <!-- Re-generate barcode -->
    <script>        
        function reGenerateBarcode(barcode, title){
            spinner('Loading...');            
            var modal = $('#barcodeModal');
            modal.find('#bcode_div1').html(barcode);     
            modal.find('#title_div').html(title);     
            $('#barcodeModal').modal('show');                
        }
    </script>
    <!-- /Re-generate barcode-->

@endsection


@section('script-bottom')

    <!-- Document Basic Details Modal -->
    <div id="documentBasicDetails" class="modal fade documentBasicDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentBasicDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentBasicDetailsModalLabel">Document Basic Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">Name: <span class="text-primary" id="title"></span></p>
                    <p class="mb-1">Type: <span class="text-primary" id="document_type_desc"></span></p>
                    <p class="mb-1">For: <span class="text-primary" id="document_action_desc"></span></p>
                    <p class="mb-1">Remarks: <span class="text-primary" id="remarks"></span></p>
                    <p class="mb-1">Notify: <span class="text-primary" id="email_notification"></span></p>
                    <hr>
                    <p class="mb-1">Hardcopy: <span class="text-primary" id="is_hardcopy"></span></p>
                    {{-- <p class="mb-1">Courier Name: <span class="text-primary" id="courier_name"></span></p>                     --}}
                    <hr>
                    <p class="mb-1">Attachment:<br/>
                    {{-- &emsp;&emsp; --}} <a href="#" id="path" target="_blank"><span class="text-primary" id="attachment_name"></span></a></p>
                    <p class="mb-1">External Link:
                        &emsp;&emsp; <a href="#" id="external_link" target="_blank"><div style="overflow-wrap: break-word;"><span class="text-primary" id="external_link_name"></span></div></a></p>
                    <hr>
                    <p class="mb-1">Confidential: <span class="text-primary" id="is_confidential"></span></p>
                    <br />
                    <div class="table-responsive">
                        <div id="userForwardedToTable"></div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Document Basic Details Modal -->

    <!-- Document Route Details Modal -->
    <div id="documentRouteDetails" class="modal fade documentRouteDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentRouteDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentRouteDetailsModalLabel">Document Route Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">Barcode: <span class="text-primary" id="r_barcode"></span></p>
                    <p class="mb-1">Name: <span class="text-primary" id="r_doc_title"></span></p>
                    <p class="mb-1">Remarks: <span class="text-primary" id="r_remarks"></span></p>
                    <div class="table-responsive">
                        <div id="routeTable"></div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Document Route Details Modal -->

    <!-- seenLogs -->
    <div id="seenLogsDetails" class="modal fade seenLogsDetailsModal" tabindex="-1" role="dialog" aria-labelledby="seenLogsDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="seenLogsDetailsModalLabel">Recipient Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">Barcode: <span class="text-primary" id="u_barcode"></span></p>
                    <p class="mb-1">Name: <span class="text-primary" id="u_doc_title"></span></p>
                    <p class="mb-1">Remarks: <span class="text-primary" id="u_remarks"></span></p>
                    <div class="table-responsive">
                        <div id="seenLogsTable"></div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /seenLogs -->

    {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
    <!-- Plugins js -->
    {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

<!-- sweetalert -->
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

        <!-- delete draft document -->
        <script>
            $(document).on("click", "#sa-custom-delete", function(e){
                e.preventDefault();
                var link = $(this).attr("href");
                swal({
                        title: "Are you sure want to delete?",
                        text: "This will be permanently deleted.",
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

        <script>

            // <!-- Document Basic -->
            function showDocumentBasicDetails(hashedDocId){
                spinner('Loading for details...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('document/details/') }}"+"/"+hashedDocId,
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        //alert(JSON.stringify(result));
                        //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'
                        $('#title').html(result[0]['title']);
                        $('#document_type_desc').html(result[0]['document_type_desc']);
                        $('#document_action_desc').html(result[0]['document_action_desc']);
                        $('#remarks').html(result[0]['remarks']);

                        if (result[0]['email_notification'] == true){ 
                            $('#email_notification').html('Yes');
                        } else {  $('#email_notification').html('No'); }

                        if (result[0]['is_hardcopy'] == true){ 
                            $('#is_hardcopy').html('Yes');
                        } else {  $('#is_hardcopy').html('No'); }

                        if (result[0]['is_confidential'] == true){ 
                            $('#is_confidential').html('Yes');
                        } else {  $('#is_confidential').html('No'); }

                        //$('#courier_name').html(result[0]['courier_last_name']+', '+result[0]['courier_first_name']+' '+result[0]['courier_middle_name']+' '+result[0]['courier_suffix_name']);
                        $('#external_link_name').html(result[0]['external_link']);
                        $("#external_link").attr("href", result[0]['external_link']);
                        $('#attachment_name').html(result[0]['attachment_name']);
                        var pathUrl = '{{ asset('') }}'+result[0]['path']; 
                        $("#path").attr("href", pathUrl);

                        /*---- User forwarded to. table ----*/
                            var tableBody="<table class='table table-centered table-nowrap'>";
                            tableBody+="<thead><tr>";
                            tableBody+="<th scope='col'>Recipients (Forwarded To)</th>";
                            tableBody+="</tr></thead><tbody>";
                            for(var i=0; i < result.length; i++){
                                tableBody+="<tr>";
                                    tableBody+="<td>";
                                        tableBody+=result[i]['recipient_last_name']+', '+result[i]['recipient_first_name']+' '+result[i]['recipient_middle_name']+' '+result[i]['recipient_suffix_name'];
                                    tableBody+="</td>";
                                tableBody+="</tr>";
                            }
                            tableBody+='</tbody></table>';
                            $('#userForwardedToTable').html(tableBody);
                        /*---- /User forwarded to. table ----*/
                        // Display Modal
                        $('#documentBasicDetails').modal('show');
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    },
                });
            }
            // <!-- /Document Basic -->

            // <!-- route -->
            function showDocumentRoutes(hashedDocId){
                spinner('Loading for the list of routes...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('document/route-details/') }}"+"/"+hashedDocId,
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        if (result == "") { alert("No routed document found."); }
                        else {
                            //alert(JSON.stringify(result));
                            $('#r_barcode').html(result[0]['barcode']);
                            $('#r_doc_title').html(result[0]['doc_title']);
                            $('#r_remarks').html(result[0]['remarks']);

                            /*---- table ----*/
                            var tableBody="<table class='table table-centered table-nowrap'>";
                                tableBody+="<thead><tr>";
                                    tableBody+="<th scope='col'>Action</th>";
                                    tableBody+="<th scope='col'>Status</th>";
                                    tableBody+="<th scope='col'>Date & Time</th>";
                                    tableBody+="<th scope='col'>By</th>";
                                    tableBody+="<th scope='col'>Region/Office</th>";
                                    tableBody+="<th scope='col'>Department/Branch</th>";
                                    tableBody+="<th scope='col'>Section/LHIO</th>";
                                tableBody+="</tr></thead><tbody>";
                                for(var i=0; i < result.length; i++){
                                    if      (result[i]['status'] == 'L'){ $stat = "Released (Sent)"; }
                                    else if (result[i]['status'] == 'C'){ $stat = "Received (Sent)"; }
                                    else if (result[i]['status'] == '1'){ $stat = "Released (Routed)"; }
                                    else if (result[i]['status'] == '2'){ $stat = "Received (Routed)"; }

                                    tableBody+="<tr>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['action_desc']; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=$stat; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['created_at']; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['last_name']+', '+result[i]['first_name']+' '+result[i]['middle_name']+' '+result[i]['suffix_name']; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['office_desc']; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['department_desc']; tableBody+="</td>";
                                        tableBody+="<td>";
                                            tableBody+=result[i]['section_desc']; tableBody+="</td>";
                                    tableBody+="</tr>";
                                }
                                tableBody+='</tbody></table>';
                                $('#routeTable').html(tableBody);
                            /*---- /table ----*/

                            // Display Modal
                            $('#documentRouteDetails').modal('show');
                        }
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    },
                });
            }
             // <!-- /route -->

            // seenLogs, date seen
            function showSeenLogs(hashedDocId){
                spinner('Loading for the list of recipients...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('document/user_seen-details/') }}"+"/"+hashedDocId,
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        //alert(JSON.stringify(result));
                        $('#u_barcode').html(result[0]['barcode']);
                        $('#u_doc_title').html(result[0]['doc_title']);
                        $('#u_remarks').html(result[0]['remarks']);

                        /*---- table ----*/
                        var tableBody="<table class='table table-centered table-nowrap'>";
                            tableBody+="<thead><tr>";
                                tableBody+="<th scope='col'>Sender</th>";
                                tableBody+="<th scope='col'>Recipient</th>";
                                tableBody+="<th scope='col'>Date Sent</th>";
                                tableBody+="<th scope='col'>Date Seen</th>";
                                tableBody+="<th scope='col'>Action</th>";
                            tableBody+="</tr></thead><tbody>";
                            for(var i=0; i < result.length; i++){
                                // if (result[i]['status'] != null){ $seen = result[i]['last_seen_at']; }
                                if (result[i]['last_seen_at'] != null){ $seen = result[i]['last_seen_at']; }
                                else                            { $seen = "unseen"; }

                                if (result[i]['action_desc'] != null){ $action = result[i]['action_desc']; }
                                else                                 { $action = "Submitted"; } //frst action is null
                                
                                tableBody+="<tr>";
                                    tableBody+="<td>";
                                        tableBody+=result[i]['s_last_name']+', '+result[i]['s_first_name']+' '+result[i]['s_middle_name']+' '+result[i]['s_suffix_name']; tableBody+="</td>";
                                    tableBody+="<td>";
                                        tableBody+=result[i]['r_last_name']+', '+result[i]['r_first_name']+' '+result[i]['r_middle_name']+' '+result[i]['r_suffix_name']; tableBody+="</td>";
                                    tableBody+="<td>";
                                        tableBody+=result[i]['d_submitted_at']; tableBody+="</td>";
                                    tableBody+="<td>";
                                        tableBody+=$seen; tableBody+="</td>";
                                    tableBody+="<td>";
                                        tableBody+=$action; tableBody+="</td>";
                                tableBody+="</tr>";
                            }
                            tableBody+='</tbody></table>';
                            $('#seenLogsTable').html(tableBody);
                        /*---- /table ----*/

                        // Display Modal
                        $('#seenLogsDetails').modal('show');
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    },
                });
            }
            // /seenLogs

            // <!-- Spinner -->
            function spinner(tile) {
                var loading = Loading({
                    title: tile,
                    titleColor: 'rgb(255, 255, 255)',
                    loadingAnimation: 'image',
                    animationSrc: "{{asset('spinner')}}/img/loading.gif",
                    animationWidth: 150,
                    animationHeight: 100,
                    defaultApply: true,
                });
                loading.out(); //hide immediately
            }
            // <!-- /Spinner -->

        </script>
                
@endsection