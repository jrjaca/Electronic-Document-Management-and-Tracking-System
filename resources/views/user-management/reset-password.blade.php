@extends('layouts.master')

@section('title') Password Reset @endsection

@section('css') 
    <!-- DataTables -->        
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') Password Reset @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Users @endslot
    @endcomponent 

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->    

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">#</th>
                                    <th style="text-align:center;">Role</th>
                                    <th style="text-align:center;">ID Number</th>
                                    <th style="text-align:center;">Name</th>
                                    <th style="text-align:center;">Email</th>
                                    <th style="text-align:center;">Registration Status</th>
                                    <th style="text-align:center;">User Status</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $row)    
                                <tr 
                                    @if( $row->deleted_at != null )
                                        class="table-warning"
                                    @elseif( $row->activated == 0 )   
                                        class="table-info"
                                    @endif
                                >
                                    <td style="text-align:center;">{{ $key +1 }}</td>
                                    <td style="text-align:left;">{{ $row->role_title }}</td>
                                    <td style="text-align:center;">{{ $row->username }}</td>
                                    <td style="text-align:left;">{{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }}</td>
                                    <td style="text-align:left;">{{ $row->email }}</td>	
                                    <td style="text-align:center;">
                                        @if( $row->activated == 1 )
                                            <span class="badge badge-success">Activated</span>
                                        @else
                                            <span class="badge badge-danger">For activation</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if( $row->deleted_at == null )
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-danger">Disabled</span>
                                        @endif
                                    </td>   
                                    <td style="text-align:center;">
                                        @can('user_activate')    
                                            <a href="javascript:void(0)" role="button" data-toggle="modal" data-target="#passwordResetModal"
                                                data-userid="{{ @$row->id }}"
                                            >
                                                <span style="font-size: 1.2em; color: Dodgerblue;"> 
                                                    <i class="fas fa-key" title="Reset {{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix_name }} password"></i>
                                                </span>
                                            </a>
                                        @endcan
                                    </td>    

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        
        <!-- Modal -->
        <div id="passwordResetModal" class="modal fade activationModal" tabindex="-1" role="dialog" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered" role="document"> --}}
            <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordResetModalLabel">Password Reset</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>            
                    <form id="my_form" action="{{ route('manage.users.reset.password') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="modal-body pd-20">
                            <div class="form-group">
                                <input type="hidden" id="user_id" name="user_id" readonly required/>

                                <label for="brand_name">New Password</label>
                                <input type="text" id="password" name="password" value="Newp@ssw0rd" maxlength="20"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required autofocus placeholder="New password"/>
                                <div class="invalid-feedback">
                                    Set new password.
                                </div>       

                            </div>
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Modal -->

@endsection

@section('script')

    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

    <script>
        $('#passwordResetModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var userid = button.data('userid')
            var modal = $(this)
            modal.find('#user_id').val(userid)
        });
    </script>    
@endsection

@section('script-bottom')

    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script> 
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script> 
    
@endsection