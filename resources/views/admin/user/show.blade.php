@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Pengguna</h4>
                <h6>Full details of a user</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="bar-code-view text-center">
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Pengguna"
                                    class="img-fluid rounded mb-3"
                                    style="max-width: 150px; max-height: 150px; object-fit: cover;">
                            @else
                                <p>Tidak ada foto yang tersedia.</p>
                            @endif
                            <a class="printimg" href="#">
                                <img src="{{ asset('assets/img/icons/printer.svg') }}" alt="print" class="mt-2"
                                    style="width: 24px; height: 24px;">
                            </a>
                        </div>
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Nama</h4>
                                    <h6>{{ $user->name }}</h6>
                                </li>
                                <li>
                                    <h4>Email</h4>
                                    <h6>{{ $user->email }}</h6>
                                </li>
                                <li>
                                    <h4>NIK</h4>
                                    <h6>{{ $user->nik }}</h6>
                                </li>
                                <li>
                                    <h4>Role</h4>
                                    <h6>{{ $user->role }}</h6>
                                </li>
                                <li>
                                    <h4>Unit</h4>
                                    <h6>{{ $user->unit }}</h6>
                                </li>
                                <li>
                                    <h4>Alamat</h4>
                                    <h6>{{ $user->address }}</h6>
                                </li>
                                <li>
                                    <h4>Nomor Telepon</h4>
                                    <h6>{{ $user->phoneNumber }}</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="slider-product-details text-center">
                            <div class="owl-carousel owl-theme product-slide">
                                @if ($user->photo)
                                    <div class="slider-product">
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Pengguna"
                                            class="img-fluid rounded mx-auto d-block"
                                            style="max-width: 100%; height: auto; object-fit: cover; max-height: 300px;">
                                        <h4 class="mt-2">{{ basename($user->photo) }}</h4>
                                        <h6>{{ number_format(filesize(storage_path('app/public/' . $user->photo)) / 1024, 2) }}
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

            <div class="mb-4">
                <a href="{{ route('user.index') }}" class="btn btn-rounded btn-outline-info">Kembali</a>
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-rounded btn-outline-primary">Edit
                    Pengguna</a>
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline mt-4 ms-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-rounded btn-outline-danger"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus
                        Pengguna</button>
                </form>
            </div>
        </div>
    </div>
    @endsection