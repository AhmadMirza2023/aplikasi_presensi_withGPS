@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        /* #1e74fd */
        .datepicker-modal {
            max-height: 430px !important;
        }
        .datepicker-date-display {
            background-color: #1e74fd !important;
        }
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="container" style="height: 450px">
        <div class="row" style="margin-top: 4.5rem">
            <div class="col">
                <form id="formIzin" method="POST" action="/storeIzin">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="tgl_izin" name="tgl_izin" placeholder="Tanggal" class="form-control datepicker">
                    </div>
                    <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control" placeholder="keterangan"></textarea>
                    </div>
                    <div class="form-group" id="kirim">
                        <button class="btn btn-primary btn-block">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"    
            });
        });

        $('#formIzin').submit(function() {
            var tgl_izin = $('#tgl_izin').val();
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();

            if (tgl_izin == '') {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal harus Diisi',
                    icon: 'error',
                });
                return false;
            } else if (status == '') {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Status harus Diisi',
                    icon: 'error',
                });
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan harus Diisi',
                    icon: 'error',
                });
                return false;
            }
        });
    </script>
@endpush