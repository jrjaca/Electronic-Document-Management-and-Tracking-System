@extends('layouts.master')

@section('title') Edit Published Document @endsection

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
        @slot('title') EDIT PUBLISHED DOCUMENT @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') <a title="Published List" href="{{route('publish.list')}}">Published List</a> @endslot
        @slot('li_3') Edit Published document @endslot
    @endcomponent  


<!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
        <form id="my_form" action="{{ route('publish.update') }}" method="POST" class="needs-validation outer-repeater" enctype="multipart/form-data" novalidate> <!--class="needs-validation outer-repeater"-->
            @csrf 
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            
                            <input type="hidden" name="published_id" value="{{ $publish['published_id'] }}" readonly >

                                <h4 class="card-title mb-4">Document Information</h4>
                                <div class="form-group row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label">Document name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="title" maxlength="100" value="{{ $publish['title'] }}" required autofocus>
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
                                            @foreach($document_types as $dtrow)
                                                <option value="{{ $dtrow->id }}" {{ $publish['document_type_id'] == $dtrow->id ? 'selected' : '' }}>{{ $dtrow->title }}</option>
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
                                        <textarea class="form-control" name="remarks" rows="2" maxlength="500">{{ $publish['remarks'] }}</textarea>
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
                                    <br /><i><b>Note:</b> Leave these fields blank to make the document visible to all staff, regardless of the office.</i> 
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Region</label>     
                                <div class="col-sm-9">                                   
                                    <select class="custom-select" name="office">
                                        <option value=""></option>
                                        @foreach($offices as $rowof)    
                                            @if($rowof->id == $publish['office_id'])
                                                <option value="{{ $rowof->id }}" selected>{{ $rowof->title }}</option>
                                            @else
                                                <option value="{{ $rowof->id }}">{{ $rowof->title }}</option>    
                                            @endif
                                        @endforeach
                                    </select>              
                                </div>                  
                            </div>
                            
                            <div class="form-group row mb-4">
                                <label for="horizontal-remarks-input" class="col-sm-3 col-form-label">Dept./Unit/Branch</label>     
                                <div class="col-sm-9">                                   
                                    <select class="custom-select" name="department">
                                        <option value=""></option>
                                        @foreach($departments_per_office as $rowde)    
                                            @if($rowde->id == $publish['department_id'])
                                                <option value="{{ $rowde->id }}" selected>{{ $rowde->title }}</option>
                                            @else
                                                <option value="{{ $rowde->id }}">{{ $rowde->title }}</option>    
                                            @endif
                                        @endforeach
                                    </select>              
                                </div>                  
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

            {{-- <div class="form-group row justify-content-end"> --}}
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary w-md" name="update_btn" value="update_pub">Update and Publish</button>
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
@endsection