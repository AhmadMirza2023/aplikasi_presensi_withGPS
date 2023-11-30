<style>
    #map {
        height: 250px;
    }
</style>

<div id="map"></div>

<script>
    var lokasi_kantor = "{{ $lok_kantor->lokasi_kantor }}";
    var lok = lokasi_kantor.split(",");
    var lat_kantor = lok[0];
    var long_kantor = lok[1];
    var lokasi = '{{ $presensi->lokasi_in }}';
    var lok = lokasi.split(',');
    var latitude = lok[0];
    var longitude = lok[1];
    var map = L.map('map').setView([latitude, longitude], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    // -7.289196, 112.799016 Alamat Kos
    var circle = L.circle([lat_kantor, long_kantor], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: {{ $lok_kantor->radius }}
    }).addTo(map);
    var popup = L.popup()
        .setLatLng([latitude, longitude])
        .setContent("{{ $presensi->nama_lengkap }}")
        .openOn(map);
</script>
