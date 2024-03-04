@extends('layouts.master')

@section('title') List of Released Documents @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') RELEASED DOCUMENTS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Released Documents @endslot
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
                                    <th>Tracking Number</th> <!--allow to generate tracking_no here-->
                                    {{-- <th>Recipient</th> --}}
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Remarks</th>
                                    {{-- <th>For</th> --}}
                                    {{-- <th>Date and Time Routed</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $key => $row)  
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td><a href="javascript:void(0)" onclick="reGenerateTrackingNo('{{ $row->tracking_no }}', '{{ $row->title }}');" title="Re-generate tracking number"> 
                                        {{ $row->tracking_no }}</a></td>
                                    {{-- <td>{{ $row->r_last_name.', '.$row->r_first_name.' '.$row->r_middle_name.' '.$row->r_suffix_name }}</td> --}}
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->type_desc }}</td>
                                    <td>{{ $row->remarks }}</td>
                                    {{-- <td>{{ $row->action_desc }}</td> --}}
                                    {{-- <td>{{ date('M. d, Y. h:i A', strtotime($row->routed_at)) }}</td>  --}}
                                    <td>                        
                                        <a href="javascript:void(0)" onclick="showRouteDocumentFullDetails('{{ Hasher::encode($row->route_id) }}')">
                                            <span style="font-size: 1.2em; color: Dodgerblue;">
                                                <i class="fa fa-search" title="See {{ $row->title }} all details"></i>
                                            </span></a>&nbsp;

                                        <a href="javascript:void(0)" onclick="showDocumentRoutes('{{ $row->tracking_no }}')">
                                            <span style="font-size: 1.2em; color: green;">
                                                <i class="fas fa-route" title="Show {{ $row->title }} routes"></i>
                                            </span></a>&nbsp;    

                                        <a href="{{ route('route.terminal.tag', ['id' => $row->tracking_no]) }}" id="sa-custom-terminal">
                                            <span style="font-size: 1.2em; color: Red;">
                                                <i class="fas fa-file-medical-alt" title="Tag {{ $row->title }} as terminal"></i>
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
                                <div style="margin: auto; text-align: center; width: 250px; height: 80px; border: 1px solid grey; border-style: dashed; padding: 9px;">
                                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($bcode, $generatorPNG::TYPE_CODE_128)) }}", width="220px" height="50px"> <br />
                                    <h1 style="font-size:14px;"><div id="bcode_div1"></div></h1>      
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

        <!-- Document Full Details Modal -->
        <div id="documentFullDetails" class="modal fade documentFullDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentFullDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="documentFullDetailsModalLabel">Document Basic Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1">Status: <span id="status_desc"></span></p>
                        <hr>
                        <p class="mb-1">Tracking Number: <span class="text-primary" id="tracking_no"></span></p>
                        <p class="mb-1">Name: <span class="text-primary" id="title"></span></p>
                        <p class="mb-1">Type: <span class="text-primary" id="document_type_desc"></span></p>
                        <p class="mb-1">For: <span class="text-primary" id="document_action_desc"></span></p>
                        <p class="mb-1">Remarks: <span class="text-primary" id="remarks"></span></p>
                        <p class="mb-1">Email Notification: <span class="text-primary" id="email_notification"></span></p>
                        <p class="mb-1">Courier Name: <span class="text-primary" id="courier_name"></span></p>
                        {{-- <p class="mb-1">Status: <span class="text-primary">Draft</span></p> --}}
                        <p class="mb-1">Date & Time Created: <span class="text-primary" id="created_at"></span></p>
                        <hr>
                        <p class="mb-1">Tag as Terminal By (Date): <span class="text-primary" id="terminal_user"></span>
                                                                    (<span class="text-primary" id="terminal_at"></span>)</p>
                        <p class="mb-1">Terminal Office: <span class="text-primary" id="terminal_office"></span></p>
                        <hr>
                        <p class="mb-1">Attachment:<br/>
                        {{-- &emsp;&emsp; --}} <a href="#" id="path" target="_blank"><span class="text-primary" id="attachment_name"></span></a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Document Full Details Modal -->

        <!-- Document Route Details Modal -->
        <div id="documentRouteDetails" class="modal fade documentRouteDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentRouteDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="documentRouteDetailsModalLabel">Route Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body"><p class="mb-1">Status: <span id="r_status_desc"></span></p>
                        <hr>
                        <p class="mb-1">Tracking Number: <span class="text-primary" id="r_tracking_no"></span></p>
                        <p class="mb-1">Document Name: <span class="text-primary" id="r_title"></span></p>
                        <p class="mb-1">Document Type: <span class="text-primary" id="r_type"></span></p>
                        <p class="mb-1">For: <span class="text-primary" id="r_action"></span></p>
                        <p class="mb-1">Remarks: <span class="text-primary" id="r_remarks"></span></p>
                        <p class="mb-1">Finalized By (Date): <span class="text-primary" id="r_cre_user"></span> 
                                                                (<span class="text-primary" id="r_finalized_at"></span>)</p>
                        <p class="mb-1">Originating From: <span class="text-primary" id="r_originating_office"></span></p>

                        <p class="mb-1">Tag as Terminal By (Date): <span class="text-primary" id="rt_terminal_user"></span>
                                                                    (<span class="text-primary" id="rt_terminal_at"></span>)</p>
                        <p class="mb-1">Terminal Office: <span class="text-primary" id="rt_terminal_office"></span></p>
                        {{-- <p class="mb-1">Name: <span class="text-primary" id="r_doc_title"></span></p>
                        <p class="mb-1">Remarks: <span class="text-primary" id="r_remarks"></span></p> --}}
                        <br />
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
        function reGenerateTrackingNo(barcode, title){
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

    {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
    <!-- Plugins js -->
    {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

<!-- sweetalert -->
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

        <script>

            //<!-- Document Full -->
            function showRouteDocumentFullDetails(hashedRouteId){
                spinner('Loading for details...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('route/details/') }}"+"/"+hashedRouteId, 
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        //alert(JSON.stringify(result));
                        //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'
                        $('#tracking_no').html(result['tracking_no']);
                        $('#title').html(result['title']);
                        $('#document_type_desc').html(result['type_desc']);
                        $('#document_action_desc').html(result['action_desc']);
                        $('#remarks').html(result['remarks']);
                        $('#created_at').html(result['created_at']);

                        if (result['email_notification'] == true){ 
                            $('#email_notification').html('Yes');
                        } else {  $('#email_notification').html('No'); }

                        if (result['co_last_name'] == null){ $('#courier_name').html(''); }
                        else {
                            $('#courier_name').html(result['co_last_name']+', '+result['co_first_name']+' '+result['co_middle_name']+' '+result['co_suffix_name']); }

                        //if terminal only
                        if (result['tr_last_name'] == null){ $tr_user = ''; }
                        else { $tr_user = result['tr_last_name']+', '+result['tr_first_name']+' '+result['tr_middle_name']+' '+result['tr_suffix_name']; } 
                        if (result['tr_office_desc'] == null){ $tr_office = ''; }
                        else { $tr_office = result['tr_office_desc']+', '+result['tr_department_desc']+', '+result['tr_section_desc']; } 
                        $('#terminal_user').html($tr_user); //Terminal by
                        $('#terminal_at').html(result['terminal_at']); //Terminal date
                        $('#terminal_office').html($tr_office); //Terminal office

                        //status
                        if (result['tr_last_name'] == null){ //not yet in terminal state
                            $('#status_desc').html('Active');
                            $('#status_desc').removeClass().addClass('badge badge-success');
                            $('#status_desc').css('font-size','1em');
                        } else {
                            $('#status_desc').html('Terminal');
                            $('#status_desc').removeClass().addClass('badge badge-danger');
                            $('#status_desc').css('font-size','1em');
                        }

                        $('#attachment_name').html(result['attachment_name']);
                        var pathUrl = '{{ asset('') }}'+result['path']; 
                        $("#path").attr("href", pathUrl);
                        // Display Modal
                        $('#documentFullDetails').modal('show');
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    },
                });
            }
            //<!-- /Document Full -->

            // <!-- route -->
            function showDocumentRoutes(trackNo){
                spinner('Loading for the list of routes...');
                $.ajax({
                    type:"GET",
                    url: "{{ url('route/trail-details/') }}"+"/"+trackNo,
                    dataType: "json",
                    cache: false,
                    success: function(result){
                        if (result == "") { alert("No routed document found."); }
                        else {
                            //alert(JSON.stringify(result));

                            /*---- table ----*/
                            var tableBody="<table class='table table-centered table-nowrap'>";
                                tableBody+="<thead><tr>";
                                    tableBody+="<th scope='col'>Status</th>";
                                    tableBody+="<th scope='col'>Action</th>";
                                    tableBody+="<th scope='col'>Date & Time Routed</th>";
                                    tableBody+="<th scope='col'>TAT<br />(DAY/S)</th>";
                                    tableBody+="<th scope='col'>Routed By</th>";
                                    tableBody+="<th scope='col'>Office</th>";
                                    tableBody+="<th scope='col'>Courier Name</th>";
                                    tableBody+="<th scope='col'>Attachment</th>";
                                    tableBody+="<th scope='col'>Remarks</th>";
                                tableBody+="</tr></thead><tbody>";
                                for(var i=0; i < result.length; i++){

                                    if (result[i]['co_last_name'] != null){
                                        $co_user = result[i]['co_last_name']+', '+result[i]['co_first_name']+' '+result[i]['co_middle_name']+' '+result[i]['co_suffix_name']; } 
                                    else { $co_user = ''; }

                                    if (result[i]['remarks'] != null){ $remarks = result[i]['remarks']; } 
                                    else { $remarks = ''; }

                                    $cre_user = result[i]['cre_last_name']+', '+result[i]['cre_first_name']+' '+result[i]['cre_middle_name']+' '+result[i]['cre_suffix_name'];
                                    $orig_office = result[i]['office_desc']+', '+result[i]['department_desc']+', '+result[i]['section_desc'];

                                    //TAT
                                    //date_default_timezone_set('Asia/Manila');
                                    $dateCreated = new Date(result[i]['created_at']);
                                    if (result[i]['received_at'] == null){
                                        $tat = Math.floor((new Date() - $dateCreated)/(24*3600*1000));
                                    } else {
                                        $dateReceived = new Date(result[i]['received_at']);
                                        $tat = Math.floor(($dateReceived - $dateCreated)/(24*3600*1000));
                                    }

                                    //common, original info
                                    $('#r_remarks').html('');
                                    if (result[i]['status'] == 'F'){ //originating office who finalized/submitted
                                        $('#r_tracking_no').html(result[i]['tracking_no']);
                                        $('#r_title').html(result[i]['title']);
                                        $('#r_type').html(result[i]['type_desc']);
                                        $('#r_action').html(result[i]['action_desc']);
                                        $('#r_remarks').html(result[i]['remarks']);
                                        $('#r_cre_user').html($cre_user); //Created by / Finalized by
                                        $('#r_finalized_at').html(result[i]['finalized_at']); //finalized date
                                        $('#r_originating_office').html($orig_office); //originating office
                                    }

                                    //if terminal only
                                    $tr_user = result[i]['tr_last_name']+', '+result[i]['tr_first_name']+' '+result[i]['tr_middle_name']+' '+result[i]['tr_suffix_name']; 
                                    $tr_office = result[i]['tr_office_desc']+', '+result[i]['tr_department_desc']+', '+result[i]['tr_section_desc'];
                                    $('#rt_terminal_user').html(''); 
                                    $('#rt_terminal_at').html('');
                                    $('#rt_terminal_office').html(''); 
                                    if (result[i]['status'] == 'F' && result[i]['terminal_at'] != null){ //Terminal
                                        $('#rt_terminal_user').html($tr_user); //Terminal by
                                        $('#rt_terminal_at').html(result[i]['terminal_at']); //Terminal date
                                        $('#rt_terminal_office').html($tr_office); //Terminal office

                                        $('#r_status_desc').html('Terminal');
                                        $('#r_status_desc').removeClass().addClass('badge badge-danger');
                                        $('#r_status_desc').css('font-size','1em');
                                    } else {
                                        $('#r_status_desc').html('Active');
                                        $('#r_status_desc').removeClass().addClass('badge badge-success');
                                        $('#r_status_desc').css('font-size','1em');
                                    }

                                    if      (result[i]['status'] == 'R'){ $stat = "Released"; }
                                    else if (result[i]['status'] == 'C'){ $stat = "Received"; }
                                    else if (result[i]['status'] == 'T'){ $stat = "Terminal"; }
                                    //else if (result[i]['status'] == 'F'){ $stat = "Finalized"; }
                                    //else if (result[i]['status'] == 'D'){ $stat = "Draft"; }

                                    if (result[i]['status'] == 'R' || result[i]['status'] == 'C'){ //released and received only
                                        tableBody+="<tr>";
                                            tableBody+="<td>";
                                                tableBody+=$stat; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=result[i]['action_desc']; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=result[i]['routed_at']; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=$tat; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=$cre_user; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=$orig_office; tableBody+="</td>";
                                            tableBody+="<td>";
                                                tableBody+=$co_user; tableBody+="</td>";
                                                
                                            $('#attachment_name').html(result[i]['attachment_name']);
                                            var pathUrl = '{{ asset('') }}'+result[i]['path'];    
                                            //$("#path").attr("href", pathUrl);  
                                            tableBody+="<td>";
                                                tableBody+="<a href='"+pathUrl+"' target='_blank'>"+result[i]['attachment_name']+"</a>"; tableBody+="</td>";

                                            tableBody+="<td>";
                                                tableBody+=$remarks; tableBody+="</td>";
                                        tableBody+="</tr>";
                                    }    
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

            //<!-- tag as terminal -->
            $(document).on("click", "#sa-custom-terminal", function(e){
                e.preventDefault();
                var link = $(this).attr("href");
                swal({
                        title: "Are you sure you want to tag this as terminal?",
                        text: "This could not be released of received.",
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
            //<!-- //tag as terminal -->

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