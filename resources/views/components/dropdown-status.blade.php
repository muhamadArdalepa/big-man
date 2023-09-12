<div class="{{ $class }}">
    <div class="dropdown">
        <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown" id="dropdownButton"
            aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
        </button>
        <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">
            @if ($status == 1)
                <li>
                    <a class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                        <i class="fa-solid fa-check me-2 text-success"></i>
                        Tugaskan {{ $tipe }}
                    </a>
                </li>
            @endif
            @if (in_array($status, [2, 3]))
                <li>
                    <a class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                        <i class="fa-solid fa-check me-2 text-success"></i>
                        Pekerjaan Selesai
                    </a>
                </li>
            @endif
            @if ($status == 2)
                <li>
                    <a class="dropdown-item btn-tunda border-radius-md py-1 " href="javascript:;">
                        <i class="fa-solid fa-pause me-2 text-warning"></i>
                        Tunda Pekerjaan
                    </a>
                </li>
            @endif
            @if ($status == 3)
                <li>
                    <a class="dropdown-item btn-lanjut border-radius-md py-1 " href="javascript:;">
                        <i class="fa-solid fa-play me-2 text-primary"></i>
                        Lanjutkan Pekerjaan
                    </a>
                </li>
            @endif
            <li>
                <a class="dropdown-item btn-batal border-radius-md py-1 " href="javascript:;">
                    <i class="fa-solid fa-xmark me-2 text-danger"></i>
                    Batalkan Pekerjaan
                </a>
            </li>
        </ul>
    </div>
</div>
