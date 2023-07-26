@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="{{asset('assets/css/custom-datatables.css')}}" rel="stylesheet" />
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'laporanshow'])
<div class="container-fluid py-4">
  
    @include('layouts.footers.auth.footer')
</div>

@endsection
@push('modal')

@endpush
@push('js')

@endpush