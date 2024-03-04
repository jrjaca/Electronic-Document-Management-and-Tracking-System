@extends('layouts.master')

@section('title') Publish Routed Document @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">

        <!-- Form Advanced -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
        <link rel="stylesheet"  href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') PUBLISH ROUTED DOCUMENT @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Publish new document @endslot
    @endcomponent  
{{-- {{dd($document[0]->title)}} --}}
    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
        <form id="my_form" action="{{ route('publish.routeddoc.submit') }}" method="POST" class="needs-validation outer-repeater" enctype="multipart/form-data" novalidate> <!--class="needs-validation outer-repeater"-->
            @csrf 
                <div class="row"> 
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <input type="hidden" name="document_id" value="{{ $document->document_id }}" readonly >
                                <input type="hidden" name="attached_document_id" value="{{ $attached_doc->attached_document_id }}" readonly >
                                <input type="hidden" name="attachment_name" value="{{ $attached_doc->attachment_name }}" readonly >
                                <input type="hidden" name="path" value="{{ $attached_doc->path }}" readonly >
                                <input type="hidden" name="size" value="{{ $attached_doc->size }}" readonly >
                                <input type="hidden" name="extension" value="{{ $attached_doc->extension }}" readonly >

                                <h4 class="card-title mb-4">Document Information</h4>
                                <div class="form-group row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label">Document name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="title" maxlength="100" value="{{ $document->title }}" required>
                                        <div class="invalid-feedback">
                                            Please provide document name.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label">Document Type</label>
                                    <div class="col-sm-9">
                                        <select name="document_type_id" required
                                                class="form-control @error('document_type_id') is-invalid @enderror">
                                            <option value=""></option>
                                            @foreach($document_types as $row)
                                                <option value="{{ $row->id }}" {{ $document->document_type_id == $row->id ? 'selected' : ''}}>{{ $row->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select document type.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Remarks</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="remarks" rows="2" maxlength="500">{{ $document->remarks }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">File to be published</label>
                                    <div class="col-sm-9">
                                        <a href="{{ asset('').$attached_doc->path }}" target="_blank">{{ $attached_doc->attachment_name }}</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                
                                <h4 class="card-title mb-4">Publish To</h4>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        {{-- <textarea class="form-control" name="remarks" rows="2" maxlength="500">{{ old('remarks')}}</textarea>
                                        <br /> --}}
                                        {{-- <div class="custom-control custom-switch mb-4" dir="ltr"> --}}
                                            {{-- <input type="checkbox" class="custom-control-input" id="is_hardcopy" name="is_hardcopy[]" 
                                                    value="hc" {{ (is_array(old('is_hardcopy')) and in_array('hc', old('is_hardcopy'))) ? ' checked' : '' }}>
                                            <label class="custom-control-label" for="is_hardcopy">Transmitting a hard copy document.</label> --}}
                                            <br /><i><b>Note:</b> Leave these fields blank to make the document visible to all staff, regardless of the office.</i> 
                                        {{-- </div> --}}
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Region</label>     
                                    <div class="col-sm-9">                                   
                                        <select class="custom-select" name="office" autofocus>
                                            <option selected value=""></option>
                                        @foreach($offices as $row)
                                            <option value="{{ $row->id }}">{{ $row->title }}</option>
                                        @endforeach
                                        </select>                      
                                        @error('region_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror    
                                    </div>                  
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Dept./Unit/Branch</label>  
                                    <div class="col-sm-9">                           
                                        <select class="custom-select" name="department"></select>
                                    </div>
                                </div>

                                {{-- <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">LHIO/Section</label>  
                                    <div class="col-sm-9">                           
                                        <select class="custom-select" name="section"></select>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>

                </div> <!-- end row -->

            {{-- <div class="form-group row justify-content-end"> --}}
                <div style="text-align: center;">
                    {{-- <button type="submit" class="btn btn-secondary w-md" name="pub_btn" value="draft_pub">Save as Unpublish</button> &nbsp;&nbsp;&nbsp; --}}
                    <button type="submit" class="btn btn-primary w-md" name="pub_btn" value="submit_pub">Publish</button>   
                </div> <br /><br />
            {{-- </div> --}}

        </form>    
@endsection

{{-- @section('script')
@endsection --}}
@section('script')
    
<!-- validation -->
        {{-- <script src="{{ asset('backend') }}/libs/parsleyjs/parsley.min.js"></script>
        <script src="{{ asset('backend') }}/js/pages/form-validation.init.js"></script> --}}
        <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 

    <!-- repeater -->
        <!-- form mask -->
        <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script> 
        <!-- form mask init -->
        <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js') }}"></script> 

    <!-- bs custom file input plugin -->
        <script src="{{ URL::asset('assets/libs/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/pages/form-element.init.js') }}"></script> 

    <!-- Form Advanced -->
        <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script> 

    <!-- form mask -->
        {{-- <script src="{{ URL::asset('assets/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>  --}}
        {{-- <script src="{{ URL::asset('assets/libs/inputmask/inputmask.min.js') }}"></script> --}}
        <!-- form mask init -->
        {{-- <script src="{{ URL::asset('assets/js/pages/form-mask.init.js') }}"></script>  --}}

@endsection

@section('script-bottom')

    {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

    {{-- Populate Departments --}}
    <script type="text/javascript">
        $(document).ready(function(){
            $('select[name="office"]').on('change',function(){
                var officeid = $(this).val();
                //alert(officeid);
                //clear department and sections
                $('select[name="department"]').empty();
                $('select[name="section"]').empty();
                if (officeid) {//with selected item
                    spinner('Loading...');
                    $.ajax({
                        type:"GET",
                        url: "{{ url('location-management/departments-office/show/') }}"+"/"+officeid,  
                        dataType: "json",
                        cache: false,
                        success: function(result){
                            //alert(JSON.stringify(result));
                            var d = $('select[name="department"]').empty();
                                    $('select[name="department"]').append('<option value="""></option>'); //first item
                            $.each(result, function(key, value){

                                $('select[name="department"]').append('<option value="'+value.id+'">'+value.title+'</option>');

                            });
                        },
                        error: function (request, status, error) {
                            //alert(request.responseText);
                        },
                    });
                } else {   
                    //alert('No selected item.');
                }
            });
        });
    </script>
    {{-- /Populate Departments --}}

    {{-- Populate Sections --}}
    {{-- <script type="text/javascript">
        $(document).ready(function(){
            $('select[name="department"]').on('change',function(){
                var deptid = $(this).val();
                //alert(deptid);
                //clear sections
                $('select[name="section"]').empty();
                if (deptid) {//with selected item
                    spinner('Loading...');
                    $.ajax({
                        type:"GET",
                        url: "{{ url('location-management/sections-department/show/') }}"+"/"+deptid,  
                        dataType: "json",
                        cache: false,
                        success: function(result){
                            //alert(JSON.stringify(result));
                            var d = $('select[name="section"]').empty();
                                    //$('select[name="section"]').append('<option value="""></option>'); //first item. Remove this bec. sometimes it is required
                            $.each(result, function(key, value){

                                $('select[name="section"]').append('<option value="'+value.id+'">'+value.title+'</option>');

                            });
                        },
                        error: function (request, status, error) {
                            //alert(request.responseText);
                        },
                    });
                } else {   
                    //alert('No selected item.');
                }
            });
        });
    </script> --}}
    {{-- /Populate Sections --}}

@endsection