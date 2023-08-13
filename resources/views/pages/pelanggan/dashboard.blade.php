@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
<div class="container-fluid py-4">

    @include('layouts.footers.auth.footer')
</div>
@endsection

@push('js')

@endpush