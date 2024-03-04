
        @yield('css')

        {{--<!-- Toastr Notification css -->
                <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}"> --}}

        <!-- spinner/waiting -->
                <!-- Core stylesheet -->
                <link rel="stylesheet" href="{{ URL::asset('spinner/css/modal-loading.css') }}">
                <!-- CSS3 animations -->
                <link rel="stylesheet" href="{{ URL::asset('spinner/css/modal-loading-animate.css') }}">
        <!-- /spinner/waiting -->

        <!-- App css -->
                <link href="{{ URL::asset('assets/css/bootstrap-dark.min.css')}}" id="bootstrap-dark" rel="stylesheet" type="text/css" />
                <link href="{{ URL::asset('assets/css/bootstrap.min.css')}}" id="bootstrap-light" rel="stylesheet" type="text/css" />
                <link href="{{ URL::asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
                <link href="{{ URL::asset('assets/css/app-rtl.min.css')}}" id="app-rtl" rel="stylesheet" type="text/css" />
                <link href="{{ URL::asset('assets/css/app-dark.min.css')}}" id="app-dark" rel="stylesheet" type="text/css" />
                <link href="{{ URL::asset('assets/css/app.min.css')}}" id="app-light" rel="stylesheet" type="text/css" />