@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Histori</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="container">
        <div class="row" style="margin-top: 5rem">
            <div class="col">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="form-control">
                                @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ $namaBulan[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control">
                                @php
                                    $tahunAwal = 2022;
                                    $tahunIni = date('Y');
                                @endphp
                                @for ($tahun = $tahunAwal; $tahun <= $tahunIni; $tahun++)
                                <option value="{{ $tahun }}" {{ date('Y') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" id="getData">
                                <ion-icon name="search-outline"></ion-icon>
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" id="showHistori">

            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#getData").click(function(e){
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                $.ajax({
                    type: 'POST',
                    url: '/getHistori',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                    },
                    cache: false,
                    success: function(respond) {
                        $("#showHistori").html(respond);
                    }
                });
            });
        });
    </script>
@endpush