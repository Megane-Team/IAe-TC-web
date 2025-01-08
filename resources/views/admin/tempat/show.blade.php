@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Detail Tempat</h4>
                <h6>Full details of a place</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="bar-code-view">
                            @if ($tempat->photo)
                                <img src="{{ asset('storage/' . $tempat->photo) }}" alt="Foto Tempat"
                                    class="w-32 h-32 object-cover mt-2">
                            @else
                                <div class="text-muted text-center">
                                    <p><em>Foto tidak tersedia</em></p>
                                    <img src="{{ asset('assets/img/icons/no-image.svg') }}" alt="No Image" class="w-32 h-32 object-cover mt-2">
                                </div>
                            @endif
                            <a class="printimg">
                                <img src="{{ asset('assets/img/icons/printer.svg') }}" alt="print">
                            </a>
                        </div>
                        <div class="productdetails">
                            <ul class="product-bar">
                                <li>
                                    <h4>Nama Tempat</h4>
                                    <h6>{{ $tempat->name }}</h6>
                                </li>
                                <li>
                                    <h4>Kategori</h4>
                                    <h6>{{ $tempat->category }}</h6>
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
                                @if ($tempat->photo)
                                    <div class="slider-product">
                                        <img src="{{ asset('storage/' . $tempat->photo) }}" alt="Foto Tempat">
                                        <h4>{{ basename($tempat->photo) }}</h4>
                                        <h6>{{ number_format(filesize(storage_path('app/public/' . $tempat->photo)) / 1024, 2) }}
                                            KB</h6>
                                    </div>
                                @else
                                    <div class="text-muted text-center">
                                        <p><em>Foto tidak tersedia</em></p>
                                        <img src="{{ asset('assets/img/icons/no-image.svg') }}" alt="No Image" class="w-32 h-32 object-cover mt-2">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('tempat.index') }}" class="btn btn-rounded btn-outline-info">Kembali</a>
            <a href="{{ route('tempat.edit', $tempat->id) }}" class="btn btn-rounded btn-outline-primary">Edit
                Tempat</a>
            <form action="{{ route('tempat.destroy', $tempat->id) }}" method="POST" class="d-inline mt-4 ms-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-rounded btn-outline-danger">Hapus Tempat</button>
            </form>
        </div>
    </div>
</div>
@endsection
