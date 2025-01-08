@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Kendaraan</h4>
                <h6>Full details of a vehicle</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="bar-code-view">
                            {!! $qrCode !!}
                        </div>
                        <div class="card">
                            <a href="{{ route('kendaraan.qrcode.single-pdf', $kendaraan->id) }}"
                                class="btn btn-rounded btn-outline-success">
                                Cetak QR Code
                            </a>
                        </div>
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Nama Kendaraan</h4>
                                    <h6>{{ $kendaraan->name }}</h6>
                                </li>
                                <li>
                                    <h4>Plat</h4>
                                    <h6>{{ $kendaraan->plat }}</h6>
                                </li>
                                <li>
                                    <h4>Status</h4>
                                    <h6>{{ $kendaraan->status ? 'Dipinjam' : 'Tidak Dipinjam' }}</h6>
                                </li>
                                <li>
                                    <h4>Kondisi</h4>
                                    <h6>{{ ucfirst($kendaraan->condition) }}</h6>
                                </li>
                                <li>
                                    <h4>Kapasitas</h4>
                                    <h6>{{ $kendaraan->capacity ?? 'Tidak ditentukan' }}</h6>
                                </li>
                                <li>
                                    <h4>Kategori</h4>
                                    <h6>{{ ucfirst($kendaraan->category) }}</h6>
                                </li>
                                <li>
                                    <h4>Warna</h4>
                                    <h6>{{ ucfirst($kendaraan->color) }}</h6>
                                </li>
                                <li>
                                    <h4>Tanggal Pajak</h4>
                                    <h6>{{ $kendaraan->tax ? \Carbon\Carbon::parse($kendaraan->tax)->format('d-m-Y') : 'Tidak ada' }}
                                    </h6>
                                </li>
                                <li>
                                    <h4>Tanggal Garansi</h4>
                                    <h6>{{ $kendaraan->warranty ? \Carbon\Carbon::parse($kendaraan->warranty)->format('d-m-Y') : 'Tidak ada' }}
                                    </h6>
                                </li>
                                <li>
                                    <h4>Parkiran</h4>
                                    <h6>{{ $kendaraan->tempat ? $kendaraan->tempat->name : 'Tidak ada' }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details">
                            <div class="owl-carousel owl-theme product-slide">
                                @if ($kendaraan->photo)
                                    <div class="slider-product">
                                        <img src="{{ asset('storage/' . $kendaraan->photo) }}" alt="Foto Kendaraan">
                                        <h4>{{ basename($kendaraan->photo) }}</h4>
                                        <h6>{{ number_format(filesize(storage_path('app/public/' . $kendaraan->photo)) / 1024, 2) }}
                                            KB</h6>
                                    </div>
                                @else
                                    <h6>Foto tidak tersedia</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('kendaraan.index') }}" class="btn btn-rounded btn-outline-info">Kembali</a>
            <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="btn btn-rounded btn-outline-primary">Edit
                Kendaraan</a>
            <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST" class="d-inline mt-4 ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-rounded btn-outline-danger">Hapus Kendaraan</button>
            </form>
        </div>
    </div>
</div>
@endsection