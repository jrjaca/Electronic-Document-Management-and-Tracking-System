<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('index')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        {{-- <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="22"> --}}
                        <img src="{{ asset('assets/images/document.png') }}" alt="" height="25">
                    </span>
                    <span class="logo-lg">
                        {{-- <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17"> --}}
                        <img src="{{ asset('assets/images/document.png') }}" alt="" height="50">
                    </span>
                </a>

                <a href="{{route('index')}}" class="logo logo-light">
                    <span class="logo-sm">
                        {{-- <img src="{{ asset('assets/images/logo-light.svg') }}" alt="" height="22"> --}}
                        <img src="{{ asset('assets/images/document.png') }}" alt="" height="25">
                    </span>
                    <span class="logo-lg">
                        {{-- <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="19"> --}}
                        <img src="{{ asset('assets/images/document.png') }}" alt="" height="50">
                    </span>
                </a>
            </div>
            <!-- /LOGO -->

            <!-- Show/Hide Menus -->
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- /Show/Hide Menus -->
            
            {{-- <h4><span class="display-4">Document Tracking System</span></a></h5> --}}
            <h1 style="color: #111; font-family: 'Open Sans', sans-serif; font-size: 20px; font-weight: 300; line-height: 32px; margin: 0 100 72px; text-align: center;">
                {{env('APP_NAME')}} (DTS)</h1>


        </div>

        <div class="d-flex">

            @if(Auth::check())
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{-- <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                            alt="Header Avatar"> --}}
                @if(\Auth::user()->avatar === null)
                    <img src="{{ Gravatar::src(\Auth::user()->username) }}" style="border-radius: 50%" width="40px" height="40px">
                @else 
                    <img src="{{asset('images/profile/'.\Auth::user()->avatar)}}" style="border-radius: 50%" width="40px" height="40px">
                @endif

                <span class="d-none d-xl-inline-block ml-1">{{\Auth::user()->first_name." ".\Auth::user()->last_name}}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <a class="dropdown-item" href="{{route('user.profile.view', ['id' => Hasher::encode(\Auth::id())])}}"><i class="bx bx-user font-size-16 align-middle mr-1"></i> Profile</a>
                        <a class="dropdown-item" href="{{route('user.change.password', ['id' => Hasher::encode(\Auth::id())])}}"><i class="bx bx-key font-size-16 align-middle mr-1"></i> Change Password</a>
                        <a class="dropdown-item" href="javascript:void(0);"  onclick="about()">
                            <i class="bx bx-info-circle font-size-16 align-middle mr-1"></i> About</a>                    
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> {{ __('Logout') }} </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @else 
                {{-- <div class="dropdown d-inline-block">
                    <a href="{{ url('login') }}">
                        <span style="font-size: 2em; color: blue;">
                            <i class="fas fa-sign-in-alt" title="Login"></i>
                        </span></a>
                </div>         --}}

                {{-- <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                        <a href="{{ url('login') }}">
                            <span style="font-size: 2em; color: blue;">
                                <i class="fas fa-sign-in-alt" title="Login"></i>
                            </span></a>
                    </button>
                </div> --}}

                {{-- <div class="dropdown d-inline-block"> --}}
                    <button type="button" class="btn btn-outline-primary"> 
                            <a href="{{ url('login') }}" style="color:inherit"> LOGIN </a>
                    </button>     
                {{-- </div>    --}}
            @endif

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>
            
        </div>
    </div>
</header>

        <!-- About Details Modal (script is in footer-script)-->
        <div id="aboutModal" class="modal fade aboutModal" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title" id="aboutModalLabel">About</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            
                            {{-- <p class="mb-1">User Name: <span class="text-primary" id="username"></span></p> --}}
                            <div class="table-responsive" style="text-align: center;">

                                <img src="{{ asset('assets/images/document.png') }}" alt="" height="50"><br/>  
                                <span class="text-primary">Version {{$global_setting_version}}</span> <br/>          
                                <span class="text-primary">{{env('APP_NAME')}}</span> <br/><br/>    
                                
                                <span class="text-primary">{{$global_setting_developed_by." (".$global_setting_developed_from.")"}}</span> <br/>
                                    <span class="text-primary">{{$global_setting_company_name}}</span> <br/>
                                        <span class="text-primary">© {{$global_setting_year}}</span>                               
                                <br /><br />

                                @if(Auth::check())
                                    <p><a data-toggle="collapse" href="#userCollapse" style="color: brown;">SHOW USER INFORMATION</a></p>
                                    <div class="collapse multi-collapse" id="userCollapse">   
                                        @if(\Auth::user()->avatar === null)
                                            <img src="{{ Gravatar::src(\Auth::user()->username) }}" style="border-radius: 50%" width="40px" height="40px"><br/>  
                                        @else 
                                            <img src="{{asset('images/profile/'.\Auth::user()->avatar)}}" style="border-radius: 50%" width="60px" height="60px"><br/>  
                                        @endif     
                                        <span class="text-primary">{{\Auth::user()->first_name." ".\Auth::user()->middle_name." ".\Auth::user()->last_name." ".\Auth::user()->suffix_name}}</span> <br/>
                                        <span class="text-primary">{{\Auth::user()->username}}</span>&nbsp;(<span class="text-primary" id="role_desc_profile"></span>)<br/>
                                        <span class="text-primary">{{\Auth::user()->email}}</span> <br/>
                                        <span class="text-primary" id="location_desc_profile"></span><br/>
                                    </div>
                                @endif
                            </div>
                            {{-- <p class="text-muted mb-0">$ 145 x 1</p>
                            <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                            <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                            <h6 class="m-0 text-right">Sub Total:</h6> --}}

                        </div>
                        <div class="modal-footer">
                            {{-- see footer script --}}
                            <span id='dynamic_datetime_aboutmodal'>Dynamic Current Date and Time</span>
                        </div>
                </div>
            </div>
        </div>
        <!-- /About Details Modal-->
