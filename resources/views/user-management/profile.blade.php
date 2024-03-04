@extends('layouts.master')

@section('title') Profile @endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') Profile  @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') Profile @endslot
    @endcomponent

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
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="avatar-md profile-user-wid mb-4">
                                
                                {{-- <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="" class="img-thumbnail rounded-circle"> --}}
                                {{-- @if(\Auth::user()->avatar === null)
                                    <img src="{{ Gravatar::src(\Auth::user()->username) }}" style="border-radius: 50%" width="70px" height="70px"><br/> 
                                @else 
                                    <img src="{{asset('images/profile/'.\Auth::user()->avatar)}}" style="border-radius: 50%" width="70px" height="70px"><br/> 
                                @endif   --}}


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

                            </div>
                            <h5 class="font-size-15 text-truncate">{{ $user->first_name." ".$user->middle_name." ".$user->last_name." ".$user->suffix_name }}</h5>
                            <p class="text-muted mb-0 text-truncate">{{ $user->role_title }}</p>
                        </div>
                        <div class="col-sm-5">
                            <div class="pt-4">                            
                                <div class="row">
                                    <div class="col-11">
                                        {{-- <h5 class="font-size-15">125</h5> --}}
                                        @if ($user->activated == 0)
                                            <span class="badge badge-warning" style="font-size: 15px;">This account is not yet activated.</span>
                                        @endif
                                        {{-- <p class="text-muted mb-0">Registration status</p> --}}
                                    </div>
                                    <div class="col-1">
                                        {{-- <h5 class="font-size-15">$1245</h5> --}}
                                        {{-- <p class="text-muted mb-0">With pending transfer</p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4" style="text-align: center;">Personal and Office Information</h4>
                    {{-- <p class="text-muted mb-4"></p> --}}
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">ID Number/Username :</th>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">E-mail :</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Region/Office :</th>
                                    <td>{{ $user->office_title }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Department/Branch/Unit :</th>
                                    <td>{{ $user->department_title }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">LHIO/Section :</th>
                                    <td>{{ $user->section_title }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($pending_location_transfer){{-- with result / with pending approval --}}
                            {{-- @if ($pending_location_transfer->approved_transfer === null)not yet acted   --}}
                                <br />
                                <span class="text-danger">Note:</span><br/>      
                                <span class="text-danger">Please inform your administrator to approved this new location. </span><br/>           
                                <span class="text-danger">{{$pending_location_transfer->office_title.", ".$pending_location_transfer->department_title.", ".$pending_location_transfer->section_title}}. </span>    
                            {{-- @endif --}}
                        @endif
                        
                    </div>
                    <div class="mt-4" style="text-align: center;">
                        <a href="{{route('user.profile.edit', ['id' => Hasher::encode(\Auth::id())])}}" class="btn btn-primary waves-effect waves-light btn-sm">Edit Profile</a>
                        {{-- <i class="mdi mdi-arrow-right ml-1"></i> --}}
                    </div>
                </div>
            </div>
            <!-- end card -->

            {{-- <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Personal Information</h4>

                    <p class="text-muted mb-4">Hi I'm Cynthia Price,has been the industry's standard dummy text To an English person, it will seem like simplified English, as a skeptical Cambridge.</p>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Full Name :</th>
                                    <td>Cynthia Price</td>
                                </tr>
                                <tr>
                                    <th scope="row">Mobile :</th>
                                    <td>(123) 123 1234</td>
                                </tr>
                                <tr>
                                    <th scope="row">E-mail :</th>
                                    <td>cynthiaskote@gmail.com</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location :</th>
                                    <td>California, United States</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            <!-- end card -->
        </div>  
    </div>
    <!-- end row -->

@endsection