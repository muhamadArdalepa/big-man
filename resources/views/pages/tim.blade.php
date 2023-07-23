@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
	.card-sm {
		box-shadow: 0 0 .5rem 0 #eee !important;
	}

	#Modal>div>div>div.modal-body span>span.selection>span {
		height: fit-content !important;
		padding: .5rem;
		border-radius: 1rem;
	}
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Tim'])
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-body  d-flex align-items-center justify-content-between">
					<h6 class="m-0">Tim</h6>
					<button class="btn btn-danger m-0" data-bs-toggle="modal" data-bs-target="#Modal">
						<i class="fas fa-plus me-3"></i>
						Tambah Tim
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3" id="card-container">

	</div>

	@include('layouts.footers.auth.footer')
</div>
@endsection
@push('modal')
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ModalLabel">Tambah Tim</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>
				<select onchange="loadAnggota()" id="ketua-select" class="w-100">
					<option></option>
				</select>

				<p class="ps-2 text-xxs font-weight-bolder opacity-7 mt-3 mb-2">ANGGOTA</p>
				<div id="anggota-container">

				</div>
				<button onclick="tambahAnggota()" class="btn btn-icon btn-outline-secondary w-100 opacity-5 mt-3">
					<i class="fa-solid fa-plus me-2"></i>
					Tambah anggota
				</button>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn bg-gradient-primary">Simpan</button>
			</div>
		</div>
	</div>
</div>
@endpush
@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"
	integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	//init
	let anggota

	// caller
	loadTims();
	loadKetua()

	// $('#Modal').on('shown.bs.modal', function () {
	// });


	// functions
	function loadTims(kota) {
		const cardContainer = document.getElementById('card-container');
		cardContainer.innerHTML = '';
		let url = 'api/tim' + (kota != null ? '?kota=' + kota : '');
		fetch(url)
			.then(response => response.json())
			.then(data => {
				data.forEach(item => {
					let anggotaList = '';
					let panjang = (Object.keys(item.anggota).length)
					let ketuaLabel = panjang > 0 ? `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>` : ''
					item.anggota.forEach((anggota, i) => {
						if (i == 0) {
							anggotaList = `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mt-3 mb-2">ANGGOTA</p>`
						}
						anggotaList += `
                        <div class="card card-sm mb-2">
							<div class="card-body p-2">
								<div class="d-flex align-items-center gap-3">
									<img src="/storage/private/profile/${anggota.anggota_foto_profil}" class="avatar">
									<p class="m-0">${anggota.anggota_nama}</p>
								</div>
							</div>
						</div>
					`;
					});

					const card = `
                    <div class="col-md-6 col-lg-4 mb-4">
						<div class="card ">
							<div class="card-header d-flex align-items-center justify-content-between">
								<h6 class="p-0 m-0">TIM ${item.id}</h6>
								<span class="badge bg-gradient-danger text-xxs">${panjang}</span>
							</div>
							<hr class="horizontal dark mt-0">
							<div class="card-body p-3 pt-0">
								${ketuaLabel}
								<div class="card card-sm">
									<div class="card-body p-2">
										<div class="d-flex align-items-center gap-3">
											<img src="/storage/private/profile/${item.foto_profil}" class="avatar">
											<p class="m-0">${item.ketua}</p>
										</div>
									</div>
								</div>
								${anggotaList}
							</div>
							<hr class="horizontal dark mt-0">
							<div class="card-footer pt-0">
								<div class="d-flex align-items-center">
									<div class="btn-group">
										<button class="btn btn-warning m-0">
											<i class="fa-solid fa-gear"></i>
										</button>
										<button onclick="deleteTim(${item.id})" class="btn btn-danger m-0">
											<i class="fa-solid fa-trash"></i>
										</button>
									</div>
									<small class="text-muted ms-auto">${item.timestamp}</small>
								</div>
							</div>
						</div>
					</div>
					`;
					cardContainer.insertAdjacentHTML('beforeend', card);
				});
			})
			.catch(error => {
				console.error('Terjadi kesalahan dalam memuat data:', error);
			});
	}
	function resultTemplate(teknisi) {
		if (!teknisi.id) {
			return teknisi.text;
		}
		var $teknisi = $(
			`<span class="d-flex align-items-center">
							<img src="${teknisi.foto_profil}" class="avatar me-3">
							${teknisi.text}
							<span class="badge bg-gradient-danger text-xxs ms-auto">${teknisi.tim}</span>							
						</span>`
		);
		return $teknisi;
	};
	function selectionTemplate(teknisi) {
		if (!teknisi.id) {
			return teknisi.text;
		}
		var $teknisi = $(
			`<span class="d-flex align-items-center">
							<img src="${teknisi.foto_profil}" class="avatar me-3">
							${teknisi.text}							
						</span>`
		);
		return $teknisi;
	};
	function loadKetua() {
		let results = [];
		fetch('api/tim/teknisi')
			.then(response => response.json())
			.then(data => {
				data.forEach(item => {
					let option = {
						id: item.id,
						text: item.nama,
						foto_profil: "/storage/private/profile/" + item.foto_profil,
						tim: item.tims.length > 0 ? "TIM " + item.tims[0].id : "",
					};
					results.push(option);
				});
				$("#ketua-select").select2({
					data: results,
					width: '100%',
					theme: 'bootstrap-5',
					placeholder: '--Pilih Teknisi--',
					dropdownParent: $('#Modal'),
					templateResult: resultTemplate,
					templateSelection: selectionTemplate,
				});

				$(".anggota-select").select2({
					data: results,
					width: '100%',
					theme: 'bootstrap-5',
					placeholder: '--Pilih Teknisi--',
					dropdownParent: $('#Modal'),
					templateResult: resultTemplate,
					templateSelection: selectionTemplate,
				});
			})
			.catch(error => {
				console.error('Terjadi kesalahan dalam memuat data:', error);
			});

	}
	function loadAnggota() {
		let results = [];
		fetch('api/tim/teknisi')
			.then(response => response.json())
			.then(data => {
				data.forEach(item => {
					if (item.id != $('#ketua-select').val() && !(anggotaTerpilih.includes(item.id))) {
						let option = {
							id: item.id,
							text: item.nama,
							foto_profil: "/storage/private/profile/" + item.foto_profil,
							tim: item.tims.length > 0 ? "TIM " + item.tims[0].id : "",
						};
						results.push(option);
					}

				});
				$(".anggota-select").select2({
					data: results,
					width: '100%',
					theme: 'bootstrap-5',
					placeholder: '--Pilih Teknisi--',
					dropdownParent: $('#Modal'),
					templateResult: resultTemplate,
					templateSelection: selectionTemplate,
				}).on("select2:select", () => {
					anggotaTerpilih = []
					let formControls = document.querySelectorAll(".anggota-select");
					formControls.forEach(function (control) {
						anggotaTerpilih.push(parseInt(control.value));
					});
					console.log(anggotaTerpilih);
				});
			})
			.catch(error => {
				console.error('Terjadi kesalahan dalam memuat data:', error);
			});

	}

	function tambahAnggota() {
		// Dapatkan elemen container
		const container = document.getElementById("anggota-container");

		// Buat elemen <select> baru
		const selectElement = document.createElement("select");
		selectElement.classList.add("d-none");
		selectElement.classList.add("anggota-select");
		selectElement.classList.add("w-100");
		selectElement.innerHTML = '<option></option>';
		// Tambahkan elemen <select> baru ke dalam container
		container.appendChild(selectElement);
		loadAnggota()
	}

</script>
@endpush