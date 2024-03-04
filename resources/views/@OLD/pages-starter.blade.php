@extends('layouts.master')

@section('title') Starter Page @endsection

@section('content')

   @component('common-components.breadcrumb')
         @slot('title') Starter Page @endslot
         @slot('li_1') Utility @endslot
         @slot('li_2') Starter Page @endslot
     @endcomponent         

@endsection