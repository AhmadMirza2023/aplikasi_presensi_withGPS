<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page {
            size: A4
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi tr th {
            border: 1px solid #000000;
            padding: 8px;
            background-color: #cfcfcf;
        }

        .tabelpresensi tr td {
            border: 1px solid #000000;
            padding: 5px;
            font-size: 12px;
        }

        .foto {
            width: 40px;
            height: 40px;
        }
    </style>
</head>

<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td style="width: 130px">
                    <img src="{{ asset('assets/img/company_logo.png') }}" width="100" height="100" alt="">
                </td>
                <td>
                    <h3>
                        LAPORAN PRESENSI KARYAWAN<br>
                        PERIODE {{ strtoupper($namaBulan[$bulan]) }} {{ $tahun }}<br>
                        PT. Ahmad Mirza Rafiq Azmi<br>
                    </h3>
                    <span style="position: relative; bottom: 15px;">
                        <i>Jln. Ikan Gurami No. 100, Kecamatan Bandeng, Kabupaten Lele</i>
                    </span>
                </td>
            </tr>
        </table>
        <table style="margin-top: 20px; font-size: 17px;">
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                    @endphp
                    <img src="{{ url($path) }}" width="120" height="160" style="margin-right: 20px;"
                        alt="">
                </td>
            </tr>
            <tr>
                <td>Nik</td>
                <td>:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $karyawan->nama_dept }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <th>No</th>
                <th>Tangal</th>
                <th>Jam Mausk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Jml Jam Kerja</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $foto_in = Storage::url('uploads/absensi/' . $d->foto_in);
                    $foto_out = Storage::url('uploads/absensi/' . $d->foto_out);
                    $jamTerlambat = selisih('07:00:00', $d->jam_in);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td>
                        <img src="{{ url($foto_in) }}" class="foto" alt="Foto Absen Masuk">
                    </td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                    <td>
                        @if ($d->jam_out != null)
                            <img src="{{ url($foto_out) }}" class="foto" alt="Foto Absen Pulang">
                        @else
                            <img src="{{ asset('assets/img/no_photo.png') }}" class="foto" alt="Foto Absen Pulang">
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_in >= '07:00')
                            Terlambat {{ $jamTerlambat }}
                        @else
                            Tepat Waktu
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_out != null)
                            @php
                                $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                            @endphp
                        @else
                            @php
                                $jmljamkerja = 0;
                            @endphp
                        @endif
                        {{ $jmljamkerja }}
                    </td>
                </tr>
            @endforeach
        </table>
        <table width="100%" style="margin-top: 150px;">
            <tr>
                <td colspan="2" style="text-align: right;">
                    Lamongan, {{ date('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td height="100px" style="text-align: center; vertical-align: bottom">
                    <u>Ansor</u><br>
                    <i><b>HRD Manager</b></i>
                </td>
                <td style="text-align: center; vertical-align: bottom">
                    <u>Depan</u><br>
                    <i><b>Direktur</b></i>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
