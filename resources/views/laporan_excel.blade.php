<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan Keuangan</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid black;
            text-align: center;
        }
        @media print {@page {size: landscape;}}
    </style>
</head>
<body>
    <center>
        <h4>LAPORAN TRANSAKSI KEUANGAN</h4>
    </center>
    <?php
    $dari = $_GET["dari"];
    $sampai = $_GET["sampai"];
    $kat = $_GET["kategori"];
    ?>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="11%">Tanggal</th>
                <th rowspan="2" width="5%">Jenis</th>
                <th rowspan="2">Keterangan</th>
                <th rowspan="2">Kategori</th>
                <th colspan="2">Transaksi</th>
            </tr>
            <tr>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_pemasukan = 0;
                $total_pengeluaran = 0;
            @endphp

            @foreach ($laporan as $lapor)
                <tr>
                    <td>{{ date('d-m-Y', strtotime($lapor->tanggal)) }}</td>
                    <td>{{ $lapor->jenis }}</td>
                    <td>{{ $lapor->keterangan }}</td>
                    <td>{{ $lapor->kategori->kategori }}</td>
                    <td>
                        @if ($lapor->jenis == 'Pemasukan')
                            {{ "Rp." . number_format($lapor->nominal) . ",-" }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($lapor->jenis == 'Pengeluaran')
                            {{ "Rp." . number_format($lapor->nominal) . ",-" }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @php
                    if ($lapor->jenis == 'Pemasukan') {
                        $total_pemasukan += $lapor->nominal;
                    } else {
                        $total_pengeluaran += $lapor->nominal;
                    }
                @endphp
            @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4">Total</td>
            <td>{{ "Rp." . number_format($total_pemasukan) . ",-" }}</td> 
            <td>{{ "Rp." . number_format($total_pengeluaran) . ",-" }}</td>
          </tr>
        </tfoot>
    </table>
</body>

</html>
