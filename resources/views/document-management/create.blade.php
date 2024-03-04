@extends('layouts.master')

@section('title') New Document @endsection

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
        @slot('title') DOCUMENT @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Create new document @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
        <form id="my_form" action="{{ route('document.submit') }}" method="POST" class="needs-validation outer-repeater" enctype="multipart/form-data" novalidate> <!--class="needs-validation outer-repeater"-->
            @csrf 

            <!-- start row Barcode-->   
            {{-- <div class="row">         
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                            @endphp
                            <i><b>Note: </b>
                                Crop the image inside the box using <img src="{{asset('assets/images/snipping tool.png')}}" style="border-radius: 50%" width="135px" height="25px"> or any cropping utilities and paste to any Microsoft Office apps. <br />
                                Paste the image from MS Word, right click on the image and select "Wrap Text" then "Behind text". Now you can place the image on  upper rigth corner. <br />
                                <h style="color:red;">Refreshing the page will automatically renew the barcode.</h><br /><br /> --}}
                                {{-- <div style="text-align: center; width:200px; height:50px; background-color:powderblue;"> --}}     
                            {{-- </i>                                       
                            <div style="margin: auto; text-align: center; width: 250px; height: 80px; border: 1px solid grey; border-style: dashed; padding: 12px;">
                                <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($raw_code, $generatorPNG::TYPE_CODE_128)) }}", width="220px" height="50px"> <br />
                                <h1 style="font-size:14px;">{{ $raw_code }}</h1>     
                            </div>  
                        </div>
                    </div>
                </div>
            </div>  --}}
            <!-- end row Barcode-->

            <div class="row"> 
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-body">
                                
                                <input type="hidden" name="barcode" value="{{ $raw_code }}" readonly >

                                <h4 class="card-title mb-4">Document Information:</h4>
                                <div class="form-group row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label">Document name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="title" maxlength="100" value="{{ old('title')}}" required autofocus>
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
                                                <option value="{{ $row->id }}" {{old ('document_type_id') == $row->id ? 'selected' : ''}}>{{ $row->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select document type.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label">For</label>
                                    <div class="col-sm-9">
                                        <select name="document_action_id" id="ddd" required
                                                class="form-control @error('document_action_id') is-invalid @enderror">
                                            <option value=""></option>
                                            @foreach($document_actions as $row)
                                                <option value="{{ $row->id }}" {{old ('document_action_id') == $row->id ? 'selected' : ''}}>{{ $row->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select the purpose.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Remarks</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="remarks" rows="2" maxlength="500">{{ old('remarks')}}</textarea>
                                        {{-- <br /><br /> --}}
                                        <div class="custom-control custom-switch mb-4" dir="ltr" style="display:none">
                                            <input type="checkbox" class="custom-control-input" id="email_notification" name="email_notification[]" 
                                                    value="em" {{ (is_array(old('email_notification')) and in_array('em', old('email_notification'))) ? ' checked' : '' }}>
                                            <label class="custom-control-label" for="email_notification">Received email notification.</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-4" dir="ltr" style="display:none">
                                            <input type="checkbox" class="custom-control-input" id="is_hardcopy" name="is_hardcopy[]" 
                                                    value="hc" {{ (is_array(old('is_hardcopy')) and in_array('hc', old('is_hardcopy'))) ? ' checked' : '' }}>
                                            <label class="custom-control-label" for="is_hardcopy">Transmitting a hard copy document.</label>
                                            <br /><i><b>Note:</b> If this is a hard copy, please print the barcode and attached to the hard copy document.</i> 
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Courier Name</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" name="courier_user_id" data-placeholder="User who will transport the hard copy document." style="width: 100%">
                                            <option value=""></option>
                                            @foreach($users as $row)
                                                    <option value="{{ $row->id }}" {{old ('courier_user_id') == $row->id ? 'selected' : ''}}>
                                                        {{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->suffix_name }}
                                                    </option>
                                            @endforeach 
                                        </select>                                        
                                    </div>       
                                </div> --}}

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Forward To:</h4>

                                <div data-repeater-list="outer-group" class="outer">
                                    <div data-repeater-item class="outer">
                                        <div class="form-group row mb-4">
                                            <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Recipients</label> <!--<span style="color: red;">*</span> :-->
                                            <div class="col-sm-9">
                                                <select class="select2 form-control select2-multiple" multiple="multiple" name="forwarded_to_users" required
                                                        data-placeholder="You can choose multiple name of recipients." style="width: 100%">
                                                    <option value=""></option>
                                                    @foreach($users as $row)
                                                        @if (Auth::user()->id != $row->id)
                                                            <option value="{{ $row->id }}" 
                                                                {{ (is_array(old('outer-group.0.forwarded_to_users')) and in_array($row->id, old('outer-group.0.forwarded_to_users'))) ? 'selected' : '' }}> 
                                                                {{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->suffix_name }}
                                                            </option>
                                                        @endif    
                                                    @endforeach                                    
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please provide user.
                                                </div>
                                                {{-- <br /><br /> --}}
                                                <div class="custom-control custom-switch mb-4" dir="ltr" style="display:none"> 
                                                    <input type="checkbox" class="custom-control-input" id="is_confidential" name="is_confidential" 
                                                            value="co" {{ (is_array(old('outer-group.0.is_confidential')) and in_array('co', old('outer-group.0.is_confidential'))) ? ' checked' : '' }}>
                                                    <label class="custom-control-label" for="is_confidential">Confidential document.</label>
                                                    <br /><i><b>Note:</b> Turn this on to make the document visible and downloadable only to the specified user/s above.</i>  
                                                </div> 
                                            </div>  
                                        </div>
                                    </div>   
                                </div> 

                                <h4 class="card-title mb-4">Attachment:</h4>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Attach File</label> 
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="attachedFile" name="attachedFile" required>
                                            <label class="custom-file-label" for="attachedFile">Choose file</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please attached file.
                                        </div>
                                        <br /><br />
                                        <i><b>Note:</b> File allowed: <br />Size: Maximum of 10 MB <br /> File Type: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx</i>  
                                    </div>       
                                </div>

                                <div class="form-group row mb-4">
                                    <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Attachment Link</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="external_link" rows="2" maxlength="500" placeholder="e.g. https://docs.google.com/spreadsheets/d/1Tj_Pt1nRAQvaHp9H4U/edit?usp=sharing">{{ old('external_link')}}</textarea>   
                                        <br /><i><b>Note:</b> Copy paste link of large document resides at Google Drive, File Server and Etc.</i>                                      
                                    </div>       
                                </div>

                            </div>
                        </div>
                    </div>
                
            </div> <!-- end row -->

            {{-- <div class="form-group row justify-content-end"> --}}
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-secondary w-md" name="save_btn" value="draft_val">Save as Draft</button> &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-primary w-md" name="save_btn" value="submit_val">Submit</button>   
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

{{-- @section('script-bottom')

    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 

@endsection --}}