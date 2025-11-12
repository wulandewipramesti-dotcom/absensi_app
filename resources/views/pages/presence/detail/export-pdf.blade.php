<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Absensi</title>
</head>

<style>
    .text-center{
        text-align: center;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .mb-2 {
        margin-bottom: 20px;
    }


    .table th,
    .table td {
        border: 1px solid black;
        padding: 10px 4px;
    }
</style>
<body>
    

    <h1 class="text-center">Absensi Online</h1>

     <table class="mb-2">
                        <tr>
                            <td width="150">Nama Kegiatan</td>
                            <td widht="20">:</td>
                            <td>{{$presence->nama_kegiatan}}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Kegiatan</td>
                            <td>:</td>
                            <td>{{ date('d F Y', strtotime($presence->tgl_kegiatan)) }}</td>
                        </tr>
                        <tr>
                            <td>Waktu Mulai</td>
                            <td>:</td>
                            <td>{{ date('H:i', strtotime($presence->tgl_kegiatan)) }}</td>
                        </tr>
                    </table>
                    <table class="table">
                           <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Asal Instansi</th>
                            <th>Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($presenceDetails->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>                  
                        @endif
                        @foreach ($presenceDetails as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->nama}}</td>
                            <td>{{ $detail->jabatan}}</td>
                            <td>{{ $detail->asal_instansi}}</td>
                            <td>
                             @if ($detail->tanda_tangan)
                                @php
                                    $path = public_path('uploads/' . $detail->tanda_tangan);
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $img = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                @endphp
                                <img src="{{$img}}" alt="Absen" style="max-width: 100%;max-height:40px">
                              @endif
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                    </table>
</body>
</html>