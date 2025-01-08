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
                                    <h6>{{ ucfirst($detailpeminjaman->status) }}</h6>
                                </li>
                                <li>
                                    <h4>Tanggal Dipinjam</h4>
                                    <h6>{{ \Carbon\Carbon::parse($detailpeminjaman->borrowedDate)->format('d-m-Y') }}</h6>
                                </li>
                                <li>
                                    <h4>Estimasi Waktu</h4>
                                    <h6>{{ $detailpeminjaman->estimatedTime ? \Carbon\Carbon::parse($detailpeminjaman->estimatedTime)->format('d-m-Y') : '-' }}</h6>
                                </li>
                                <li>
                                    <h4>Tanggal Kembali</h4>
                                    <h6>{{ $detailpeminjaman->returnDate ? \Carbon\Carbon::parse($detailpeminjaman->returnDate)->format('d-m-Y') : '-' }}</h6>
                                </li>
                                <li>
                                    <h4>Tujuan</h4>
                                    <h6>{{ $detailpeminjaman->objective }}</h6>
                                </li>
                                <li>
                                    <h4>Destinasi</h4>
                                    <h6>{{ $detailpeminjaman->destination }}</h6>
                                </li>
                                <li>
                                    <h4>Penumpang</h4>
                                    <h6>{{ $detailpeminjaman->passenger }}</h6>
                                </li>
                                <li>
                                    <h4>Alasan Dibatalkan</h4>
                                    <h6>{{ $detailpeminjaman->canceledReason }}</h6>
                                </li>
                                <li>
                                    <h4>Pengguna</h4>
                                    <h6>{{ $detailpeminjaman->user->name }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('detailpeminjaman.index') }}" class="btn btn-rounded btn-outline-danger">Kembali</a>
        </div>
    </div>
</div>
@endsection
