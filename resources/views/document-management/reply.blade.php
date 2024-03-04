@extends('layouts.master')

@section('title') Reply @endsection

@section('css') 
        <!-- DataTables -->        
        {{-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}"> --}}

        <!-- Form Advanced -->
        {{-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}"> --}}
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') REPLY @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') <a title="Back to List of Received Documents" href="{{route('document.received.list')}}">Received</a> @endslot
        @slot('li_3') Reply to document @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
        <form id="my_form" action="{{ route('document.submit.reply') }}" method="POST" class="needs-validation"> <!--class="needs-validation outer-repeater"--> <!--class="needs-validation outer-repeater" enctype="multipart/form-data" -->
            @csrf 

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            {{-- <div class="media">
                                <img src="assets/images/companies/img-1.png" alt="" class="avatar-sm mr-4">

                                <div class="media-body overflow-hidden">
                                    <h5 class="text-truncate font-size-15">Skote Dashboard UI</h5>
                                    <p class="text-muted">Separate existence is a myth. For science, music, sport, etc.</p>
                                </div>
                            </div> --}}

                            <h5 class="font-size-15 ">{{ $doc[0]->title }}</h5>
                            <p class="text-muted">{{ $doc[0]->remarks }}</p>
                            <div class="text-muted mt-4">
                                <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>{{ $doc[0]->document_type_desc }}</p>
                                <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>For: {{ $doc[0]->document_action_desc }}</p>
                                @if ($doc[0]->is_hardcopy)  
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>Tracking No.: {{ $doc[0]->barcode }}</p>  
                                    {{-- <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>Created By: {{ $doc[0]->u_first_name.' '.
                                                                                                            $doc[0]->u_middle_name.' '.
                                                                                                            $doc[0]->u_last_name.' '.
                                                                                                            $doc[0]->u_suffix_name
                                    }}</p>                                   --}}
                                    <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>With/is a hard copy document.</p>
                                    {{-- <p><i class="mdi mdi-chevron-right text-primary mr-1"></i>Courier: {{ $doc[0]->courier_first_name.' '.
                                                                                                    $doc[0]->courier_middle_name.' '.
                                                                                                    $doc[0]->courier_last_name.' '.
                                                                                                    $doc[0]->courier_suffix_name
                                                                                                }}</p>                                         --}}
                                @endif
                                
                            </div>

                            <div class="row task-dates">
                                <div class="col-sm-4 col-6">
                                    <div class="mt-4">
                                        <h5 class="font-size-14"><i class="bx bx-calendar mr-1 text-primary"></i>Date & Time Submitted</h5>
                                        <p class="text-muted mb-0">{{ date('M. d, Y. h:i A', strtotime($doc[0]->submitted_at)) }}</p>
                                    </div>
                                </div>

                                {{-- <div class="col-sm-4 col-6">
                                    <div class="mt-4">
                                        <h5 class="font-size-14"><i class="bx bx-calendar-check mr-1 text-primary"></i> Due Date</h5>
                                        <p class="text-muted mb-0">12 Oct, 2019</p>
                                    </div>
                                </div> --}}
                            </div>

                            <br /><br />
                            <h4 class="card-title mb-4">Recipients</h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <tbody>
                                        {{-- creator --}}
                                        <tr>
                                            <td>
                                                @if($doc[0]->avatar === null)
                                                    <img src="{{Gravatar::src($doc[0]->username)}}" class="rounded-circle avatar-xs" alt=""></td>
                                                @else 
                                                    <img src="{{asset('images/profile/'.$doc[0]->avatar)}}" class="rounded-circle avatar-xs" alt=""></td>
                                                @endif 
                                            </td>
                                            <td><h5 class="font-size-14 m-0"><a href="" class="text-dark">{{$doc[0]->u_first_name.' '.
                                                                                                                $doc[0]->u_middle_name.' '.
                                                                                                                $doc[0]->u_last_name.' '.
                                                                                                                $doc[0]->u_suffix_name
                                                                                                            }} (Creator)</a></h5></td>
                                            <td>
                                                <div>
                                                    {{ $doc[0]->office_desc.', '.$doc[0]->department_desc.', '.$doc[0]->section_desc }}
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        @foreach($recipients as $key => $rrow)
                                            <tr>
                                                <td>
                                                    @if($rrow->r_avatar === null)
                                                        <img src="{{Gravatar::src($rrow->r_username)}}" class="rounded-circle avatar-xs" alt=""></td>
                                                    @else 
                                                        <img src="{{asset('images/profile/'.$rrow->r_avatar)}}" class="rounded-circle avatar-xs" alt=""></td>
                                                    @endif 
                                                </td>
                                                <td><h5 class="font-size-14 m-0"><a href="" class="text-dark">{{ $rrow->r_first_name.' '.
                                                                                                                    $rrow->r_middle_name.' '.
                                                                                                                    $rrow->r_last_name.' '.
                                                                                                                    $rrow->r_suffix_name
                                                                                                                }}</a></h5></td>
                                                <td>
                                                    <div>
                                                        {{ $rrow->office_desc.', '.$rrow->department_desc.', '.$rrow->section_desc }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Comments</h4>
    
                            {{-- creator --}}                            
                            {{-- <div class="media mb-4">
                                <div class="mr-3">
                                    @if($doc[0]->avatar === null)
                                        <img src="{{Gravatar::src($doc[0]->username)}}" class="media-object rounded-circle avatar-xs" alt=""></td>
                                    @else 
                                        <img src="{{asset('images/profile/'.$doc[0]->avatar)}}" class="media-object rounded-circle avatar-xs" alt=""></td>
                                    @endif 
                                </div>
                                <div class="media-body">
                                    <h5 class="font-size-13 mb-1">{{ $doc[0]->u_first_name.' '.$doc[0]->u_middle_name.' '.$doc[0]->u_last_name.' '.$doc[0]->u_suffix_name }}</h5>
                                    <div class="media mt-3">
                                        <div class="media-body">
                                            @if ($doc[0]->attachment_name != null)
                                                <p class="text-muted mb-1">Attachment:
                                                    <a href="{{ asset('').$doc[0]->path }}" target="_blank">{{ $doc[0]->attachment_name }}</a></p>
                                            @endif     
                                            @if ($doc[0]->external_link != null)
                                                <p class="text-muted mb-1">External Link:
                                                    <a href="{{ $doc[0]->external_link }}" id="external_link" target="_blank">{{ $doc[0]->external_link }}</a></p>
                                                    @endif 
                                            @if ($doc[0]->remarks != null)
                                                <p class="text-muted mb-1">
                                                    {{ $doc[0]->remarks.'   ('.date('M. d, Y. h:i A', strtotime($doc[0]->submitted_at)).')' }} </p><br />                                                    
                                            @endif    
                                        </div>
                                    </div>
                                </div>       
                            </div> --}}
                            {{-- /creator --}}      

                            @foreach($reply as $key => $row)
                                <div class="media mb-4">
                                    <div class="mr-3">
                                        @if($row->s_avatar === null)
                                            <img src="{{Gravatar::src($row->s_username)}}" class="media-object rounded-circle avatar-xs" alt=""></td>
                                        @else 
                                            <img src="{{asset('images/profile/'.$row->s_avatar)}}" class="media-object rounded-circle avatar-xs" alt=""></td>
                                        @endif 
                                    </div>
                                    <div class="media-body">
                                        <h5 class="font-size-13 mb-1">{{ $row->s_first_name.' '.$row->s_middle_name.' '.$row->s_last_name.' '.$row->s_suffix_name }}</h5>
                                        {{-- <p class="text-muted mb-1">
                                            <a href="" class="text-success">@Henry</a>
                                            To an English person it will like simplified
                                        </p> --}}

                                        <div class="media mt-3">
                                            <div class="media-body">
                                                @if ($row->att_attachment_name != null)
                                                    <p class="text-muted mb-1">Attachment:
                                                        <a href="{{ asset('').$doc[0]->path }}" target="_blank">{{ $row->att_attachment_name }}</a></p>
                                                @endif     
                                                @if ($row->att_external_link != null)
                                                    <p class="text-muted mb-1">External Link:
                                                        <a href="{{ $row->att_external_link }}" target="_blank">{{ $row->att_external_link }}</a></p>
                                                @endif    
                                            </div>
                                        </div>

                                        <div class="media mt-3">
                                            {{-- <div class="avatar-xs mr-3">
                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-16">
                                                    J
                                                </span>
                                            </div> --}}
                                            
                                                <div class="media-body">
                                                    {{-- <h5 class="font-size-13 mb-1">{{ $row->submitted_at }}</h5> --}}
                                                    <p class="text-muted mb-1">
                                                        {{ $row->reply }}                                                   
                                                    </p>
                                                    <p>
                                                        <b>{{ $row->act_title.'.' }}</b>&nbsp;{{ date('M. d, Y. h:i A', strtotime($row->submitted_at)) }}                                              
                                                    </p> 
                                                </div>
                                        
                                            {{-- <div class="ml-3">
                                                <a href="" class="text-primary">Reply</a>
                                            </div> --}}
                                        </div>
                                    </div>                                    
                                    {{-- <div class="ml-3">
                                        <a href="" class="text-primary">Reply</a>
                                    </div> --}}
                                </div>
                            @endforeach

                                {{-- <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachedFile" name="attachedFile">
                                    <label class="custom-file-label" for="attachedFile">Choose file</label>
                                </div>
                                <i><b>Note:</b> File allowed: <br />Size: Maximum of 10 MB <br /> File Type: jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx</i>         
                                <br /><br />
                                <textarea class="form-control" name="external_link" rows="2" maxlength="500" placeholder="External Link: e.g. https://docs.google.com/spreadsheets/d/1Tj_Pt1nRAQvaHp9H4U/edit?usp=sharing">{{ old('external_link')}}</textarea> --}}

                                <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Reply</label>
                                <textarea class="form-control" name="remarks" rows="2" maxlength="500" autofocus></textarea>
            
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label">Response</label>                                
                                <select name="document_action_id" required
                                        class="form-control @error('document_action_id') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach($document_actions as $row)
                                        <option value="{{ $row->id }}" {{old ('document_action_id') == $row->id ? 'selected' : ''}}>{{ $row->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select your response.
                                </div>

                                {{-- <input type="hidden" name="route_id" value="{{ $doc[0]->route_id }}" readonly> --}}
                                <input type="hidden" name="document_id" value="{{ $doc[0]->document_id }}" readonly>
                                <input type="hidden" name="old_sender_id" value="{{ $sender_id }}" readonly> {{-- user_id from document/creator --}}

                            <br />
    
                            {{-- <div class="media mb-4">
                                <div class="mr-3">
                                    <img class="media-object rounded-circle avatar-xs" alt="" src="assets/images/users/avatar-2.jpg">
                                </div>
                                <div class="media-body">
                                    <h5 class="font-size-13 mb-1">David Lambert</h5>
                                    <p class="text-muted mb-1">
                                        Separate existence is a myth.
                                    </p>
                                </div>
                                <div class="ml-3">
                                    <a href="" class="text-primary">Reply</a>
                                </div>
                            </div>
    
                            <div class="media mb-4">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-16">
                                        S
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-size-13 mb-1">Steven Carlson</h5>
                                    <p class="text-muted mb-1">
                                        Separate existence is a myth.
                                    </p>
                                </div>
                                <div class="ml-3">
                                    <a href="" class="text-primary">Reply</a>
                                </div>
                            </div> --}}
    
                            {{-- <div class="text-center mt-4 pt-2">
                                <a href="#" class="btn btn-primary btn-sm">View more</a>
                            </div> --}}
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-primary w-md" name="reply_btn" value="reply_btn_val">Submit</button>   
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

            </div>
            <!-- end row -->

        </form>    
@endsection


{{-- @section('script')
@endsection --}}
@section('script')
    
<!-- validation -->
        {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
        <!-- Plugins js -->
        {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

    <!-- repeater -->
        <!-- form mask -->
        {{-- <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>  --}}
        <!-- form mask init -->
        {{-- <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js') }}"></script>  --}}

    <!-- bs custom file input plugin -->
        {{-- <script src="{{ URL::asset('assets/libs/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>  --}}
        {{-- <script src="{{ URL::asset('assets/js/pages/form-element.init.js') }}"></script>  --}}

    <!-- Form Advanced -->
        {{-- <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>  --}}

@endsection

@section('script-bottom')
    {{-- <script>
        $(document).ready(function(){
            $('#btnClear').click(function(){				
                if(confirm("Continue to delete old attachment?")){
                    document.getElementById('old_path').value = '';
                    //$("a").removeAttr("href");  remove href only but text retain
                    $("#old_path_div").hide();
                }					
            });
        });
    </script>   --}}

    {{-- < src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></> 
        <!-- Plugins js -->
        < src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></>  --}}

@endsection