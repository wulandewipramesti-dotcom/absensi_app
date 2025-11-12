<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .signature-pad {
        border: 1px solid #ced4da;
        height: 200px;
      }
    </style>
  </head>
  <body>

    <div class="container my-5">
        <div class="card mb-4">
            <div class="card-body">
                 <h4 class="text-center">Absensi</h4>
                 <table class="table table-borderless">
                        <tr>
                            <td width="150">Nama Kegiatan</td>
                            <td width="20">:</td>
                            <td>{{ $presence->nama_kegiatan }}</td>
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
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Absensi</h5>
                    </div>
                    <div class="card-body">
                        <form id="form-absen" action="{{ route('absen.save', $presence->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                                @error('nama')
                                <div class="text-danger">{{ $message }}</div>               
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan">
                                @error('jabatan')
                                <div class="text-danger">{{ $message }}</div>               
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="asal_instansi">Asal Instansi</label>
                                <input type="text" class="form-control" id="asal_instansi" name="asal_instansi">
                                @error('asal_instansi')
                                <div class="text-danger">{{ $message }}</div>               
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanda_tangan">Tanda Tangan</label>
                                <div class="d-block form-control mb-2 p-0">
                                    <canvas id="signature-pad" class="signature-pad"></canvas>
                                </div>
                                <textarea name="signature" id="signature64" class="d-none"></textarea>
                                @error('signature')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <button type="button" id="clear" class="btn btn-sm btn-secondary">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                  </svg>  
                                  Clear
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Kehadiran</h5>
                    </div>
                    <div class="card-body">
                       <table class="table table-stringpad">
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
                              <img src="{{ asset('uploads/' . $detail->tanda_tangan) }}" 
                              alt="Tanda Tangan" 
                              width="100">
                              @endif
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                       </table>
                        <!-- Isi daftar hadir -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Perbaiki jQuery, hapus integrity sementara -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/signature.min.js') }}"></script>

    <script>
        $(function() {
            // Sesuaikan lebar canvas dengan parent
            let sig = $('#signature-pad').parent().width();
            $('#signature-pad').attr('width', sig);
            $('#signature-pad').attr('height', 200); // tinggi canvas


            let signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
                backgroundColor: 'rgba(255, 255, 255, 0)', // transparan
                penColor: 'rgb(0, 0, 0)',
            });

            // menyimpan ke textarea
            $('canvas').on('mouseup touchend', function() {
              $('#signature64').val(signaturePad.toDataURL());
            });

            // clear signature
            $('#clear').on('click', function(e) {
              e.preventDefault();
              signaturePad.clear();
              $('#signature64').val('');
            });

            // submit form
            $('#form-absen').on('submit', function(){
              $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            });

        });
    </script>

  </body>
</html>