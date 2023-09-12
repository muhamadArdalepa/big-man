<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>
.card-body.flex-shrink-0 {
    transition: margin-left 300ms ease-in-out;
}

.card-body.flex-shrink-0 div {
    transition: opacity 300ms ease-in-out;
}

.img-container {
    background-size: cover;
    min-height: 150px;
    max-height: 300px;
}

#map>div.leaflet-pane.leaflet-map-pane>div.leaflet-pane.leaflet-popup-pane>div>div.leaflet-popup-content-wrapper>div {
    width: 300px !important;
}
</style>