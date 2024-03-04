@extends('layouts.master')

@section('title') Document Route Details @endsection

@section('content')

    @component('common-components.breadcrumb')
    @slot('title') DOCUMENT ROUTE DETAILS @endslot
    @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
    @slot('li_2') <a title="Routing Management" href="javascript:history.back()">Routing Management</a> @endslot 
    @slot('li_3') Document route details @endslot
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
                        @foreach($routes as $key => $row)  
                            @if($row->status == 'F')        <!-- finalized_at(F) and routed_at(R) date is the same at route table -->
                                <div class="media">
                                    <img src="{{asset('storage/route_icon/route2.jpg')}}" alt="" class="avatar-md mr-4">

                                    <div class="media-body overflow-hidden">
                                        <h5 class="text-truncate font-size-15">{{ $row->title }}</h5>
                                        @if($row->terminal_at == null) 
                                            <p class="text-muted font-size-15"><span class="badge badge-success">Active</span></p>
                                        @else
                                            <p class="text-muted font-size-15"><span class="badge badge-danger">Terminal</span></p>
                                        @endif    
                                    </div>
                                </div>

                                <h5 class="font-size-15 mt-4">Document Details :</h5>

                                <p class="text-muted">{{ $row->remarks }}</p>

                                <div class="text-muted mt-4">
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Tracking Number : {{ $row->tracking_no }}</p>
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Document Type : {{ $row->type_desc }}</p>
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> For : {{ $row->action_desc }}</p>
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Remarks : {{ $row->remarks }}</p>
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Originating Office : {{ $row->office_desc.", ".$row->department_desc.", ".$row->section_desc }}</p>
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Created By : {{ $row->cre_first_name." ".$row->cre_middle_name.", ".$row->cre_last_name." ".$row->cre_suffix_name }}</p>
                                    @if($row->tr_last_name != '')
                                        <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Terminal Office : {{ $row->tr_office_desc.", ".$row->tr_department_desc.", ".$row->tr_section_desc }}</p>
                                        <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Tagged Terminal By : {{ $row->tr_first_name." ".$row->tr_middle_name.", ".$row->tr_last_name." ".$row->tr_suffix_name }}</p>
                                    @endif
                                </div>

                                <div class="row task-dates">
                                    <div class="col-sm-4 col-6">
                                        <div class="mt-4">
                                            <h5 class="font-size-14"><i class="bx bx-calendar mr-1 text-primary"></i> Initial Date Released</h5  dito na>
                                            <p class="text-muted mb-0">{{ $row->finalized_at }}</p>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-6">
                                        <div class="mt-4">
                                            <h5 class="font-size-14"><i class="bx bx-calendar-check mr-1 text-primary"></i> Terminal Date</h5>
                                            <p class="text-muted mb-0">{{ $row->terminal_at }}</p>
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                            @endif
                        @endforeach

                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>Date and Time Routed</th>
                                        <th>TAT<br />(DAY/S)</th>
                                        <th>Routed By</th>
                                        <th>Office</th>
                                        <th>Courier Name</th>
                                        <th>Attachment</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $key => $rr)  
                                        @php
                                                $stat = "";
                                                if      ($rr->status == 'R'){ $stat = "Released"; }
                                                else if ($rr->status == 'C'){ $stat = "Received"; }

                                                //TAT                                                
                                                date_default_timezone_set('Asia/Manila');
                                                $dateCreated = strtotime($rr->created_at);
                                                $dateToday = strtotime(date("Y-m-d H:i:s"));
                                                if ($rr->received_at == ""){
                                                    $tat = floor(abs($dateToday - $dateCreated)/(24*60*60));
                                                } else {
                                                    $dateReceived = strtotime($rr->received_at);
                                                    $tat = floor(abs($dateReceived - $dateCreated)/(24*60*60));
                                                }

                                                $cre_user = $rr->cre_last_name.', '.$rr->cre_first_name.' '.$rr->cre_middle_name.' '.$rr->cre_suffix_name;                                                
                                                $orig_office = $rr->office_desc.', '.$rr->department_desc.', '.$rr->section_desc;
                                                
                                                if ($rr->co_last_name != null){
                                                    $co_user = $rr->co_last_name.', '.$rr->co_first_name.' '.$rr->co_middle_name.' '.$rr->co_suffix_name; } 
                                                else { $co_user = ''; }

                                                $pathUrl = asset('').$rr->path;   
                                        @endphp 

                                        @if ($stat != "")
                                            <tr>
                                                <td>{{ $stat}}</td>
                                                <td>{{ $rr->action_desc }}</td>
                                                <td>{{ date('M. d, Y. h:i A', strtotime($rr->routed_at)) }}</td>        
                                                <td>{{ $tat }}</td>                                   
                                                <td>{{ $cre_user }}</td>
                                                <td>{{ $orig_office }}</td>
                                                <td>{{ $co_user }}</td>
                                                <td><a href='{{ $pathUrl }}' target='_blank'> {{ $rr->attachment_name }} </a></td>
                                                <td>{{ $rr->remarks }}</td>  
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                    </div>
                </div>
            </div>                
        </div> <!-- end row -->
    @endif        

@endsection

@section('script')

        {{-- <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>

        <!-- apexcharts -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

        <script src="assets/js/pages/project-overview.init.js"></script>

        <script src="assets/js/app.js"></script> --}}


@endsection

@section('script-bottom')
    <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>


@endsection