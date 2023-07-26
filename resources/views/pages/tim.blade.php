@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
	.card-sm {
		box-shadow: 0 0 .5rem 0 #eee !important;
	}

	#Modal>div>div>div.modal-body span>span.selection>span {
		border-radius: .5rem;
		box-shadow: none;
		border-color: #d2d6da;
	}

	#Modal>div>div>div.modal-body span>span.selection>span[aria-expanded=true] {
		border-color: #5e72e4;
		border-radius: 0.5rem 0.5rem 0 0;
	}

	#Modal>span>span>span.select2-search.select2-search--dropdown>input {
		border-radius: 0.5rem;
		box-shadow: none;
		border-color: #5e72e4;
	}

	#Modal>span>span.select2-dropdown {
		border-radius: 0 0 .5rem .5rem;
		border-color: #5e72e4;
	}
</style>
@endpush
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Tim'])
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-body">
					<div class="d-flex gap-3 flex-column flex-sm-row">
						<div class="d-flex align-items-center">
							<label for="kota" class="m-0 d-none d-sm-inline-block">Kota</label>
							<select class="form-control m-0 ms-sm-2" id="kota">
								<option value="">Semua Kota</option>
								@foreach ($kotas as $kota)
								<option value="{{$kota->id}}" {{auth()->
									user()->kota_id==$kota->id?'selected':''}}>{{$kota->kota}}
								</option>
								@endforeach
							</select>
						</div>
						<div class="d-flex align-items-center">
							<label for="tanggal" class="m-0 d-none d-sm-inline-block">Tanggal</label>
							<input type="date" id="tanggal" class="form-control m-0 ms-sm-2" value="{{$date}}">
						</div>
						<div class="d-flex align-items-center ms-md-auto">
							<label for="search" class="m-0 d-none d-sm-inline-block">Cari</label>
							<input type="text" id="search" class="form-control ms-0 ms-sm-2"
								placeholder="Cari Teknisi. . .">
						</div>
						<button class="btn btn-icon btn-3 btn-danger" type="button" data-bs-toggle="modal"
							data-bs-target="#Modal" data-bs-title="Tambah Laporan">
							<span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
							<span class="btn-inner--text">Tambah Tim</span>
						</button>

					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3" id="card-parent-container">
		<div class="col-sm-4 card-container"></div>
		<div class="col-sm-4 card-container"></div>
		<div class="col-sm-4 card-container"></div>
	</div>

	@include('layouts.footers.auth.footer')
</div>
@endsection
@push('modal')
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="d-flex align-items-center w-100">
					<h5 class="modal-title" id="ModalLabel">Tambah Tim</h5>
					<div class="form-group d-flex align-items-center gap-2 mb-0 ms-auto">
						<label for="kota_id" class="m-0">Kota</label>
						<select class="form-control" id="kota_id" tabindex="5">
							@foreach($kotas as $kota)
							<option value="{{$kota->id}}" {{auth()->user()->kota_id==$kota->id?'selected':''}}
								>{{$kota->kota}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<select id="teknisi-select">
					<option></option>
				</select>
				<div id="ketuaFeedback" class="invalid-feedback text-xs ps-2"></div>

				<div id="teknisi-container" class=" m-0 p-0"></div>

				<button id="btn-tambah-anggota" class="btn btn-icon btn-outline-secondary opacity-7 w-100 mt-3"
					type="button">
					<span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
					<span class="btn-inner--text">Tambah Anggota</span>
				</button>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn bg-gradient-primary" id="btn-simpan">Simpan</button>
			</div>
		</div>
	</div>
</div>
@endpush
@push('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
	// init
	const kota = document.getElementById('kota');
	const kotaModal = document.getElementById('kota_id');
	let teknisiIdArray = [];
	let ketuaId = null
	let kotaValue = kota.value;
	const baseUrl = `api/tim?`
	let url = `${baseUrl}kota=${kota}`;

	// functions
	function loadTims(url) {
		const cardContainer = document.getElementsByClassName('card-container');
		cardContainer[0].innerHTML = '';
		cardContainer[1].innerHTML = '';
		cardContainer[2].innerHTML = '';
		fetch(url)
			.then(response => response.json())
			.then(data => {
				data.forEach((item, h) => {
					let anggotaList = '';
					let panjang = (Object.keys(item.anggota).length)
					let ketuaLabel = panjang > 0 ? `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>` : ''
					item.anggota.forEach((anggota, i) => {
						if (i == 0) {
							anggotaList = `
							<div class="accordion accordion-flush" id="accordion${item.id}">
								<div class="accordion-item">
									<div class="accordion-header" id="flush-heading${item.id}">
										<button class="p-0 m-0 accordion-button collapsed ps-2 text-xxs font-weight-bolder opacity-7 mt-3 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse${item.id}" aria-expanded="false" aria-controls="flush-collapse${item.id}">
											ANGGOTA
										</button>
									</div>
									<div id="flush-collapse${item.id}" class="accordion-collapse collapse" aria-labelledby="flush-heading${item.id}" data-bs-parent="#accordionFlushExample">`
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
					if (panjang > 0) {
						anggotaList += `
								</div>
							</div>
						</div>
						`;
					}
					const card = `
					<div class="card mb-3">
						<div class="card-header d-flex align-items-center">
							<h6 class="p-0 m-0">TIM ${item.id}</h6>
							<span class="badge bg-gradient-warning text-xxs ms-auto">Penarikan</span>
							<span class="badge bg-gradient-success text-xxs ms-2">${item.status}</span>
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
								<button class="btn bg-gradient-primary m-0">
									Detail
								</button>
								<div class="ms-auto text-end">
									<small class="text-muted d-block text-xxs font-weight-bolder opacity-7 m-0 text-uppercase">${item.kota}</small>
									<small class="text-muted">${item.timestamp}</small>
								</div>
							</div>
						</div>
					</div>
					`;
					cardContainer[h % 3].insertAdjacentHTML('beforeend', card);
				});
			})
			.catch(error => {
				console.error('Terjadi kesalahan dalam memuat data:', error);
			});
	}
	function searchAndLoadData() {
		const searchQuery = document.getElementById('search').value;
		const selectedCity = document.getElementById('kota').value;
		const selectedDate = document.getElementById('tanggal').value;
		url = `${baseUrl}search=${searchQuery}&kota=${selectedCity}&tanggal=${selectedDate}`;
		loadTims(url);
	}
	function templateResult(teknisi) {
		if (!teknisi.id) {
			return teknisi.text;
		}

		let tim = teknisi.tims == null ? '' : 'TIM ' + teknisi.tims.id;
		var $teknisi = $(
			`<span class="d-flex align-items-center">
				<img src="/storage/private/profile/${teknisi.foto_profil}" class="avatar me-3">
				${teknisi.text}
				<span class="badge bg-gradient-danger text-xxs ms-auto">${tim}</span>
			</span>`
		);
		return $teknisi;
	};
	function templateSelection(teknisi) {
		return $(`<span class="text-muted">Cari Teknisi . . . </span>`);
	};
	function initSelect2(kotaId, e) {
		url2 = `api/select2-tim-teknisi?kota=${kotaId}`
		if (e.length > 0) {
			excepted = encodeURIComponent(JSON.stringify(e));
			url2 += `&teknisis=${excepted}`
		}
		$('#teknisi-select').select2({
			ajax: {
				url: url2,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						nama: params.term,
					};
				},
				processResults: function (data) {
					return {
						results: data.results,
					};
				},
				cache: true,
			},
			width: '100%',
			cache: true,
			templateResult: templateResult,
			templateSelection: templateSelection,
			dropdownParent: $('#Modal'),
			theme: 'bootstrap-5',
		});
	}
	function hapusTeknisi(teknisiId) {
		teknisiId = parseInt(teknisiId)
		teknisiIdArray = teknisiIdArray.filter(item => item !== teknisiId)

		teknisiCard = document.getElementById('teknisi-card-' + teknisiId);

		if (teknisiIdArray.length == 1 || !teknisiIdArray.includes(ketuaId)) {
			teknisiCard.previousElementSibling.remove()
		} else if (teknisiIdArray.length == 2) {
			teknisiCard.previousElementSibling.remove()
		}

		teknisiCard.remove()
		$('#teknisi-select').select2('destroy')
		initSelect2(kotaModal.value, teknisiIdArray);
	}
	// event listeners
	document.addEventListener('DOMContentLoaded', function () {
		$('#btn-tambah-anggota').hide();
		searchAndLoadData();

		document.getElementById('search').addEventListener('keyup', searchAndLoadData);
		document.getElementById('kota').addEventListener('change', searchAndLoadData);
		document.getElementById('tanggal').addEventListener('change', searchAndLoadData);

		initSelect2(kotaModal.value, teknisiIdArray);

		kotaModal.addEventListener('change', () => {
			ketuaId = null
			teknisiIdArray = []
			document.getElementById('teknisi-container').innerHTML = '';
			$('#teknisi-select').select2('destroy');
			$('#teknisi-select').val(null).trigger('change');
			initSelect2(kotaModal.value, teknisiIdArray)
		});

		$('#teknisi-select').on('select2:select', e => {
			teknisiId = parseInt(e.target.value)
			teknisiIdArray.push(teknisiId)
			$('#teknisi-select').select2('destroy')
			$('#teknisi-select').val(null).trigger('change')
			initSelect2(kotaModal.value, teknisiIdArray);
			url = 'api/teknisi/' + teknisiId
			fetch(url)
				.then(response => response.json())
				.then(data => {
					let teknisiCard = `
					<div class="card card-sm teknisi-card" id="teknisi-card-${data.id}">
						<div class="card-body p-2">
							<div class="d-flex align-items-center gap-3">
								<img id="ketua-card-img" class="avatar" src="/storage/private/profile/${data.foto_profil}">
								<p id="ketua-card-name" class="m-0">${data.nama}</p>
								<i class="ms-auto fas fa-times p-3 cursor-pointer text-secondary opacity-5" onclick="hapusTeknisi(${data.id})"></i>
							</div>
						</div>
					</div>
					`
					console.log('array = ' + teknisiIdArray);
					console.log('ketua = ' + ketuaId);

					if (teknisiIdArray.length == 1 || !teknisiIdArray.includes(ketuaId)) {
						document.getElementById('teknisi-container').insertAdjacentHTML('afterbegin', `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3" id="ketua-label">KETUA</p>`)
						document.getElementById('ketua-label').insertAdjacentHTML('afterend', teknisiCard)
						ketuaId = teknisiId
					} else if (teknisiIdArray.length == 2) {
						document.getElementById('teknisi-container').insertAdjacentHTML('beforeend', `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3">ANGGOTA</p>`)
						document.getElementById('teknisi-container').insertAdjacentHTML('beforeend', teknisiCard)
					} else {
						document.getElementById('teknisi-container').insertAdjacentHTML('beforeend', teknisiCard)
					}
				})
		});
		$('#btn-simpan').on('click', e => {
			if (teknisiIdArray.includes(ketuaId)) {
				$('#ketuaFeedback').hide();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					url: 'api/tim',
					type: 'POST',
					data: {
						ketua_id: ketuaId,
						teknisis: teknisiIdArray,
					},
					success: function (response) {
						Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        $('#Modal').modal('hide');
						kota.value = kotaModal.value
						searchAndLoadData();
					}
				})
			} else {
				$('#ketuaFeedback').show();
				$('#ketuaFeedback').text('Pilih ketua tim terlebih dahulu');
			}

		})
		$('#Modal').on('shown.bs.modal',e=>{
			$('#kota_id').val(kota.value).trigger('change');
		})
		$('#Modal').on('hide.bs.modal',e=>{
			setTimeout(()=>{
				ketuaId = null
				teknisiIdArray = []
				document.getElementById('teknisi-container').innerHTML = '';
				$('#teknisi-select').select2('destroy');
				$('#teknisi-select').val(null).trigger('change');
				initSelect2(kotaModal.value, teknisiIdArray)
			},250)
		})
	});
</script>
@endpush