@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        @media (min-width: 768px) {
            .mh-200 {
                min-height: 200px !important;
            }
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
       @if(session()->has('success'))
       <div class="alert alert-success text-white" role="alert">
           Selamat datang kembali, <strong> {{auth()->user()->nama}}</strong>
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card h-100 mh-200">
                    <div class="card-body p-3">
                        <p class="text-sm text-uppercase font-weight-bold">
                            {{ $data['pekerjaan']->count() > 0 ? 'Pekerjaan Sedang Berlangsung' : 'Tidak ada pekerjaan yang sedang berlangsung' }}
                        </p>
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    @foreach ($data['pekerjaan'] as $i => $pekerjaan)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <img class="rounded-3"
                                                        src="{{ route('storage.private', 'profile/dummy.png') }}"
                                                        alt="foto profil" height="35">
                                                    <div class="d-flex flex-column w-100">
                                                        <div class="d-flex gap-1">
                                                            <h6 class="lh-sm mb-1 d-inline">TIM {{ $pekerjaan->id }}</h6>
                                                            <span class="text-muted text-sm">
                                                                {{ $pekerjaan->wilayah->nama_wilayah }}</span>
                                                        </div>
                                                        <div class="fw-bold">
                                                            {{ $pekerjaan->getKetuaTim()->user->nama }}
                                                        </div>
                                                        @foreach ($pekerjaan->getAnggotaTim() as $anggota)
                                                            <div>{{ $anggota->user->nama }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>

                                            <td style="white-space: normal">
                                                {{$pekerjaan->nama_pekerjaan}}
                                                <div>{{ $pekerjaan->alamat }}</div>
                                            </td>

                                            <td class="p-0">
                                                <a href="{{route('pekerjaan.show',$pekerjaan->id)}}"
                                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto ms-auto">
                                                    <i class="ni ni-bold-right" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-carousel overflow-hidden h-100 p-0 mh-200">
                    @if ($aktivitas->count() > 0)
                        
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            @foreach ($aktivitas as $i => $item)
                                <div class="carousel-item h-100 {{ $i == 0 ? 'active' : '' }}"
                                    style="background-image: url('{{ route('storage.private','/'. $item->foto) }}'); background-size: cover; background-position: center center">
                                    <div class="carousel-caption bottom-0 text-start start-0 w-100 px-5"
                                        style="background-image: linear-gradient(to top,rgba(0,0,0,.5),transparent)">
                                        <div class="">
                                            <img class="rounded-3"
                                                src="{{ route('storage.private', $item->foto_profil) }}"
                                                alt="foto profil" height="35">
                                            <small class="ms-2 align-bottom">{{ $item->nama }}</small>
                                        </div>
                                        <div class="small ">{{ $item->aktivitas }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev w-5 me-3" style="mix-blend-mode: difference" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" style="mix-blend-mode: difference" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @else
                    <div class="p-3">
                        <p class="text-sm text-uppercase font-weight-bold">
                            Tidak ada aktivitas terbaru
                        </p>
                    </div>

                    @endif


                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-lg-5 mb-4 col-md-6">
                <div class="card h-100 mh-200">
                    <div class="card-body h-100 overflow-hidden">
                        <div class="d-flex flex-column justify-content-between gap-3">
                            <div class="">
                                <div class="d-flex">
                                    <div class="">
                                        <h6 class="text-capitalize m-0">Pemasangan Baru Bulan
                                            {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h6>
                                        <h5 class="font-weight-bolder my-2">
                                            {{ $data['pemasangan']->count() }} User
                                        </h5>
                                    </div>
                                    <span class="ms-auto">
                                        <i class="fa-solid fa-circle-check fa-2xl text-success"></i>
                                    </span>
                                </div>
                                <p class="mb-0">
                                    <span
                                        class="text-success text-sm font-weight-bolder">{{ $data['pemasangan']->whereDate('pemasangans.created_at', date('Y-m-d'))->count() }}</span>
                                    Hari ini
                                </p>
                            </div>

                            @if ($data['permintaan']->count() > 0)
                                <div class="">

                                    <div class="d-flex mb-3 lh-1">
                                        <h6 class="text-capitalize m-0">Permintaan Pemasangan</h6>
                                        <a href="{{ route('pemasangan.index') }}" class="ms-auto text-danger">Lihat semua</a>
                                    </div>
                                    @foreach ($data['permintaan'] as $i => $permintaan)
                                        <div
                                            class="position-relative d-flex gap-2 {{ $i == 0 ? 'pt-0' : 'pt-3' }} {{ $loop->last ? '' : 'pb-3 border-bottom' }}">
                                            <img class="rounded-3"
                                                src="{{ route('storage.private', $permintaan->user->foto_profil) }}"
                                                alt="foto profil" height="35">
                                            <div class="w-100">
                                                <div class="align-bottom">{{ $permintaan->user->nama }}</div>
                                                <div class="text-sm opacity-7">{{ $permintaan->alamat }}</div>
                                            </div>
                                            <button
                                                class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto ms-auto">
                                                <i class="ni ni-bold-right" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-7 mb-4 col-md-6">
                <div class="card h-100 mh-200">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Grafik Pemasangan Bulan
                            {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h6>

                    </div>
                    <div class="card-body p-0 h-100">
                        <div class="chart h-100">
                            <canvas id="chart-line" class="chart-canvas h-100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 mb-4 col-md-6">
                <div class="card h-100 mh-200">
                    <div class="card-body h-100 overflow-hidden">
                        <div class="d-flex flex-column justify-content-between gap-3">
                            <div class="">
                                <div class="d-flex">
                                    <div class="">
                                        <h6 class="text-capitalize m-0">Laporan Gangguan Bulan
                                            {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h6>
                                        <h5 class="font-weight-bolder my-2">
                                            {{ $data['nLaporan']->count() }} User
                                        </h5>
                                    </div>
                                    <span class="ms-auto">
                                        <i class="fa-solid fa-circle-exclamation fa-2xl text-danger"></i>
                                    </span>
                                </div>
                                <p class="mb-0">
                                    <span
                                        class="text-warning text-sm font-weight-bolder">{{ $data['nLaporan']->whereDate('laporans.created_at', date('Y-m-d'))->count() }}</span>
                                    Hari ini
                                </p>
                            </div>
                            @if ($data['laporans']->count() > 0)
                                <div class="">
                                    <div class="d-flex mb-3 lh-1">
                                        <h6 class="text-capitalize m-0">Laporan Gangguan Terbaru</h6>
                                        <a href="{{ route('laporan') }}" class="ms-auto text-danger">Lihat semua</a>
                                    </div>
                                    @foreach ($data['laporans'] as $i => $laporan)
                                        <div
                                            class="position-relative d-flex gap-2 {{ $i == 0 ? 'pt-0' : 'pt-3' }} {{ $loop->last ? '' : 'pb-3 border-bottom' }}">
                                            <img class="rounded-3"
                                                src="{{ route('storage.private', $laporan->pelapors->foto_profil) }}"
                                                alt="foto profil" height="35">
                                            <div class="w-100">
                                                <div class="align-bottom lh-1">{{ $laporan->pelapors->name }}</div>
                                                <span
                                                    class="badge bg-gradient-danger text-xxs">{{ $laporan->jenis_gangguan->nama_gangguan }}</span>
                                                <div class="text-sm opacity-7">{{ $laporan->pemasangan->alamat }}</div>
                                            </div>
                                            <button
                                                class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto ms-auto">
                                                <i class="ni ni-bold-right" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-7 mb-4 col-md-6">
                <div class="card h-100 mh-200">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Grafik Laporan Gangguan Bulan
                            {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h6>

                    </div>
                    <div class="card-body p-0 h-100">
                        <div class="chart h-100">
                            <canvas id="chart-gangguan" class="chart-canvas h-100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4 col-md-12">
                <div class="card h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Teknisi aktif</h6>
                        <p class="text-sm mb-0">
                            <span class="font-weight-bold">{{$data['absen']->count()}}</span> teknisi aktif dari {{$data['teknisi']->count()}}
                        </p>
                    </div>
                    <div class="card-body">
                        @foreach ($data['absen']->get() as $i => $absen)
                            <div
                                class="d-flex align-items-center {{ $i == 0 ? 'pt-0' : 'pt-2' }} gap-2 {{ $i == 4 ? '' : ' pb-2 border-bottom' }}">
                                <img class="rounded-3" src="{{ route('storage.private', $absen->user->foto_profil) }}"
                                    alt="foto profil" height="35">
                                <div class="">{{ $absen->user->nama }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 col-md-12">
                <div class="card h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize m-0">Peringkat Teknisi</h6>
                    </div>
                    <div class="card-body p-0 pt-3">
                        @php
                            $color = ['gold', 'silver', 'bronze'];
                        @endphp
                        @foreach ($data['teknisi']->limit(3)->get() as $i => $teknisi)
                            <div
                                class="d-flex align-items-center px-4  p-2 gap-2 {{ $i == 0 ? 'pt-0' : 'pt-2' }} {{ $i == 2 ? 'pb-3' : ' border-bottom' }}">
                                <h6 class="m-0 lh-1 text-center text-{{ $color[$i] }}">
                                    #{{ $i + 1 }}</h6>
                                <img class="rounded-3" src="{{ route('storage.private', 'profile/dummy.png') }}"
                                    alt="foto profil" height="35">
                                {{ $teknisi->nama }}
                                <div class="ms-auto text-center">
                                    <small class="opacity-7">Poin</small>
                                    <h5 class="m-0 lh-1">{{ $teknisi->poin }}</h5>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 col-md-12">
                <div class="card h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize m-0">Tim Teknisi Hari Ini</h6>
                    </div>
                    <div class="card-body pt-3">
                        @for ($i = 0; $i < 1; $i++)
                            <div
                                class="d-flex {{ $i == 0 ? 'pt-0' : 'pt-2' }} gap-2 {{ $i == 2 ? '' : ' pb-2 border-bottom' }}">
                                <img class="rounded-3" src="{{ route('storage.private', 'profile/dummy.png') }}"
                                    alt="foto profil" height="35">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex w-100 align-items-start">
                                        <h6 class="lh-sm mb-1">TIM {{ random_int(1, 10) }}</h6>
                                        @php
                                            $color = [['primary', 'Penarikan Jalur'], ['success', 'Pemasangan'], ['warning', 'Perbaikan']];
                                            $num = random_int(0, 2);
                                        @endphp
                                        <span
                                            class="badge text-xxs ms-auto bg-gradient-{{ $color[$num][0] }}">{{ $color[$num][1] }}</span>
                                    </div>
                                    @for ($j = 0; $j < random_int(2, 5); $j++)
                                        <div class="">
                                            {{ fake()->name() }}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/chartjs.min.js') }}"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");
        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(45, 206, 137, 0.5) ');
        gradientStroke1.addColorStop(0.2, 'rgba(45,206,204,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(45,206,204,0)');
        let labels =  @json($chart[0]);
        let data =  @json($chart[1]);
        console.log(labels);
        console.log(data);
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Pelanggan baru",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#2dce89",
                    backgroundColor: gradientStroke1,
                    borderWidth: 2,
                    fill: true,
                    data: data,
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: true,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#ccc',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
        var ctx2 = document.getElementById("chart-gangguan").getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
        new Chart(ctx2, {
            type: "line",
            data: {
                labels: @json($chart[2]),
                datasets: [{
                    label: "Total Laporan",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fb6340",
                    backgroundColor: gradientStroke1,
                    borderWidth: 2,
                    fill: true,
                    data: @json($chart[3]),
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: true,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#ccc',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush
