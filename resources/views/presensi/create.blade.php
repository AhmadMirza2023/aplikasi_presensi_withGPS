@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

    {{-- css dan js untuk section content, start --}}
    <style>
        .webcam-capture, .webcam-capture video{
            display: inline-block;
            margin: auto;
            width: 100% !important;
            height: auto !important;
            border-radius: 15px;
        }
        #map { height: 180px; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    {{-- css dan js untuk section content, end --}}
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        @if ($cek > 0)
        <div class="col">
            <button id="takeabsen" class="btn btn-danger btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Pulang
            </button>
        </div>
        @else
        <div class="col">
            <button id="takeabsen" class="btn btn-primary btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Masuk
            </button>
        </div>
        @endif
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('myscript')
{{-- script start --}}
    <script>
        // script untuk mengatur Webcam, start
        var notifikasi_in = document.getElementById('notifikasi_in');
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');
        // script untuk mengatur Webcam, end

        // script untuk mengatur maps, start
        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallBack, errorCallBack)
        }

        function successCallBack (position) {
            lokasi.value = position.coords.latitude + ',' + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([-7.2891713, 112.7989571], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20
            }).addTo(map);
        }
        // script untuk mengatur maps, end

        function errorCallBack (){

        }

        // script untuk mengatur absen ketika di klik, start
        $('#takeabsen').click(function(e){
            Webcam.snap(function(uri){
                image = uri;
            });
            var lokasi = $('#lokasi').val();
            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                chace: false,
                success: function(respond){
                    var status = respond.split('|');
                    if (status[0] == "success") {
                        Swal.fire({
                            title: 'Berhasil !',
                            text: status[1],
                            icon: 'success',
                        }).then((result) => {
                            window.location.href = '/dashboard';
                        })
                    } else {
                        Swal.fire({
                            title: 'Gagal !',
                            text: status[1],
                            icon: 'error',
                        })
                    }
                }
            });
        });
        // script untuk mengatur absen ketika di klik, end

    </script>
{{-- script end --}}
@endpush