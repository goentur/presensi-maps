<!DOCTYPE html>
<html>

<head>
    <title>LAPORAN PRESENSI {{ $bulan }}</title>
    <style type="text/css">
        .page-header {
            position: relative;
            top: 1mm;
            width: 100%;
        }

        .page-header-space {
            height: 5mm;
        }

        .page-footer {
            position: fixed;
            bottom: 1mm;
            width: 100%;
        }

        .page-footer-space {
            height: 5mm;
        }

        @media print {
            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            body {
                margin: 0;
            }

            .page {
                break-before: page
            }
        }

        table.tbl {
            width: 100%;
            border-collapse: collapse;
        }

        table.tbl th {
            padding: 6px;
        }

        table.tbl td {
            padding: 5px;
        }
    </style>
</head>

<body>
    
    <div class="page-header">
        <table width="95%" style="margin-left: 2.5%;margin-right: 2.5%" cellpadding="0" cellspacing="0" border="0">
            <td rowspan="4" width="15%" align="center">
                <img style="height : 75px" src="{{ asset('img/icons/logo.png') }}" alt="Logo">
            </td>
            <td align="">
                <span style="color: black; font-family: 'Times New Roman'; font-size: 25px;font-weight: bold;">XXX</span><br>
                <span style="color: black; font-family: 'Times New Roman'; font-size: 14px;font-weight: bold;">XXXXXXXXXXXX</span>
            </td>
        </table>
        <table width="95%" style="margin-left: 2.5%;margin-right: 2.5%;margin-bottom: 1%" cellpadding="1" cellspacing="1" border="1"></table>
    </div>
    <table width="100%">
        <thead>
            <tr>
                <td>
                    <!--place holder for the fixed-position header-->
                    <div class="page-header-space"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <!--*** CONTENT GOES HERE ***-->
                    <div class="page">
                    <h1 style="text-align: center;margin: 0px;padding: 0px">{{ Str::upper($attribute['title'].' PRESENSI') }}</h1>
                    <table width="95%" style="margin-left: 2.5%;margin-right: 2.5%;margin-bottom: 2.5%;" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="30%">NIP</td>
                            <td width="1%">:</td>
                            <td>{{ $pegawai->nip }}</td>
                        </tr>
                        <tr>
                            <td>NAMA</td>
                            <td>:</td>
                            <td>{{ $pegawai->user->name }}</td>
                        </tr>
                        <tr>
                            <td>JABATAN</td>
                            <td>:</td>
                            <td>{{ $pegawai->jabatan->nama }}</td>
                        </tr>
                        <tr>
                            <td>TEMPAT KERJA</td>
                            <td>:</td>
                            <td>{{ $pegawai->tempat_kerja->nama }}</td>
                        </tr>
                        <tr>
                            <td>PERIODE PRESENSI</td>
                            <td>:</td>
                            <td>{{ $bulan }}</td>
                        </tr>
                    </table>
                    <table width="95%" style="margin-left: 2.5%;margin-right: 2.5%;border-collapse: collapse" cellpadding="3" cellspacing="3" border="1">
                        <thead>
                            <tr>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">NO</th>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">TANGGAL</th>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">MASUK</th>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">PULANG</th>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">FOTO MASUK</th>
                                    <th align="center" style="color: black; font-family: 'Times New Roman'; font-size: 14px">FOTO PULANG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $value)
                            <tr>
                                    <td align="center" style="color: black; font-family: 'Times New Roman'; font-size: 12px">{{ $value['no'] }}</td>
                                    <td style="color: black; font-family: 'Times New Roman'; font-size: 12px">{!! $value['tanggal'] !!}</td>
                                    <td style="color: black; font-family: 'Times New Roman'; font-size: 12px">{!! $value['masuk'] !!}</td>
                                    <td style="color: black; font-family: 'Times New Roman'; font-size: 12px">{!! $value['keluar'] !!}</td>
                                    <td style="color: black; font-family: 'Times New Roman'; font-size: 12px;text-align: center">{!! $value['foto_masuk'] !!}</td>
                                    <td style="color: black; font-family: 'Times New Roman'; font-size: 12px;text-align: center">{!! $value['foto_pulang'] !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <!--place holder for the fixed-position footer-->
                    <div class="page-footer-space">
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="page-footer">
        <div style="text-align: right;width: 97%;">
            <span style="color: black; font-family: 'Times New Roman';font-size: 12px;font-style: italic">dicetak pada : {{ date('d-m-Y H:i:s') }}</span>
        </div>
    </div>
    <script type="text/javascript">
        window.print();
        window.focus();
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>

</html>