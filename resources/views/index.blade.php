@extends('layouts.master')

@section('title') Home @endsection

@section('content')

    {{-- @component('common-components.breadcrumb')
        @slot('title') Home  @endslot
        @slot('li_1') @endslot --}}
        {{-- Welcome to {{env('APP_NAME')}}  --}}
    {{-- @endcomponent --}}

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

    <div class="row">
        @if($announcement['title'] != "" || $announcement['sub_title'] != "" || $announcement['details'] != "")
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <img src="{{asset('storage/announcement/announcement.png')}}" alt="" class="avatar-md mr-4">

                            <div class="media-body overflow-hidden">
                                <h5 class="text-truncate font-size-15">{{ $announcement['title'] }}</h5>
                                <p class="text-muted">{{ $announcement['sub_title'] }}</p>
                            </div>
                        </div>

                        {{-- <h5 class="font-size-15 mt-4">Details :</h5> --}}
                        <br />
                        {{-- <p class="text-muted">{!! $announcement['details'] !!}</p> --}}
                        <div>{!! nl2br(e($announcement['details'])) !!}</div>
                        
                        {{-- <div class="text-muted mt-4">
                            <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> To achieve this, it would be necessary</p>
                            <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> Separate existence is a myth.</p>
                            <p><i class="mdi mdi-chevron-right text-primary mr-1"></i> If several languages coalesce</p>
                        </div> --}}
                        
                        <div class="row task-dates">
                            <div class="col-sm-4 col-6">
                                <div class="mt-4">
                                    <h5 class="font-size-14"><i class="bx bx-calendar mr-1 text-primary"></i> Date Published: {{ date_format($announcement['updated_at'], 'F j, Y') }}.</h5>
                                    {{-- <p class="text-muted mb-0">08 Sept, 2019</p> --}}
                                </div>
                            </div>

                            {{-- <div class="col-sm-4 col-6">
                                <div class="mt-4">
                                    <h5 class="font-size-14"><i class="bx bx-calendar-check mr-1 text-primary"></i> Due Date</h5>
                                    <p class="text-muted mb-0">12 Oct, 2019</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif    
        <!-- end col -->
    </div>
    <!-- end row -->

    <!-- Search and route document -->
    @if(Auth::check())
        @if(Gate::check('track_document') || Gate::check('received_document') || Gate::check('released_document')  || Gate::check('tag_as_terminal'))
            <div class="row"> 
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">                    
                            {{-- <h4 class="card-title mb-4">Document Criteria</h4> --}}           
                                <h4 class="card-title mb-4">ROUTING</h4>                
                            <div class="row">
                                @if(Gate::check('track_document'))
                                    <div class="col-md-3">
                                        <div class="form-group position-relative">
                                            <label>Search</label>
                                            {{-- <input type="text" class="form-control"  name="title" maxlength="17"> --}}
                                            <div class="input-group mb-3">
                                                <input id="barcode_search" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                                <div class="input-group-append">
                                                    
                                                    {{-- 02-20-2022
                                                    <button class="btn btn-info" type="submit" onclick="getRoutedDocInfo();">
                                                        <i class="fas fa-search-location" title="Search document"></i>
                                                    </button> --}}
                                                    <button class="btn btn-info" type="submit" onclick="getDocFullInfoAndRoute();">
                                                        <i class="fas fa-search-location" title="Search document"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif    

                                {{-- @if(Gate::check('received_document'))
                                    <div class="col-md-4">
                                        <div class="form-group position-relative">
                                            <label>Received</label>
                                            <div class="input-group mb-3">
                                                <input id="barcode_received" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number" autofocus>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="submit" onclick="receivedDocument();">
                                                        <i class="fas fa-file-import" title="Receive document"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif     --}}
                                @if(Gate::check('received_document'))
                                {{-- <form id="my_form" action="{{ route('route.receiving') }}" method="POST" class="needs-validation" novalidate>
                                    @csrf --}}
                                    
                                    <div class="col-md-3">
                                        <div class="form-group position-relative">
                                            <label>Received</label>
                                            <form id="my_form" action="{{ route('route.receiving') }}" method="POST" class="needs-validation" novalidate>
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <input name="receiving_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-info" type="submit">
                                                            <i class="fas fa-file-import" title="Receive document"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                {{-- </form> --}}
                                @endif         
                                {{-- @if(Gate::check('released_document'))
                                    <div class="col-md-4">
                                        <div class="form-group position-relative">
                                            <label>Released</label>
                                            <div class="input-group mb-3">
                                                <input id="barcode_released" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="submit" onclick="releasedDocument();">
                                                        <i class="fas fa-file-export" title="Release document"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif    --}}
                                @if(Gate::check('released_document'))
                                    <div class="col-md-3">
                                        <div class="form-group position-relative">
                                            <label>Released</label>
                                            <form id="my_form" action="{{ route('route.create.released') }}" method="POST" class="needs-validation" novalidate>
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <input name="releasing_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-info" type="submit">
                                                            <i class="fas fa-file-export" title="Release document"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif 

                                @if(Gate::check('tag_as_terminal'))
                                    <div class="col-md-3">
                                        <div class="form-group position-relative">
                                            <label>Tag as Terminal</label>
                                            <form id="my_form" action="{{ route('route.terminal.tagpost') }}" method="POST" class="needs-validation" novalidate>
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <input name="terminal_tr" class="form-control input-mask" data-inputmask="'mask': '999999-999999-999'" placeholder="Tracking Number" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-info" type="submit" >
                                                            <i class="fas fa-file-medical-alt" title="Release document"></i>
                                                        </button>
                                                        {{-- fas fa-dolly   fas fa-file-medical-alt --}}
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                    {{-- <i class="fas fa-sign-in-alt" title="Download"></i>
                                    <i class="fas fa-sign-out-alt" title="Download"></i>
                                    <i class="fas fa-search-location" title="Download"></i>
                                    <i class="fas fa-file-export" title="Download"></i>
                                    <i class="fas fa-file-import" title="Download"></i>
                                    <i class="fas fa-truck-loading"></i>
                                    <i class="fas fa-file-medical-alt"></i>
                                    <i class="fas fa-file-contract"></i>
                                    <i class="fas fa-dolly"></i>
                                    <i class="fas fa-barcode"></i>
                                    <i class="fas fa-warehouse" title="Download"></i> --}}
                                
                            </div>  
                            <i><b>Note: </b> All documents for routing only (With hardcopy).</i>    
                        </div>
                    </div>
                </div>                
            </div> <!-- end row -->
        @endif
    @endif        
    
    <div class="row">
        @if (!$cpo_list->isEmpty())        
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            {{-- <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(1)]) }}"> <!--1-CPO-->
                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                    <i class="fas fa-list-ul" title="View all"></i>
                                </span></a>&nbsp; --}}
                                    CPO</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-centered table-hover mb-0">
                                <tbody>
                                    @foreach ($cpo_list as $item)
                                    {{-- {{dd($item)}} --}}
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                                        {{-- <i class="bx bxs-file-doc"></i> --}}
                                                        <img src="{{asset('assets/images/file_type_icons/'.strtolower($item->extension).'.jpg')}}" alt="" 
                                                            class="avatar-title rounded-circle">
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-13 mb-1"><a href="javascript:void(0)" class="text-dark" title="{{ $item->document_type_desc }}"
                                                    onclick="showPublishedDocumentFullDetails('{{ Hasher::encode($item->published_id) }}')">{{ $item->title }}</a></h5>
                                                {{-- <small>Size : 3.25 MB</small> --}}
                                                @php
                                                    $actSize = $item->size;
                                                    if (strlen($actSize) >= 7){ 
                                                        $byte = 'MB';
                                                        $size = number_format(($actSize/1048576), 2);
                                                    } else { 
                                                        $byte = 'KB';
                                                        $size = number_format(($actSize/1024), 2);
                                                    }
                                                @endphp
                                                <small>Size : {{ $size.' '. $byte.'. Date Published : '.date_format(new DateTime($item->published_at), 'F j, Y') }}</small> 
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    {{-- <a href="{{ url('downloadfile/'.$item->path) }}" target="_blank" class="text-dark"><i class="bx bx-download h3 m-0"></i></a> --}}
                                                    {{-- <a href="{{ route('downloadfile', ['id' => $item->path]) }}" target="_blank" class="text-dark"><i class="bx bx-download h3 m-0"></i></a> --}}
                                                    <a href="{{ route('downloadfile', ['id' => Hasher::encode($item->published_id)]) }}" class="text-dark">
                                                        {{-- <i class="bx bx-download h3 m-0"></i></a> --}}
                                                        <span style="font-size: 1.5em; color: Dodgerblue;">
                                                            <i class="fas fa-download" title="Download {{ $item->attachment_name }}"></i>
                                                        </span></a>     
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(1)]) }}"> <!--1 CPO -->
                                <span style="font-size: 1.6em; color: Dodgerblue;">
                                    <i class="fas fa-angle-double-down" title="View all"></i>
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- end col -->

        @if (!$dtr_list->isEmpty())    
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            {{-- <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(2)]) }}"> <!--2-DTR-->
                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                    <i class="fas fa-list-ul" title="View all"></i>
                                </span></a>&nbsp; --}}
                                    DTR</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-centered table-hover mb-0">
                                <tbody>
                                    @foreach ($dtr_list as $item)
                                    {{-- {{dd($item)}} --}}
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                                        {{-- <i class="bx bxs-file-doc"></i> --}}
                                                        <img src="{{asset('assets/images/file_type_icons/'.strtolower($item->extension).'.jpg')}}" alt="" class="avatar-title rounded-circle">
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-13 mb-1">
                                                    <a href="javascript:void(0)" class="text-dark" title="{{ $item->document_type_desc }}"
                                                        onclick="showPublishedDocumentFullDetails('{{ Hasher::encode($item->published_id) }}')">{{ $item->title }}</a></h5>
                                                {{-- <small>Size : 3.25 MB</small> --}}
                                                @php
                                                    $actSize = $item->size;
                                                    if (strlen($actSize) >= 7){ 
                                                        $byte = 'MB';
                                                        $size = number_format(($actSize/1048576), 2);
                                                    } else { 
                                                        $byte = 'KB';
                                                        $size = number_format(($actSize/1024), 2);
                                                    }
                                                @endphp
                                                <small>Size : {{ $size.' '. $byte.'. Date Published : '.date_format(new DateTime($item->published_at), 'F j, Y') }}</small> 
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    {{-- <a href="#" class="text-dark"><i class="bx bx-download h3 m-0"></i></a> --}}
                                                    <a href="{{ route('downloadfile', ['id' => Hasher::encode($item->published_id)]) }}" class="text-dark">
                                                        {{-- <i class="bx bx-download h3 m-0"></i></a> --}}
                                                        <span style="font-size: 1.5em; color: Dodgerblue;">
                                                            <i class="fas fa-download" title="Download {{ $item->attachment_name }}"></i>
                                                        </span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(2)]) }}"> <!--2 DTR -->
                                <a href="{{route('search')}}">
                                <span style="font-size: 1.6em; color: Dodgerblue;">
                                    <i class="fas fa-angle-double-down" title="View all"></i>
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        @endif    
        <!-- end col -->

        @if (!$ot_list->isEmpty())
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            {{-- <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(3)]) }}"> <!--3-OT-->
                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                    <i class="fas fa-list-ul" title="View all"></i>
                                </span></a>&nbsp; --}}
                                    OT</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-centered table-hover mb-0">
                                <tbody>
                                    @foreach ($ot_list as $item)
                                    {{-- {{dd($item)}} --}}
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                                        {{-- <i class="bx bxs-file-doc"></i> --}}
                                                        <img src="{{asset('assets/images/file_type_icons/'.strtolower($item->extension).'.jpg')}}" alt="" class="avatar-title rounded-circle">
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-13 mb-1">
                                                    <a href="javascript:void(0)" class="text-dark" title="{{ $item->document_type_desc }}"
                                                        onclick="showPublishedDocumentFullDetails('{{ Hasher::encode($item->published_id) }}')">{{ $item->title }}</a></h5>
                                                {{-- <a href="#" class="text-dark" title="{{ $item->attachment_name }}">{{ $item->title }}</a></h5> --}}
                                                {{-- <small>Size : 3.25 MB</small> --}}
                                                @php
                                                    $actSize = $item->size;
                                                    if (strlen($actSize) >= 7){ 
                                                        $byte = 'MB';
                                                        $size = number_format(($actSize/1048576), 2);
                                                    } else { 
                                                        $byte = 'KB';
                                                        $size = number_format(($actSize/1024), 2);
                                                    }
                                                @endphp
                                                <small>Size : {{ $size.' '. $byte.'. Date Published : '.date_format(new DateTime($item->published_at), 'F j, Y') }}</small> 
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    {{-- <a href="#" class="text-dark"><i class="bx bx-download h3 m-0"></i></a> --}}
                                                    <a href="{{ route('downloadfile', ['id' => Hasher::encode($item->published_id)]) }}" class="text-dark">
                                                        {{-- <i class="bx bx-download h3 m-0"></i></a> --}}
                                                        <span style="font-size: 1.5em; color: Dodgerblue;">
                                                            <i class="fas fa-download" title="Download {{ $item->attachment_name }}"></i>
                                                        </span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('publish.list.bytype', ['doc_type_id' => Hasher::encode(3)]) }}"> <!--3 OT -->
                                <span style="font-size: 1.6em; color: Dodgerblue;">
                                    <i class="fas fa-angle-double-down" title="View all"></i>
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="text-center" style="width: 50%; margin: 0 auto;">
            <a href="{{route('search')}}">
                <span style="font-size: 2.2em; color: Dodgerblue;">
                    <i class="fa fa-search" title="View all"></i>
                </span></a>
            <br />ADVANCE PUBLISHED DOCUMENT SEARCH
        </div>
    </div><br />
    <!-- end row -->

    

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

    <div id="documentRouteDetails" class="modal fade documentRouteDetailsModal" tabindex="-1" role="dialog" aria-labelledby="documentRouteDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentRouteDetailsModalLabel">Route Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body"><p class="mb-1">Status: <span id="r_status_desc"></span></p>
                    <hr>
                    <p class="mb-1">Tracking Number: <span class="text-primary" id="r_tracking_no"></span></p>
                    <p class="mb-1">Document Name: <span class="text-primary" id="r_title"></span></p>
                    <p class="mb-1">Document Type: <span class="text-primary" id="r_type"></span></p>
                    <p class="mb-1">For: <span class="text-primary" id="r_action"></span></p>
                    <p class="mb-1">Remarks: <span class="text-primary" id="r_remarks"></span></p>
                    <p class="mb-1">Created By (Date): <span class="text-primary" id="r_cre_user"></span> 
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

                    // var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
                    var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
                    // var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
                    // var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
                    // $('#created_at').html(createdAt);
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

    <script>
            //02-20-2022
            // function getRoutedDocInfo(){
            //     var barcode = document.getElementById("barcode_search").value;
            //     var trimmedBcode = barcode.trim();
            //     if (trimmedBcode == ""){ alert ("Please provide tracking number/barcode."); }
            //     else {
                    
            //             spinner('Loading for details...');
            //             $.ajax({
            //                 type:"GET",
            //                 url: "{{ url('document/details-by-trackingnumber/') }}"+"/"+trimmedBcode,  
            //                 dataType: "json",
            //                 cache: false,
            //                 success: function(result){
            //                     alert(JSON.stringify(result));
            //                     //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'

            //                     $('#document_id').html(result[0]['document_id']);
            //                     $('#document_name').html(result[0]['title']);
            //                     $('#remarks').html(result[0]['remarks']);
            //                     $('#document_type_desc').html(result[0]['document_type_desc']);

            //                     $('#attachment_name').html(result[0]['attachment_name']);              
            //                     var pathUrl = '{{ asset('') }}'+result[0]['path']; 
            //                     $("#path").attr("href", pathUrl);

            //                     $('#created_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']);
            //                     $('#published_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']); //same who created
            //                     editedBy = result[0]['ue_last_name']+', '+result[0]['ue_first_name']+' '+result[0]['ue_middle_name']+' '+result[0]['ue_suffix_name'];
            //                     deletedBy = result[0]['ud_last_name']+', '+result[0]['ud_first_name']+' '+result[0]['ud_middle_name']+' '+result[0]['ud_suffix_name'];
            //                     if (result[0]['ue_last_name'] == null) { editedBy = ""; }
            //                     if (result[0]['ud_last_name'] == null) { deletedBy = ""; }
            //                     $('#edited_by').html(editedBy);
            //                     $('#deleted_by').html(deletedBy);

            //                     //published to
            //                     if (result[0]['office_id'] != null || result[0]['department_id'] != null ){
            //                         if (result[0]['department_id'] != null){
            //                             $publishedTo = result[0]['office_desc']+' / '+result[0]['department_desc'];
            //                         } else {
            //                             $publishedTo = result[0]['office_desc'];
            //                         }
            //                     } else {  $publishedTo = 'All'; }     
            //                     $('#published_to').html($publishedTo);

            //                     //var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
            //                     var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
            //                     //var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
            //                     //var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
            //                     //$('#created_at').html(createdAt);
            //                     $('#published_at').html(publishedAt);
                                
            //                     // if (updatedAt == "January 01, 1970") { updatedAt = ""; }
            //                     // if (deletedAt == "January 01, 1970") { deletedAt = ""; }
            //                     // $('#updated_at').html(updatedAt);
            //                     // $('#deleted_at').html(deletedAt);
            //                     //is_published
            //                     // if (result[0]['email_notification'] == true){ 
            //                     //     $('#email_notification').html('Yes');
            //                     // } else {  $('#email_notification').html('No'); }

            //                     // Display Modal
            //                     $('#documentFullDetails').modal('show');
            //                 },
            //                 error: function (request, status, error) {
            //                     alert(request.responseText);
            //                 },
            //             });
            //     }            
            // }
            function getDocFullInfoAndRoute(){
                var barcode = document.getElementById("barcode_search").value;
                var trimmedBcode = barcode.trim();
                if (trimmedBcode == ""){ alert ("Please provide tracking number/barcode."); }
                else {
                    
                        spinner('Loading for details...');
                        $.ajax({
                            type:"GET",
                            // url: "{{ url('document/details-by-trackingnumber/') }}"+"/"+trimmedBcode,  
                            url: "{{ url('route/trail-details/') }}"+"/"+trimmedBcode,
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
                                            //else if (result[i]['status'] == 'T'){ $stat = "Terminal"; }
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
            }

            // function receivedDocument(){
            //     var barcode = document.getElementById("barcode_received").value;
            //     var trimmedBcode = barcode.trim();
            //     //alert(trimmedBcode);
            //     if (trimmedBcode == ""){ alert ("Please provide tracking number/barcode."); }
            //     else {
                    
            //             spinner('Loading for details...');
            //             $.ajax({
            //                 type:"GET",
            //                 url: "{{ url('document/receiving-document/') }}"+"/"+trimmedBcode,  
            //                 dataType: "json",
            //                 cache: false,
            //                 success: function(result){
            //                     alert(JSON.stringify(result));
            //                     //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'

            //                     $('#document_id').html(result[0]['document_id']);
            //                     $('#document_name').html(result[0]['title']);
            //                     $('#remarks').html(result[0]['remarks']);
            //                     $('#document_type_desc').html(result[0]['document_type_desc']);

            //                     $('#attachment_name').html(result[0]['attachment_name']);              
            //                     var pathUrl = '{{ asset('') }}'+result[0]['path']; 
            //                     $("#path").attr("href", pathUrl);

            //                     $('#created_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']);
            //                     $('#published_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']); //same who created
            //                     editedBy = result[0]['ue_last_name']+', '+result[0]['ue_first_name']+' '+result[0]['ue_middle_name']+' '+result[0]['ue_suffix_name'];
            //                     deletedBy = result[0]['ud_last_name']+', '+result[0]['ud_first_name']+' '+result[0]['ud_middle_name']+' '+result[0]['ud_suffix_name'];
            //                     if (result[0]['ue_last_name'] == null) { editedBy = ""; }
            //                     if (result[0]['ud_last_name'] == null) { deletedBy = ""; }
            //                     $('#edited_by').html(editedBy);
            //                     $('#deleted_by').html(deletedBy);

            //                     //published to
            //                     if (result[0]['office_id'] != null || result[0]['department_id'] != null ){
            //                         if (result[0]['department_id'] != null){
            //                             $publishedTo = result[0]['office_desc']+' / '+result[0]['department_desc'];
            //                         } else {
            //                             $publishedTo = result[0]['office_desc'];
            //                         }
            //                     } else {  $publishedTo = 'All'; }     
            //                     $('#published_to').html($publishedTo);

            //                     //var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
            //                     var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
            //                     //var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
            //                     //var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
            //                     //$('#created_at').html(createdAt);
            //                     $('#published_at').html(publishedAt);
                                
            //                     // if (updatedAt == "January 01, 1970") { updatedAt = ""; }
            //                     // if (deletedAt == "January 01, 1970") { deletedAt = ""; }
            //                     // $('#updated_at').html(updatedAt);
            //                     // $('#deleted_at').html(deletedAt);
            //                     //is_published
            //                     // if (result[0]['email_notification'] == true){ 
            //                     //     $('#email_notification').html('Yes');
            //                     // } else {  $('#email_notification').html('No'); }

            //                     // Display Modal
            //                     $('#documentFullDetails').modal('show');
            //                 },
            //                 error: function (request, status, error) {
            //                     alert(request.responseText);
            //                 },
            //             });
            //     }
            // }

            // function releasedDocument(){
            //     var barcode = document.getElementById("barcode_released").value;
            //     var trimmedBcode = barcode.trim();
            //     //alert(trimmedBcode);
            //     if (trimmedBcode == ""){ alert ("Please provide tracking number/barcode."); }
            //     else {
                    
            //             spinner('Loading for details...');
            //             $.ajax({
            //                 type:"GET",
            //                 url: "{{ url('document/releasing-document/') }}"+"/"+trimmedBcode,  
            //                 dataType: "json",
            //                 cache: false,
            //                 success: function(result){
            //                     alert(JSON.stringify(result));
            //                     //alert(result['title']); //don't use 'result[0]' since the model return only first row   ->first();'

            //                     $('#document_id').html(result[0]['document_id']);
            //                     $('#document_name').html(result[0]['title']);
            //                     $('#remarks').html(result[0]['remarks']);
            //                     $('#document_type_desc').html(result[0]['document_type_desc']);

            //                     $('#attachment_name').html(result[0]['attachment_name']);              
            //                     var pathUrl = '{{ asset('') }}'+result[0]['path']; 
            //                     $("#path").attr("href", pathUrl);

            //                     $('#created_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']);
            //                     $('#published_by').html(result[0]['uc_last_name']+', '+result[0]['uc_first_name']+' '+result[0]['uc_middle_name']+' '+result[0]['uc_suffix_name']); //same who created
            //                     editedBy = result[0]['ue_last_name']+', '+result[0]['ue_first_name']+' '+result[0]['ue_middle_name']+' '+result[0]['ue_suffix_name'];
            //                     deletedBy = result[0]['ud_last_name']+', '+result[0]['ud_first_name']+' '+result[0]['ud_middle_name']+' '+result[0]['ud_suffix_name'];
            //                     if (result[0]['ue_last_name'] == null) { editedBy = ""; }
            //                     if (result[0]['ud_last_name'] == null) { deletedBy = ""; }
            //                     $('#edited_by').html(editedBy);
            //                     $('#deleted_by').html(deletedBy);

            //                     //published to
            //                     if (result[0]['office_id'] != null || result[0]['department_id'] != null ){
            //                         if (result[0]['department_id'] != null){
            //                             $publishedTo = result[0]['office_desc']+' / '+result[0]['department_desc'];
            //                         } else {
            //                             $publishedTo = result[0]['office_desc'];
            //                         }
            //                     } else {  $publishedTo = 'All'; }     
            //                     $('#published_to').html($publishedTo);

            //                     //var createdAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['created_at'])); 
            //                     var publishedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['published_at'])); 
            //                     //var updatedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['updated_at'])); 
            //                     //var deletedAt = $.datepicker.formatDate( "MM dd, yy", new Date(result[0]['deleted_at'])); 
            //                     //$('#created_at').html(createdAt);
            //                     $('#published_at').html(publishedAt);
                                
            //                     // if (updatedAt == "January 01, 1970") { updatedAt = ""; }
            //                     // if (deletedAt == "January 01, 1970") { deletedAt = ""; }
            //                     // $('#updated_at').html(updatedAt);
            //                     // $('#deleted_at').html(deletedAt);
            //                     //is_published
            //                     // if (result[0]['email_notification'] == true){ 
            //                     //     $('#email_notification').html('Yes');
            //                     // } else {  $('#email_notification').html('No'); }

            //                     // Display Modal
            //                     $('#documentFullDetails').modal('show');
            //                 },
            //                 error: function (request, status, error) {
            //                     alert(request.responseText);
            //                 },
            //             });
            //     }
            // }

    </script>    

@endsection