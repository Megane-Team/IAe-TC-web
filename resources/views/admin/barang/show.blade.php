@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Barang</h4>
                <h6>Full details of a product</h6>
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
                            <a href="{{ route('barang.qrcode.single-pdf', $barang->id) }}"
                                class="btn btn-rounded btn-outline-success">
                                Cetak QR Code
                            </a>
                        </div>
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Nama Barang</h4>
                                    <h6>{{ $barang->name }}</h6>
                                </li>
                                <li>
                                    <h4>Kode</h4>
                                    <h6>{{ $barang->code }}</h6>
                                </li>
                                <li>
                                    <h4>Kondisi</h4>
                                    <h6>{{ ucfirst($barang->condition) }}</h6>
                                </li>
                                <li>
                                    <h4>Status</h4>
                                    <h6>{{ $barang->status ? 'Dipinjam' : 'Tidak Dipinjam' }}</h6>
                                </li>
                                <li>
                                    <h4>Tanggal Garansi</h4>
                                    <h6>{{ \Carbon\Carbon::parse($barang->warranty)->format('d-m-Y') }}</h6>
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
                                @if ($barang->photo)
                                    <div class="slider-product">
                                        <img src="{{ asset('storage/' . $barang->photo) }}" alt="Foto Barang">
                                        <h4>{{ basename($barang->photo) }}</h4>
                                        <h6>{{ number_format(filesize(storage_path('app/public/' . $barang->photo)) / 1024, 2) }}
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
            <a href="{{ route('barang.index') }}" class="btn btn-rounded btn-outline-info">Kembali</a>
            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-rounded btn-outline-primary">Edit
                Barang</a>
            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline mt-4 ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-rounded btn-outline-danger">Hapus Barang</button>
            </form>
        </div>
    </div>
</div>
@endsection