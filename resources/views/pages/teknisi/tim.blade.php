@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Tim'])
<div class="container-fluid py-4">

	<div class="row mt-3" id="card-parent-container">
		<div class="col-sm-4 card-container"></div>
		<div class="col-sm-4 card-container"></div>
		<div class="col-sm-4 card-container"></div>
	</div>

	@include('layouts.footers.auth.footer')
</div>
@endsection