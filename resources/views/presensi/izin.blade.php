@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Perizinan</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="container">
        <div class="row" style="margin-top: 4.5rem;">
            <div class="col">
                @php
                    $messageSuccess = Session::get('success');
                    $messageError = Session::get('error');
                @endphp
                @if (Session::get('success'))
                    <div class="alert alert-success mb-2">
                        {{ $messageSuccess }}
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger mb-2">
                        {{ $messageError }}
                    </div>
                @endif
            </div>
        </div>
        @foreach ($dataIzin as $d)
            <ul class="listview image-listview">
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <b>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}
                                    ({{ $d->status == 'i' ? 'Izin' : 'Sakit' }})</b><br>
                                <small class="text-muted">{{ $d->keterangan }}</small>
                            </div>
                            @if ($d->status_approved == '0')
                                <span class="badge badge-warning">Waiting</span>
                            @elseif ($d->status_approved == '1')
                                <span class="badge badge-success">Approved</span>
                            @elseif ($d->status_approved == '2')
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        @endforeach
        <div class="fab-button bottom-right" style="margin-bottom: 4.5rem">
            <a href="/buatIzin" class="fab">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div>
    </div>
@endsection
