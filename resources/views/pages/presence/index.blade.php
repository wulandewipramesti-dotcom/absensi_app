@extends('layouts.main')
@section('conten')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="card-header mb-3">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">
                            Daftar Kegiatan
                        </h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{route('presence.create')}}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                </div>
                <!-- Form Search -->
                <form method="GET" action="{{ route('presence.index') }}" class="mt-3">
    <div class="row g-2">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari nama kegiatan..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <input type="date" name="tgl_kegiatan" class="form-control" value="{{ request('tgl_kegiatan') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
            @if(request('search') || request('tgl_kegiatan'))
                <a href="{{ route('presence.index') }}" class="btn btn-outline-danger">Reset</a>
            @endif
        </div>
    </div>
</form>

            </div>

            <table class="table table-striped" id="">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Waktu Mulai</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($presences->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>                  
                    @endif
                    @foreach ($presences as $presence)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $presence->nama_kegiatan }}</td>
                            <td>{{ $presence->tgl_kegiatan->format('d-m-Y') }}</td>
                            <td>{{ $presence->tgl_kegiatan->format('H:i') }}</td>
                            <td>
                                <a href="{{ route('presence.show', $presence->id )}}" class="btn btn-secondary">
                                    Detail
                                </a>
                                <a href="{{ route('presence.edit', $presence->id )}}" class="btn btn-warning">
                                    Edit
                                </a>
                                <form action="{{ route('presence.destroy', $presence->id )}}" method="POST" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah yakin ingin menghapus data ini')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                     @endforeach
                </tbody>
            </table>
        </div>
     </div>
</div>
@endsection
