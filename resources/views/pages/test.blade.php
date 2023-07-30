<!DOCTYPE html>
<html>
<head>
    <title>Get User Location</title>
</head>
<body>
    <button onclick="getLocation()">Get My Location</button>
    <p id="location"></p>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                document.getElementById('location').textContent = 'Geolocation is not supported by this browser.';
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            document.getElementById('location').textContent = `Latitude: ${latitude}, Longitude: ${longitude}`;
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    document.getElementById('location').textContent = 'User denied the request for Geolocation.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    document.getElementById('location').textContent = 'Location information is unavailable.';
                    break;
                case error.TIMEOUT:
                    document.getElementById('location').textContent = 'The request to get user location timed out.';
                    break;
                case error.UNKNOWN_ERROR:
                    document.getElementById('location').textContent = 'An unknown error occurred.';
                    break;
            }
        }
    </script>
</body>
</html>
