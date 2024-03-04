@extends('layouts.master')

@section('title') List of Draft Route Documents @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') DRAFT ROUTE DOCUMENTS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Draft Route Documents @endslot
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
                                    <th>For</th>
                                    <th>Date and Time Created</th>
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
                                    <td>{{ $row->action_desc }}</td>
                                    <td>{{ date('M. d, Y. h:i A', strtotime($row->created_at)) }}</td> 
                                    <td>                                                                            
                                        <a href="javascript:void(0)" onclick="showRouteDocumentFullDetails('{{ Hasher::encode($row->route_id) }}')">
                                            <span style="font-size: 1.2em; color: Dodgerblue;">
                                                <i class="fa fa-search" title="See all details"></i>
                                            </span></a>&nbsp;
                                        {{-- 
                                        <a href="{{ route('document.fulldetails', ['docid' => Hasher::encode($row->route_id)]) }}">
                                            <span style="font-size: 1.2em; color: darkblue;">
                                                <i class="fa fa-search-plus" title="See all details"></i>
                                            </span></a>&nbsp; --}}
                                        
                                        <a href="{{ route('route.draft.edit', ['id' => Hasher::encode($row->route_id)]) }}" >
                                            <span style="font-size: 1.2em; color: Dodgerblue;">
                                                <i class="fa fa-edit" title="Show {{ $row->title }} to update or submit."></i>
                                            </span> </a>&nbsp;

                                        <a href="{{ route('route.draft.delete', ['id' => Hasher::encode($row->route_id)]) }}" id="sa-custom-delete">
                                            <span style="font-size: 1.2em; color: Red;">
                                                <i class="far fa-trash-alt" title="Delete {{ $row->title }}."></i>
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
                        <p class="mb-1">Tracking Number: <span class="text-primary" id="tracking_no"></span></p>
                        <p class="mb-1">Name: <span class="text-primary" id="title"></span></p>
                        <p class="mb-1">Type: <span class="text-primary" id="document_type_desc"></span></p>
                        <p class="mb-1">For: <span class="text-primary" id="document_action_desc"></span></p>
                        <p class="mb-1">Remarks: <span class="text-primary" id="remarks"></span></p>
                        <p class="mb-1">Email Notification: <span class="text-primary" id="email_notification"></span></p>
                        <p class="mb-1">Courier Name: <span class="text-primary" id="courier_name"></span></p>
                        <p class="mb-1">Status: <span class="text-primary">Draft</span></p>
                        <p class="mb-1">Date & Time Created: <span class="text-primary" id="created_at"></span></p>
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