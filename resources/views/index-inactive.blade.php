@extends('layouts.master')

@section('title') For activation @endsection

@section('content')

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-2">
                    {{-- <h5 class="display-2 font-weight-medium">Your registration has been successful</h5> --}}
                    <br/><br/>
                    <h1 style="color: green;">Your registration has been successful!</h1>
                    <h4 style="color: red;">Please inform your I.T. administrator to activate your account.</h4>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div>
                    <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>     

@endsection
