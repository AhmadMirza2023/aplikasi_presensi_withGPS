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
            font-size: 12px;
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

<body class="A4 landscape">
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
        <table class="tabelpresensi">
            <tr>
                <th rowspan="2">Nik</th>
                <th rowspan="2">Nama Karyawan</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TT</th>
            </tr>
            <tr>
                <?php
                for($i=1; $i<=31; $i++){
                ?>
                <th>{{ $i }}</th>
                <?php
                }
                ?>
            </tr>
            @foreach ($rekap as $d)
                <tr>
                    <td>{{ $d->nik }}</td>
                    <td>{{ $d->nama_lengkap }}</td>

                    <?php
                    $totalhadir = 0;
                    $totalterlambat = 0;
                    for($i=1; $i<=31; $i++){
                        $tgl = "tgl_" . $i;
                        if (empty($d->$tgl)) {
                            $hadir = ['', ''];
                            $totalhadir += 0;
                            $totalterlambat += 0;
                        } else {
                            $hadir = explode('-', $d->$tgl);
                            $totalhadir += 1;
                            if ($hadir[1]>'07:00:00'){
                                $totalterlambat += 1;
                            }
                        }
                    ?>

                    <td>
                        <span style="color: {{ $hadir[0] > '07:00:00' ? 'red' : '' }}">
                            {{ $hadir[0] }}
                        </span><br>
                        <span style="color: {{ $hadir[1] < '16:00:00' ? 'red' : '' }}">
                            {{ $hadir[1] }}
                        </span>
                    </td>

                    <?php
                    }
                    ?>
                    <td>{{ $totalhadir }}</td>
                    <td>{{ $totalterlambat }}</td>
                </tr>
            @endforeach
        </table>
        <table width="100%" style="margin-top: 20px;">
            <tr>
                <td></td>
                <td style="text-align: center;">
                    Lamongan, {{ date('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td height="100px" style="text-align: center; vertical-align: bottom; width: 50%;">
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
