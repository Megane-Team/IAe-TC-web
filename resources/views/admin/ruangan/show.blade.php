@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Ruangan</h4>
                <h6>Full details of a room</h6>
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
                            <a href="{{ route('ruangan.qrcode.single-pdf', $ruangan->id) }}"
                                class="btn btn-rounded btn-outline-success">
                                Cetak QR Code
                            </a>
                        </div>
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Kode Ruangan</h4>
                                    <h6>{{ $ruangan->code }}</h6>
                                </li>
                                <li>
                                    <h4>Status</h4>
                                    <h6>{{ $ruangan->status ? 'Dipinjam' : 'Tidak Dipinjam' }}</h6>
                                </li>
                                <li>
                                    <h4>Kapasitas</h4>
                                    <h6>{{ $ruangan->capacity }}</h6>
                                </li>
                                <li>
                                    <h4>Kategori</h4>
                                    <h6>{{ ucfirst($ruangan->category)}}</h6>
                                </li>
                                <li>
                                    <h4>Gedung</h4>
                                    <h6>{{ $ruangan->tempat->name ?? 'Tidak ada' }}</h6>
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
                                @if ($ruangan->photo)
                                    <div class="slider-product">
                                        <img src="{{ asset('storage/' . $ruangan->photo) }}" alt="Foto Ruangan">
                                        <h4>{{ basename($ruangan->photo) }}</h4>
                                        <h6>{{ number_format(filesize(storage_path('app/public/' . $ruangan->photo)) / 1024, 2) }}
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
            <a href="{{ route('ruangan.index') }}" class="btn btn-rounded btn-outline-info">Kembali</a>
            <a href="{{ route('ruangan.edit', $ruangan->id) }}" class="btn btn-rounded btn-outline-primary">Edit
                Ruangan</a>
            <form action="{{ route('ruangan.destroy', $ruangan->id) }}" method="POST" class="d-inline mt-4 ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-rounded btn-outline-danger">Hapus Ruangan</button>
            </form>
        </div>
    </div>
</div>
@endsection