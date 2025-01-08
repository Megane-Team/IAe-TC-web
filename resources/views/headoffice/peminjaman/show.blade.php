@extends('layouts.headoffice.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Peminjaman</h4>
                <h6>Informasi lengkap peminjaman</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Status</h4>
                                    <h6>{{ ucfirst($peminjaman->status) }}</h6>
                                </li>
                                <li>
                                    <h4>Tanggal Peminjaman</h4>
                                    <h6>{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d-m-Y H:i:s') }}</h6>
                                </li>
                                <li>
                                    <h4>Kategori</h4>
                                    <h6>{{ ucfirst($peminjaman->category) }}</h6>
                                </li>
                                <li>
                                    <h4>Ruangan ID</h4>
                                    <h6>{{ $peminjaman->ruangan_id ?? 'Tidak ada' }}</h6>
                                </li>
                                <li>
                                    <h4>Barang ID</h4>
                                    <h6>{{ $peminjaman->barang_id ?? 'Tidak ada' }}</h6>
                                </li>
                                <li>
                                    <h4>Kendaraan ID</h4>
                                    <h6>{{ $peminjaman->kendaraan_id ?? 'Tidak ada' }}</h6>
                                </li>
                                <li>
                                    <h4>Waktu Dibuat</h4>
                                    <h6>{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d-m-Y H:i:s') }}</h6>
                                </li>
                                <li>
                                    <h4>Waktu Diperbarui</h4>
                                    <h6>{{ \Carbon\Carbon::parse($peminjaman->updated_at)->format('d-m-Y H:i:s') }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-rounded btn-outline-danger">Kembali</a>
        </div>
    </div>
</div>
@endsection
