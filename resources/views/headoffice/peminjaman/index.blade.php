@extends('layouts.headoffice.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Peminjaman Aset</h4>
                <h6>Kelola data peminjaman</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                                <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                    alt="img"></a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a href="{{ route('peminjaman.downloadPDF') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="PDF">
                                    <img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="PDF">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('peminjaman.exportExcel') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Excel">
                                    <img src="{{ asset('assets/img/icons/excel.svg') }}" alt="Excel">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-0" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="row align-items-end">
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select id="filter-category" class="form-control form-small select">
                                                <option value="" selected>Semua Kategori</option>
                                                <option value="barang">Barang</option>
                                                <option value="kendaraan">Kendaraan</option>
                                                <option value="ruangan">Ruangan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Ruangan</label>
                                            <select id="filter-ruangan" class="form-control form-small select">
                                                <option value="" selected>Semua Ruangan</option>
                                                @foreach($ruangans as $ruangan)
                                                    <option value="{{ $ruangan->id }}">{{ $ruangan->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Kendaraan</label>
                                            <select id="filter-kendaraan" class="form-control form-small select">
                                                <option value="" selected>Semua Kendaraan</option>
                                                @foreach($kendaraans as $kendaraan)
                                                    <option value="{{ $kendaraan->id }}">{{ $kendaraan->plat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Barang</label>
                                            <select id="filter-barang" class="form-control form-small select">
                                                <option value="" selected>Semua Barang</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}">{{ $barang->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12 d-flex align-items-end">
                                        <div class="form-group">
                                            <button class="btn btn-filters" id="btn-filter">
                                                <img src="{{ asset('assets/img/icons/search-whites.svg') }}" alt="img">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table datanew table-bordered">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Barang</th>
                                <th>Kendaraan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $peminjaman)
                                <tr>
                                    <td>{{ $peminjaman->user->name }}</td>
                                    <td>{{ ucfirst($peminjaman->category) }}</td>
                                    <td>{{ $peminjaman->ruangan->code ?? 'Tidak ada' }}</td>
                                    <td>{{ $peminjaman->barang->code ?? 'Tidak ada' }}</td>
                                    <td>{{ $peminjaman->kendaraan->plat ?? 'Tidak ada' }}</td>
                                    <td>
                                        <a class="me-3" href="{{ route('peminjaman.show', $peminjaman) }}">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="Show">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
</style>

<script>
    document.getElementById('btn-filter').addEventListener('click', function () {
        const category = document.getElementById('filter-category').value;
        const ruangan = document.getElementById('filter-ruangan').value;
        const kendaraan = document.getElementById('filter-kendaraan').value;
        const barang = document.getElementById('filter-barang').value;
        const query = new URLSearchParams({ category, ruangan, kendaraan, barang }).toString();
        window.location.href = `{{ route('peminjaman.index') }}?${query}`;
    });
</script>

@endsection