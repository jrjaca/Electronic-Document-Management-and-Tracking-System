@extends('layouts.master')

@section('title') Change Password @endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') Change Password  @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_3') Change Password @endslot
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
                        <a href="{{route('index')}}">
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
                    <div class="p-2">
                        
                        <form class="form-horizontal mt-4" method="POST" action="{{ route('user.update.password') }}">
                            @csrf
                            {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}
                            <div class="form-group">
                                <label for="password_old">Current Password</label>
                                <input type="password" class="form-control @error('password_old') is-invalid @enderror" name="password_old" id="password_old" placeholder="Enter current password" autofocus autocomplete="new-password">
                                @error('password_old')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter new password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"placeholder="Enter new password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Change Password</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
