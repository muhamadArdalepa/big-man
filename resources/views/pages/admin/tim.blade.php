@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    @include('components.select2css')
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
                                <label for="wilayah" class="m-0 d-none d-sm-inline-block">Wilayah</label>
                                <select class="form-control m-0 ms-sm-2" id="wilayah">
                                    <option value="">Semua wilayah</option>
                                    @foreach ($wilayahs as $wilayah)
                                        <option value="{{ $wilayah->id }}"
                                            {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }}>
                                            {{ $wilayah->nama_wilayah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex align-items-center">
                                <label for="tanggal" class="m-0 d-none d-sm-inline-block">Tanggal</label>
                                <input type="date" id="tanggal" class="form-control m-0 ms-sm-2"
                                    value="{{ $date }}">
                            </div>

                            <div class="d-flex align-items-center ms-md-auto">
                                <label for="search" class="m-0 d-none d-sm-inline-block">Cari</label>
                                <input type="text" id="search" class="form-control ms-0 ms-sm-2"
                                    placeholder="Cari Teknisi. . .">
                            </div>

                            <button class="btn btn-icon btn-3 bg-gradient-danger" type="button" data-bs-toggle="modal"
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Tim</h5>
                </div>
                <div class="modal-body">
                    <select id="teknisi-select">
                        <option></option>
                    </select>
                    <div id="ketuaFeedback" class="invalid-feedback text-xs ps-2"></div>
                    <div id="teknisi-container" class=" m-0 p-0"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const wilayah = document.getElementById('wilayah');
        let teknisiIdArray = [];
        let includedTeknisiId = [];
        let ketuaId = null
        let wilayahValue = wilayah.value == '' ? {{ auth()->user()->wilayah_id }} : wilayah.value;
        const appUrl = `{{ env('APP_URL') }}`
        const baseUrl = `{{ env('APP_URL') }}/api/tim`
        let url = `${baseUrl}?wilayah=${wilayah}`;
        let timer;
        let tim_id = null;

        // functions
        function loadTims(url) {
            const cardContainer = document.getElementsByClassName('card-container');
            cardContainer[0].innerHTML = '';
            cardContainer[1].innerHTML = '';
            cardContainer[2].innerHTML = '';
            fetch(url)
                .then(response => response.json())
                .then(data => {
					console.log(data);
                    if (!Array.isArray(data)) {
                        throw new Error('Data is not an array');
                    }

                    data.forEach((item, h) => {
                        let anggotaList = '';
                        let panjang = (Object.keys(item.anggota).length)
                        let ketuaLabel = panjang > 0 ?
                            `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>` : ''
                        item.anggota.forEach((anggota, i) => {
                            if (i == 0) {
                                anggotaList =
                                    `
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
									<img src="${appUrl}/storage/private/${anggota.foto_profil}" class="avatar">
									<div>
										<div class="m-0">${anggota.nama}</div>
										<small class="text-sm opacity-7 m-0">${anggota.speciality}</small>
									</div>
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
							<span class="badge bg-${item.status[1]} text-xxs ms-2">${item.status[0]}</span>
							<div class="dropdown ms-auto">
								<button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown" id="dropdownButton"
									aria-expanded="false">
									<i class="fa fa-ellipsis-v"></i>
								</button>
								<ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">
									<li>
										<a href="javascript:;" data-tim_id="${item.id}" class="dropdown-item btn-edit border-radius-md py-1">
											<i class="fa-solid fa-pen-to-square me-2 text-warning"></i>
											Edit Tim
										</a>
									</li>
									<li>
										<a href="javascript:;" data-tim_id="${item.id}" class="dropdown-item btn-hapus border-radius-md py-1">
											<i class="fa-solid fa-xmark me-2 text-danger"></i>
											Hapus Tim
										</a>
									</li>
								</ul>
							</div>
						</div>
						<hr class="horizontal dark mt-0">
						<div class="card-body p-3 pt-0 ">
							<div class="overflow-hidden">
								<span class="badge bg-gradient-secondary text-xxs me-2">${item.pekerjaans}</span>
							</div>
							${ketuaLabel}
							<div class="card card-sm">
								<div class="card-body p-2">
									<div class="d-flex align-items-center gap-3">
										<img src="${appUrl}/storage/private/${item.foto_profil}" class="avatar">
										<div>
											<div class="m-0">${item.nama}</div>
											<small class="text-sm opacity-7 m-0">${item.speciality}</small>
										</div>
									</div>
								</div>
							</div>
							${anggotaList}
						</div>
						<hr class="horizontal dark mt-0">
						<div class="card-footer pt-0">
							<div class="d-flex align-items-center">
								<a href="${appUrl}/tim/${item.id}" class="btn bg-gradient-primary m-0">
									Detail
								</a>
								<div class="ms-auto text-end">
									<small class="text-muted d-block text-xxs font-weight-bolder opacity-7 m-0 text-uppercase">${item.nama_wilayah}</small>
									<small class="text-muted">${item.created_atFormat}</small>
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
                }).finally(() => {
                    $('.btn-hapus').on('click', e => {
                        e.preventDefault();
                        let id = e.currentTarget.dataset.tim_id
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: 'Apakah Anda yakin ingin menghapus tim ini?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#5e72e4',
                            cancelButtonColor: '#f5365c',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete(baseUrl + '/' + id)
                                    .then(response => {
                                        searchAndLoadData();
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.data.message,
                                            timer: 1500,
                                            showConfirmButton: false,
                                        });
                                    })
                                    .catch(error => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: error.response.data.message,
                                            timer: 1500,
                                            showConfirmButton: false,
                                        });
                                    })

                            }
                        });


                    })
                    $('.btn-edit').on('click', e => {
                        e.preventDefault();
                        let id = e.currentTarget.dataset.tim_id
                        tim_id = id;

                        axios.get(`${baseUrl}/${id}/edit`) // Use template literals for the URL
                            .then(response => {
                                let d = response.data;
                                ketuaId = d.user_id;
                                teknisiIdArray.push(d.user_id);
                                $('#teknisi-container').html(`
									<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3" id="ketua-label">KETUA</p>
									<div class="card card-sm teknisi-card" id="teknisi-card-${d.user_id}">
										<div class="card-body p-2">
											<div class="d-flex align-items-center gap-3">
												<img id="ketua-card-img" class="avatar" src="${appUrl}/storage/private/${d.foto_profil}">
												<p id="ketua-card-name" class="m-0">${d.nama}</p>
												<i class="ms-auto fas fa-times p-3 cursor-pointer text-secondary opacity-5" onclick="hapusTeknisi(${d.user_id})"></i>
											</div>
										</div>
									</div>
								`);

                                if (d.anggota.length > 0) { // Corrected the function call
                                    $('#teknisi-container').append(
                                        `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3">ANGGOTA</p>`
                                    );
                                }

                                d.anggota.forEach(anggota => {
                                    teknisiIdArray.push(anggota.user_id);
                                    $('#teknisi-container').append(`
										<div class="card card-sm teknisi-card" id="teknisi-card-${anggota.user_id}">
											<div class="card-body p-2">
												<div class="d-flex align-items-center gap-3">
													<img id="ketua-card-img" class="avatar" src="${appUrl}/storage/private/${anggota.foto_profil}">
													<p id="ketua-card-name" class="m-0">${anggota.nama}</p>
													<i class="ms-auto fas fa-times p-3 cursor-pointer text-secondary opacity-5" onclick="hapusTeknisi(${anggota.user_id})"></i>
												</div>
											</div>
										</div>
									`);
                                });
                                console.log(teknisiIdArray);
                                $('#Modal').modal('show');
                            })
                            .catch(error => {
                                // Handle errors as needed
                            });



                    })
                });
        }

        function searchAndLoadData() {
            const searchQuery = document.getElementById('search').value;
            const selectedCity = document.getElementById('wilayah').value;
            const selectedDate = document.getElementById('tanggal').value;
            url = `${baseUrl}?search=${searchQuery}&wilayah=${selectedCity}&tanggal=${selectedDate}`;
            loadTims(url);
        }

        function debounceSearchAndLoadData() {
            clearTimeout(timer);
            timer = setTimeout(searchAndLoadData, 500);
        }

        function templateResult(teknisi) {
            console.log(teknisi);
            if (!teknisi.id) {
                return teknisi.text;
            }

            let tim = teknisi.tims == null ? '' : 'TIM ' + teknisi.tims.id;
            var $teknisi = $(
                `<span class="d-flex align-items-center">
					<img src="${appUrl}/storage/private/${teknisi.foto_profil}" class="avatar me-3">
					<div>
						<div>${teknisi.text}</div>
						<span class="badge bg-gradient-danger text-xxs ms-auto">${teknisi.speciality}</span>
					</div>
				</span>`
            );
            return $teknisi;
        };

        function templateSelection(teknisi) {
            return $(`<span class="text-muted">Cari Teknisi . . . </span>`);
        };

        function initSelect2(wilayahId, e) {
            url2 = `${baseUrl}/select2-teknisi?wilayah=${wilayahId}`
            if (e.length > 0) {
                excepted = encodeURIComponent(JSON.stringify(e));
                url2 += `&teknisis=${excepted}`
            }
            if (includedTeknisiId.length > 0) {
                included = encodeURIComponent(JSON.stringify(includedTeknisiId));
                url2 += `&included=${included}`
            }
            $('#teknisi-select').select2({
                ajax: {
                    url: url2,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            nama: params.term,
                        };
                    },
                    processResults: function(data) {
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

            teknisiCard = document.getElementById('teknisi-card-' + teknisiId);

            if (teknisiIdArray.length == 1 || teknisiId == ketuaId) {
                teknisiCard.previousElementSibling.remove()
                ketuaId = null
            } else if (teknisiIdArray.length == 2) {
                teknisiCard.previousElementSibling.remove()
            }
            includedTeknisiId.push(teknisiId)
            teknisiCard.remove()

            teknisiIdArray = teknisiIdArray.filter(item => item !== teknisiId)
            $('#teknisi-select').select2('destroy')
            initSelect2(wilayahValue, teknisiIdArray);
        }

        // event listeners
        document.addEventListener('DOMContentLoaded', function() {
            searchAndLoadData();

            document.getElementById('search').addEventListener('keyup', debounceSearchAndLoadData);
            document.getElementById('wilayah').addEventListener('change', searchAndLoadData);
            document.getElementById('tanggal').addEventListener('change', searchAndLoadData);

            initSelect2(wilayahValue, teknisiIdArray);


            $('#teknisi-select').on('select2:select', e => {
                teknisiId = parseInt(e.target.value)
                teknisiIdArray.push(teknisiId)
                $('#teknisi-select').select2('destroy')
                $('#teknisi-select').val(null).trigger('change')
                initSelect2(wilayahValue, teknisiIdArray);
                url = 'api/teknisi/' + teknisiId
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let teknisiCard = `
					<div class="card card-sm teknisi-card" id="teknisi-card-${data.id}">
						<div class="card-body p-2">
							<div class="d-flex align-items-center gap-3">
								<img id="ketua-card-img" class="avatar" src="${appUrl}/storage/private/${data.foto_profil}">
								<p id="ketua-card-name" class="m-0">${data.nama}</p>
								<i class="ms-auto fas fa-times p-3 cursor-pointer text-secondary opacity-5" onclick="hapusTeknisi(${data.id})"></i>
							</div>
						</div>
					</div>
					`

                        if (teknisiIdArray.length == 1 || !teknisiIdArray.includes(ketuaId)) {
                            document.getElementById('teknisi-container').insertAdjacentHTML(
                                'afterbegin',
                                `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3" id="ketua-label">KETUA</p>`
                            )
                            document.getElementById('ketua-label').insertAdjacentHTML('afterend',
                                teknisiCard)
                            ketuaId = teknisiId
                        } else if (teknisiIdArray.length == 2) {
                            document.getElementById('teknisi-container').insertAdjacentHTML('beforeend',
                                `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-3">ANGGOTA</p>`
                            )
                            document.getElementById('teknisi-container').insertAdjacentHTML('beforeend',
                                teknisiCard)
                        } else {
                            document.getElementById('teknisi-container').insertAdjacentHTML('beforeend',
                                teknisiCard)
                        }

                        includedTeknisiId = includedTeknisiId.filter(item => item !== teknisiId)
                    })

            });

            $('#btn-simpan').on('click', e => {
                if (teknisiIdArray.includes(ketuaId)) {
                    $('#ketuaFeedback').hide();
                    data = {
                        ketua_id: ketuaId,
                        teknisis: teknisiIdArray,
                    };
                    if (tim_id != null) {
                        axios.put(baseUrl + '/' + tim_id, data)
                            .then(response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            }).finally(() => {
                                $('#Modal').modal('hide');
                                searchAndLoadData();
                            })
                    } else {
                        axios.post(baseUrl, data)
                            .then(response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            }).finally(() => {
                                $('#Modal').modal('hide');
                                searchAndLoadData();
                            })
                    }

                } else {
                    $('#ketuaFeedback').show();
                    $('#ketuaFeedback').text('Pilih ketua tim terlebih dahulu');
                }

            })

            $('#Modal').on('hide.bs.modal', e => {
                setTimeout(() => {
                    ketuaId = null
                    teknisiIdArray = []
                    includedTeknisiId = []
                    document.getElementById('teknisi-container').innerHTML = '';
                    $('#teknisi-select').select2('destroy');
                    $('#teknisi-select').val(null).trigger('change');
                    initSelect2(wilayahValue, teknisiIdArray)
                }, 250)
            });
        });
    </script>
@endpush
