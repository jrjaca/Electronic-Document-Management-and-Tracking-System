@extends('layouts.master')

@section('title') List of Draft Documents @endsection

@section('css') 
        <!-- DataTables -->        
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}">
@endsection

@section('content')

    @component('common-components.breadcrumb')
        @slot('title') DRAFT DOCUMENTS @endslot
        @slot('li_1') <a title="Back to Home" href="{{route('index')}}">Home</a> @endslot
        @slot('li_2') List of Draft Documents @endslot
    @endcomponent  

    <!-- Start Display Message-->
    @include('layouts.flash-message')
    <!-- END Display Message-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Barcode</th> <!--allow to generate barcode here-->
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>For</th>
                                    <th>Date and Time Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($draft_docs as $key => $row)  
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td><a href="javascript:void(0)" onclick="reGenerateBarcode('{{ $row->barcode }}', '{{ $row->title }}');" title="Re-generate barcode"> 
                                        {{ $row->barcode }}</a></td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->document_type_desc }}</td>       
                                    <td>{{ $row->document_action_desc }}</td>
                                    <td>{{ date('M. d Y, h:i A', strtotime($row->created_at)) }}</td>   
                                    <td>  
                                        @if(Gate::check('create_document'))
                                            <a href="{{ route('document.draft.edit', ['id' => Hasher::encode($row->document_id)]) }}" >
                                                <span style="font-size: 1.2em; color: Dodgerblue;">
                                                    <i class="fa fa-edit" title="Show {{ $row->title }} to update or submit."></i>
                                                </span> </a>&nbsp;

                                            <a href="{{ route('document.draft.delete', ['id' => Hasher::encode($row->document_id)]) }}" id="sa-custom-delete">
                                                <span style="font-size: 1.2em; color: Red;">
                                                    <i class="far fa-trash-alt" title="Delete {{ $row->title }}."></i>
                                                </span></a>  
                                        @endif
                                    </td>    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

        <!-- Re-generate barcode-->
        <div id="barcodeModal" class="modal fade barcodeModal" tabindex="-1" role="dialog" aria-labelledby="barcodeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" id="title_div"></label>
                                @php
                                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                                    $bcode = "<div id='bcode_div1'></div>";
                                @endphp     
                                <div style="margin: auto; text-align: center; width: 200px; height: 62px; border: 1px solid grey; border-style: dashed; padding: 9px;">
                                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($bcode, $generatorPNG::TYPE_CODE_128)) }}", width="180px" height="30px"> <br />
                                    <h1 style="font-size:9px;"><div id="bcode_div1"></div></h1>      
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Re-generate barcode -->

@endsection

@section('script')

        <!-- Plugins js -->
        <script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Init js-->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js')}}"></script> 

    <!-- Re-generate barcode -->
    <script>        
        function reGenerateBarcode(barcode, title){
            spinner('Loading...');            
            var modal = $('#barcodeModal');
            modal.find('#bcode_div1').html(barcode);     
            modal.find('#title_div').html(title);     
            $('#barcodeModal').modal('show');                
        }
    </script>
    <!-- /Re-generate barcode-->

@endsection

@section('script-bottom')

    {{-- <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>  --}}
    <!-- Plugins js -->
    {{-- <script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>  --}}

<!-- sweetalert -->
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('assets/libs/sweetalert2.1.2jaca/sweetalert2.1.2jaca.min.js') }}"></script>
        <!-- Sweet alert init js-->
        <script src="{{ URL::asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

        <!-- delete draft document -->
        <script>
            $(document).on("click", "#sa-custom-delete", function(e){
                e.preventDefault();
                var link = $(this).attr("href");
                swal({
                        title: "Are you sure want to delete?",
                        text: "This will be permanently deleted.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                })
                .then((willDelete) => {
                        if (willDelete) {
                                window.location.href = link;
                        } /*else {
                                swal("Safe Data!");
                        }*/
                });
            });
        </script>
        <!-- //delete draft document -->
        
@endsection