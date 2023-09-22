@push('css')
    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        .card-body.flex-shrink-0 {
            transition: margin-left 300ms ease-in-out;
        }

        .card-body.flex-shrink-0 div {
            transition: opacity 300ms ease-in-out;
        }

        .leaflet-popup-content {
            width: 300px;
        }
    </style>
@endpush

@push('modal')
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body h-100">
                    <div id="map-container" class="h-100">
                        <div id="map" class="h-100"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#Modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        let alamatField, koordinatField;

        $('#mapModal').on('shown.bs.modal', e => {
            mapHeight = document.getElementById('map-container').offsetHeight
            $('#map').css('height', mapHeight)

            if ($(koordinatField).val() == null || $(koordinatField).val() == undefined || $(koordinatField)
                .val() == '') {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(setupMap, rejectGeolokasi);
                } else {
                    rejectGeolokasi()
                }
            } else {
                [lat, lng] = ($(koordinatField).val()).split(",");
                rejectGeolokasi(lat, lng)
            }

        });

        let map;

        function pilihLokasi(lat, long, address) {
            $(alamatField).val(address)
            $(alamatField).text(address)
            $(koordinatField).val(lat + ',' + long)
            $('#mapModal').modal('hide');
            @if (isset($modal))
                $('{{ $modal }}').modal('show');
            @endif
        }
        const button = $('<button>', {
            class: 'btn btn-primary mt-2 w-100 btn-pilih-lokasi',
            text: 'Pilih lokasi'
        });
        let marker;
        function markLocation(lat, long, address = null) {
            if (marker !== undefined) {
                map.removeLayer(marker)
            }
            map.setView([lat, long], 16);
            marker = L.marker([lat, long]).addTo(map).bindPopup(`<i class="fa-solid fa-spinner fa-spin me-1"></i> Sedang mendapatkan lokasi...`).openPopup();
            if (address === null) {
                axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}`)
                    .then(response => {
                        content = $('<div>').append(response.data.display_name, '<br/>').append(button[0]);
                        marker._popup.setContent(content[0]);
                        $('.btn-pilih-lokasi').on('click', () => {
                            pilihLokasi(lat, long, response.data.display_name)
                        })
                    });
            } else {
                content = $('<div>').append(address, '<br/>').append(button[0])
                marker._popup.setContent(content[0]);
                $('.btn-pilih-lokasi').on('click', () => {
                    pilihLokasi(lat, long, address)
                })
            }

        }

        function setupMap(position) {
            lat = position.coords.latitude
            long = position.coords.longitude
            let marker;
            if (map === undefined) {
                map = L.map('map').setView([lat, long], 16);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 20,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', function(e) {
                        markLocation(e.geocode.center.lat, e.geocode.center.lng, e.geocode.name)
                    })
                    .addTo(map);

            }
            markLocation(lat, long)
            map.on('click', e => {
                markLocation(e.latlng.lat, e.latlng.lng)
            })
        }

        function rejectGeolokasi(lat = 0.7893, long = 113.9213) {
            position = {
                coords: {
                    latitude: lat,
                    longitude: long
                }
            }
            setupMap(position)
        }
    </script>
@endpush
