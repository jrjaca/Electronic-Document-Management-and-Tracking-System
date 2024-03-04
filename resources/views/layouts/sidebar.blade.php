<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ route('index') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i><!--<span class="badge badge-pill badge-info float-right">03</span>-->
                        <span>Home</span>
                    </a>
                    <a href="{{route('search')}}">
                        <i class="bx bxs-paper-plane"></i>
                        <span>Published Document</span>
                    </a>
                    {{--<ul class="sub-menu" aria-expanded="false">
                        <li><a href="index">Default</a></li>
                        <li><a href="dashboard-saas">Saas</a></li>
                        <li><a href="dashboard-crypto">Crypto</a></li> 
                    </ul>--}}
                </li>

                @if(Auth::check())
                    @if(Gate::check('user_view') || Gate::check('role_view') || Gate::check('permission_view') || Gate::check('user_password_reset') ||
                        Gate::check('location_transfer'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-group"></i>
                                <span>User Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                @can('user_view')
                                    <li><a href="javascript: void(0);" class="has-arrow">Users</a>
                                        <ul class="sub-menu" aria-expanded="true">
                                            <li><a href="{{route('manage.valid.users')}}">Registered Users</a></li>
                                            <li><a href="{{route('manage.registered.users')}}">Registration Activation</a></li>
                                            <li><a href="{{route('manage.deactivated.users')}}">Deactivated Users</a></li>
                                            <li><a href="{{route('manage.users')}}">All Users</a></li>
                                        </ul>
                                    </li>
                                @endcan     
                                @can('role_view')
                                    <li><a href="{{route('manage.roles')}}">Roles</a></li>
                                @endcan     
                                @can('permission_view')
                                    <li><a href="{{route('manage.permissions')}}">Permissions</a></li>
                                @endcan    
                                @can('user_password_reset')
                                    <li><a href="{{route('manage.users.list.reset')}}">User Password Reset</a></li>
                                @endcan   
                                @can('location_transfer')
                                    <li><a href="javascript: void(0);">Transfer Approval</a>                                
                                        <ul class="sub-menu" aria-expanded="true">
                                            @cannot('su'){{-- show if not superus --}}
                                                <li><a href="{{route('manage.user.change.location.sameoffice')}}">Within Office</a></li>
                                                <li><a href="{{route('manage.user.change.location.initial')}}">Initital Approval</a></li>
                                            @endcan
                                            <li><a href="{{route('manage.user.change.location.final')}}">Final Approval</a></li>
                                        </ul>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @if(Gate::check('office_view') || Gate::check('department_view') || Gate::check('section_view'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-map"></i>
                                <span>Location Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('office_view')
                                    <li><a href="{{route('manage.location.offices')}}">Regions/Offices</a></li>
                                @endcan     
                                @can('department_view')
                                    <li><a href="{{route('manage.location.departments')}}">Dept./Units/Branches</a></li>
                                @endcan     
                                @can('section_view')
                                    <li><a href="{{route('manage.location.sections')}}">LHIOs/Sections</a></li>
                                @endcan     
                            </ul>
                        </li>
                    @endif 

                    @if(Gate::check('documenttype_view'))
                        <li>
                            <a href="{{route('manage.doctypes')}}">
                                <i class="bx bx-copy-alt"></i>
                                <span>Doc. Type Management</span>
                            </a>
                        </li>
                    @endif

                    @if(Gate::check('documentaction_view'))
                        <li>
                            <a href="{{route('manage.docactions')}}">
                                <i class="bx bxs-calendar-check"></i>
                                <span>Doc. Action Management</span>
                            </a>
                        </li>
                    @endif

                    {{-- @if(Gate::check('generate_barcode') || Gate::check('received_document') || Gate::check('released_document') ||
                        Gate::check('tag_as_terminal') || Gate::check('track_document') || Gate::check('search_document') ||
                        Gate::check('create_document') || Gate::check('disable_document') || Gate::check('view_download_attachment')) --}}
                    
                    @if(Gate::check('create_document') && Gate::check('search_document'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-paste"></i>
                                <span>Doc. Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                {{-- @can('create_document') --}}
                                    <li><a href="{{route('document.create')}}">Create New</a></li>
                                    <li><a href="{{route('document.draft.list')}}">Drafts</a></li>
                                    <li><a href="{{route('document.submitted.list')}}">My Documents</a></li>
                                {{-- @endcan   --}}
                                    {{-- <li role="separator" class="divider" style="background-color: white;">&nbsp;</li> --}}
                                {{-- @can('received_document') --}}
                                    <li><a href="{{route('document.received.list')}}">Inbox</a></li>
                                {{-- @endcan   --}}
                                {{-- @can('released_document') --}}
                                    <li><a href="{{route('document.released.list')}}">Sent</a></li>
                                {{-- @endcan   --}}
                            </ul>
                        </li>
                    @endif

                    @if(Gate::check('track_document') || Gate::check('tag_as_terminal') || Gate::check('released_document') ||
                        Gate::check('received_document') || Gate::check('generate_barcode'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxl-squarespace"></i>
                                <span>Routing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                {{-- @can('track_document') @can('tag_as_terminal') @can('released_document') @can('received_document') --}}
                                    <li><a href="{{route('route.index')}}">Routing Management</a></li>
                                {{-- @endcan      --}}
                                {{-- @can('generate_barcode')
                                    <li><a href="{{route('route.create')}}">Route Document</a></li>
                                @endcan --}}
                                @can('generate_barcode')
                                    <li><a href="{{route('route.draft.list')}}">Draft</a></li>
                                @endcan
                                @can('generate_barcode')
                                    <li><a href="{{route('route.finalized.list')}}">Finalized (Created)</a></li>
                                @endcan

                                {{-- <hr style="height:2px;border-width:0;color:gray;background-color:gray"> --}}
                                {{-- <hr> --}}                                
                                <hr style="background-color:gray">

                                @can('received_document')
                                    <li><a href="{{route('route.received.list')}}">Received (Pending)</a></li>
                                @endcan  
                                @if(Gate::check('generate_barcode') || Gate::check('released_document'))
                                    <li><a href="{{route('route.released.list')}}">Released</a></li>
                                @endif
                                @can('tag_as_terminal')
                                    <li><a href="{{route('route.terminal.list')}}">Terminal</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @if(Gate::check('publish_document') || Gate::check('view_publish_document') || Gate::check('unpublish_document') ||
                        Gate::check('update_publish_document') || Gate::check('view_deleted_publish_document'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-street-view"></i>
                                <span>Publishing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @if(Gate::check('publish_document'))
                                    <li><a href="{{route('publish.create')}}">Publish</a></li>
                                @endif
                                @if(Gate::check('view_publish_document') || Gate::check('update_publish_document') || Gate::check('unpublish_document'))
                                    <li><a href="{{route('publish.list')}}">List of Published</a></li>
                                @endif
                                @if(Gate::check('view_deleted_publish_document') || Gate::check('publish_document'))
                                    <li><a href="{{route('publish.deleted.list')}}">List of Temporary Deleted</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(Gate::check('publish_announcement') || Gate::check('update_publish_announcement'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-street-view"></i>
                                <span>Announcement</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{route('publish.announcement.create')}}">Publish Announcement</a></li>  
                            </ul>
                        </li>
                    @endif

                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->