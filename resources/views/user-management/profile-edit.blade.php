@extends('layouts.master')

@section('title') Edit Profile @endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') Edit Profile  @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') <a title="Back to Profile" href="{{route('user.profile.view', ['id' => Hasher::encode(\Auth::id())])}}">Profile</a> @endslot
        @slot('li_3') Edit Profile @endslot
    @endcomponent

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->    

    <div class="row">
        <div class="col-lg-5" style="margin: auto;">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                {{-- <h5 class="text-primary">Welcome Back !</h5> --}}
                                {{-- <p>It will seem like simplified</p> --}}
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0"> 
                    <div>
                        <form action="{{ route('user.avatar.update') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            {{-- <a href="{{route('index')}}"> --}}
                            @if(\Auth::user()->avatar === null)
                                <a href="{{Gravatar::src(\Auth::user()->username)}}" target="_blank">
                            @else 
                                <a href="{{asset('images/profile/'.\Auth::user()->avatar)}}" target="_blank">
                            @endif    
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        {{-- <img src="assets/images/logo.svg" alt="" class="rounded-circle" height="34"> --}}
                                        @if(\Auth::user()->avatar === null)
                                            <img src="{{ Gravatar::src(\Auth::user()->username) }}" style="border-radius: 50%" width="70px" height="70px">
                                        @else 
                                            <img src="{{asset('images/profile/'.\Auth::user()->avatar)}}" style="border-radius: 50%" width="70px" height="70px">
                                        @endif
                                    </span>
                                </div>
                            </a>

                            {{-- <img src="{{ asset($site_setting->logo_path) }}" height="90px" width="125px"></div></td> --}}                           
                            {{-- old picture   --}}
                            <input type="hidden" name="avatar_old" readonly value="{{\Auth::user()->avatar}}">
                            {{-- select new picture --}}
                            <input type="file" name="avatar_new" onchange="displayNewImage(this);">
                            <img src="" id="mainlogo_prev">
                            {{-- update button --}}
                            <button class="btn btn-sm btn-primary" type="submit">Update Profile Image</button>
                    
                        </form>    
                    </div>
                    <div class="p-2">

                        {{-- <form id="my_form" action="{{ route('manage.roles.update') }}" method="POST" class="needs-validation" novalidate>                       
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" class="form-control" id="hashid" name="hashid" readonly required> --}}
                        <form method="POST" class="form-horizontal mt-4" action="{{ route('user.profile.update') }}">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}" readonly required>
                            <div class="form-group">
                                <label for="username">ID Number (username)*</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{$user->username}}" required name="username" id="username" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="useremail">Email*</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{$user->email}}" id="useremail" name="email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name*</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{$user->last_name}}" required name="last_name" id="last_name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name*</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{$user->first_name}}" required name="first_name" id="first_name">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name*</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" value="{{$user->middle_name}}" required name="middle_name" id="middle_name">
                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="suffix_name">Suffix Name</label>
                                <input type="text" class="form-control @error('suffix_name') is-invalid @enderror" value="{{$user->suffix_name}}" name="suffix_name" id="suffix_name" placeholder="E.g. Jr, Sr.">
                                @error('suffix_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if ($pending_location_transfer){{-- with result / with pending approval --}}
                                {{-- @if ($pending_location_transfer->approved_transfer === null)not yet acted   --}}
                                    <span class="text-danger">Note:</span><br/>      
                                    <span class="text-danger">Please inform your administrator to approved this new location. </span><br/>           
                                    <span class="text-danger">{{$pending_location_transfer->office_title.", ".$pending_location_transfer->department_title.", ".$pending_location_transfer->section_title}}. </span>    
                                {{-- @endif --}}

                            @else 
                                {{-- display locations and allow to edit if no pending transfer --}}
                                <input type="hidden" name="office_old" value="{{$user->office_id}}" readonly>
                                    <div class="form-group">
                                        <label class="control-label">Region*</label>                                        
                                        <select class="custom-select" name="office" required>
                                            <option value=""></option>
                                            @foreach($offices as $row_o)
                                                @if($row_o->id == $user->office_id)
                                                    <option value="{{ $row_o->id }}" selected>{{ $row_o->title }}</option>
                                                @else
                                                    <option value="{{ $row_o->id }}">{{ $row_o->title }}</option>    
                                                @endif
                                                
                                            @endforeach
                                        </select>                      
                                        @error('region_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror                      
                                    </div>

                                    <input type="hidden" name="department_old" value="{{$user->department_id}}" readonly>
                                    <div class="form-group">
                                        <label class="control-label">Dept./Unit/Branch*</label>
                                        <select class="custom-select" name="department" required>
                                            <option value=""></option>
                                            @foreach($departments as $row_d)
                                                @if($row_d->id == $user->department_id) 
                                                    <option value="{{ $row_d->id }}" selected>{{ $row_d->title }}</option>
                                                @else
                                                    <option value="{{ $row_d->id }}">{{ $row_d->title }}</option>    
                                                @endif                                        
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" name="section_old" value="{{$user->section_id}}" readonly>
                                    <div class="form-group">
                                        <label class="control-label">LHIO/Section</label>
                                        <select class="custom-select" name="section">
                                            <option value=""></option>
                                            @foreach($sections as $row_s)
                                                @if($row_s->id == $user->section_id) 
                                                    <option value="{{ $row_s->id }}" selected>{{ $row_s->title }}</option>
                                                @else
                                                    <option value="{{ $row_s->id }}">{{ $row_s->title }}</option>    
                                                @endif                                        
                                            @endforeach</select>
                                </div>
                            
                            @endif

                            <div class="mt-4">
                                <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Update</button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>    

        </div>
    </div>

@endsection

@section('script-bottom')

    {{-- Display new image to upload--}}
    <script type="text/javascript">

        function displayNewImage(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#mainlogo_prev')
                        .attr('src', e.target.result)
                        .height(80)
                        .width(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>    

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