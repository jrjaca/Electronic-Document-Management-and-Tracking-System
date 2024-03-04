@extends('layouts.master-without-nav')

@section('title')
    Register
@endsection

@section('body')
    <body>
@endsection

@section('content')

        <div class="home-btn d-none d-sm-block">
            <a href="{{route('index')}}" class="text-dark"><i class="fas fa-home h2"></i></a>
        </div>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-soft-primary">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">User Registration</h5>
                                            <p>Document Tracking System</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div>
                                    <a href="{{route('index')}}">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form method="POST" class="form-horizontal mt-4" action="{{ route('register') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="username">ID Number (username)*</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{old('username')}}" required name="username" id="username" autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="useremail">Email*</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" id="useremail" name="email" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="last_name">Last Name*</label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{old('last_name')}}" required name="last_name" id="last_name">
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="first_name">First Name*</label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{old('first_name')}}" required name="first_name" id="first_name">
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="middle_name">Middle Name*</label>
                                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" value="{{old('middle_name')}}" required name="middle_name" id="middle_name">
                                            @error('middle_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="suffix_name">Suffix Name</label>
                                            <input type="text" class="form-control @error('suffix_name') is-invalid @enderror" value="{{old('suffix_name')}}" name="suffix_name" id="suffix_name" placeholder="E.g. Jr, Sr.">
                                            @error('suffix_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Region*</label>                                        
                                            <select class="custom-select" name="office" required>
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

                                        <div class="form-group">
                                            <label class="control-label">Dept./Unit/Branch*</label>
                                            <select class="custom-select" name="department" required></select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">LHIO/Section</label>
                                            <select class="custom-select" name="section"></select>
                                        </div>

                                        <div class="form-group">
                                            <label for="userpassword">Password*</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required id="userpassword">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="userpassword">Confirm Password*</label>
                                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Register</button>
                                        </div>
    
                                    </form>
    
                                </div>
                            </div>
    
                        </div>
    
                        <div class="mt-5 text-center">
                            <p>Already have an account ? <a href="{{url('login')}}" class="font-weight-medium text-primary"> Login In here </a> </p>
                            <p>© 2020 Philippine Health Insurance Corporation <i class="mdi mdi-heart text-danger"></i> PRO NCR-South</p>
                        </div>
    
    
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('script-bottom')

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
    <script type="text/javascript">
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
    </script>
    {{-- /Populate Sections --}}

@endsection