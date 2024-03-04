@extends('layouts.master')

@section('title') Announcement @endsection

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
        @slot('title') ANNOUNCEMENT @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Update announcement @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->
        <form id="my_form" action="{{ route('publish.announcement.submit') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate> <!--class="needs-validation outer-repeater"-->
            @csrf 
            <div class="row"> 
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <input type="hidden" name="announcement_id" value="{{ $announcement['announcement_id'] }}" readonly >

                            {{-- <h4 class="card-title mb-4">Document Information</h4> --}}
                            <div class="form-group row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label">Title name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="title" maxlength="100" value="{{ $announcement['title'] }}" autofocus>
                                    {{-- <div class="invalid-feedback">
                                        Please provide document name.
                                    </div> --}}
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label">Sub-title name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sub_title" maxlength="100" value="{{ $announcement['sub_title'] }}" autofocus>
                                    {{-- <div class="invalid-feedback">
                                        Please provide document name.
                                    </div> --}}
                                </div>
                            </div>
                            
                            <div class="form-group row mb-4">
                                <label for="horizontal-details-input" class="col-sm-3 col-form-label">Details</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="details" rows="2" maxlength="1000">{{ $announcement['details'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

            {{-- <div class="form-group row justify-content-end"> --}}
            @if(Gate::check('update_publish_announcement'))
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary w-md" name="save_btn" value="submit_val">Publish</button>   
                </div> <br /><br />
            @endif
            {{-- </div> --}}

        </form>    
@endsection


{{-- @section('script')
@endsection --}}
@section('script')
    
<!-- validation -->
        {{-- <script src="{{ asset('backend') }}/libs/parsleyjs/parsley.min.js"></script>
        <script src="{{ asset('backend') }}/js/pages/form-validation.init.js"></script> --}}
        {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
        <!-- Plugins js -->
        {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

    <!-- repeater -->
        <!-- form mask -->
        {{-- <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>  --}}
        <!-- form mask init -->
        {{-- <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js') }}"></script>  --}}

    <!-- bs custom file input plugin -->
        {{-- <script src="{{ URL::asset('assets/libs/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/pages/form-element.init.js') }}"></script>  --}}

    <!-- Form Advanced -->
        {{-- <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script> 
        <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>  --}}

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