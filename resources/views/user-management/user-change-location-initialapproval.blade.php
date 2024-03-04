@extends('layouts.master')

@section('title') Transfer Initial Approval @endsection

@section('css') 
    <!-- DataTables -->        
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') TRANSFER INITIAL APPROVAL @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Transferees for Initial Approval @endslot
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
                                    <th style="text-align:center;" class="table-warning">Current Location</th>
                                    <th style="text-align:center;" class="table-info">Transfer To</th>
                                    <th style="text-align:center;">Action</th>                            
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($transferees as $key => $row)    
                                <tr>
                                    {{-- <td style="text-align:center;">{{ $key +1 }}</td> --}}
                                    <td style="text-align:center;">
                                        @if($row->avatar === null)
                                            <img src="{{Gravatar::src($row->username)}}" style="border-radius: 50%" width="25px" height="25px">
                                        @else 
                                            <img src="{{asset('images/profile/'.$row->avatar)}}" style="border-radius: 50%" width="25px" height="25px">
                                        @endif 
                                    </td>     
                                    <td style="text-align:left;">{{ $row->role_title }}</td>
                                    <td style="text-align:center;">{{ $row->username }}</td>
                                    <td style="text-align:left;">{{ $row->first_name.' '.$row->middle_name.' '.$row->last_name.' '.$row->suffix_name}}</td>
                                    <td style="text-align:left;" class="table-warning">{{ $row->office_from.', '.$row->department_from.', '.$row->section_from }}</td>
                                    <td style="text-align:left;" class="table-info">{{ $row->office_to.', '.$row->department_to.', '.$row->section_to }}</td>                            
                                    <td style="text-align:left;">   
                                        @can('location_transfer_approval')           
                                            <a href="{{ route('manage.user.approved.transfer', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-approveTransfer">
                                            <span style="font-size: 1.2em; color: Dodgerblue;">
                                                <i class="fas fa-check" title="Approve transfer to {{$row->office_to.', '.$row->department_to.', '.$row->section_to}}"></i>
                                            </span></a>&nbsp;
                            
                                            <a href="{{ route('manage.user.disapproved.transfer', ['id' => Hasher::encode($row->id)]) }}" id="sa-custom-disapproveTransfer">
                                            <span style="font-size: 1.2em; color: Red;">
                                                <i class="fas fa-times" title="Disapprove transfer to {{$row->office_to.', '.$row->department_to.', '.$row->section_to}}"></i>
                                            </span></a>
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

@endsection

@section('script')

        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

@endsection

@section('script-bottom')

    <!-- sweetalert -->
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script> --}}
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

    <script>
        $(document).on("click", "#sa-custom-approveTransfer", function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            swal({
                    title: "Continue to approve?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willApprove) => {
                    if (willApprove) {
                            window.location.href = link;
                    } /*else {
                            swal("Safe Data!");
                    }*/
            });
        });
    </script>
    
    <script>
        $(document).on("click", "#sa-custom-disapproveTransfer", function(e){
            e.preventDefault();
            var link = $(this).attr("href");
            swal({
                    title: "Are you sure want to disapprove?",
                    text: "This action cannot be reversed.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
            })
            .then((willDisapprove) => {
                    if (willDisapprove) {
                            window.location.href = link;
                    } /*else {
                            swal("Safe Data!");
                    }*/
            });
        });
    </script>

@endsection