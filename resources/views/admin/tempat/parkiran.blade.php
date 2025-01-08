@extends('layouts.admin.app') <!-- Pastikan ini adalah layout yang benar -->

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Daftar Tempat Parkiran</h1>
    <a href="{{ route('tempat.create') }}" class="btn btn-primary mb-3">Tambah Tempat Parkiran</a>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Tempat Parkiran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tempats as $tempat)
                        @if($tempat->category == 'parkiran') <!-- Memastikan hanya menampilkan kategori parkiran -->
                        <tr>
                            <td>{{ $tempat->name }}</td>
                            <td>{{ $tempat->category }}</td>
                            <td>
                                <a href="{{ route('tempat.show', $tempat) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('tempat.edit', $tempat) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('tempat.destroy', $tempat) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection