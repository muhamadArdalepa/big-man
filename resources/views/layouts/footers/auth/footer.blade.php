<footer class="footer pt-3  ">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-lg-between">
            <div class="">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    made with <i class="fa fa-heart"></i> by
                    <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                    &
                    <a href="https://www.updivision.com" class="font-weight-bold" target="_blank">UPDIVISION</a>
                    for a better web.
                </div>
            </div>
            <div class="">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <div id="clock"></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
@push('js')
<script>
    function updateClock() {
        const currentTime = new Date();
        const hours = currentTime.getHours();
        const minutes = currentTime.getMinutes();
        const seconds = currentTime.getSeconds();

        // Menambahkan angka 0 di depan angka jika nilainya < 10
        const formattedHours = hours < 10 ? "0" + hours : hours;
        const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
        const formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

        const timeString = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;

        return timeString
    }
    
    
    // Memperbarui jam setiap detik
    setInterval(()=>{
        const clockElement = document.getElementById("clock");
        clockElement.textContent = updateClock();
    }, 1000);
</script>
@endpush