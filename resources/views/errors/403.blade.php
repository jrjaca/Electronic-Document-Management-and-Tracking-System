@extends('layouts.master-without-nav')

@section('title')
    403 Error Page
@endsection

@section('body')
<body>
@endsection

@section('content')

        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-5">
                            <h1 class="display-2 font-weight-medium">403</h1>
                            <h4 class="text-uppercase">Unauthorized action.</h4>
                            <div class="mt-5 text-center">
                                <a class="btn btn-primary waves-effect waves-light" href="javascript:history.go(-1)">Back to previous page.</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-xl-6">
                        <div>
                            <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
