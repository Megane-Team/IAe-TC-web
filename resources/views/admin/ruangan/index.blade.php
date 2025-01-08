@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Ruangan</h4>
                <h6>Manage your rooms</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('ruangan.create') }}" class="btn btn-added">
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Ruangan
                </a>
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
                        <div class="ms-2">
                            <button type="button"
                                class="btn btn-rounded btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
                                onclick="deleteSelected()">
                                <span class="d-flex align-items-center">
                                    <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete"
                                        class="me-1">Hapus Terpilih
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a href="{{ route('ruangan.downloadPDF') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="PDF">
                                    <img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="PDF">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ruangan.exportExcel') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Excel">
                                    <img src="{{ asset('assets/img/icons/excel.svg') }}" alt="Excel">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ruangan.qrcode-pdf') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="QRCode"><img
                                        src="{{ asset('assets/img/icons/printer.svg') }}" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-0" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="filter-status" class="select">
                                        <option value="" disabled selected>Pilih Status</option>
                                        <option value="">Lainnya</option>
                                        <option value="1">Dipinjam</option>
                                        <option value="0">Tidak Dipinjam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select id="filter-category" class="select">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <option value="">Lainnya</option>
                                        <option value="kelas">Kelas</option>
                                        <option value="lab">Lab</option>
                                        <option value="gudang">Gudang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12 d-flex align-items-end">
                                <div class="form-group me-2 flex-grow-1">
                                    <label>Tempat</label>
                                    <select id="filter-tempat" class="form-control form-small select">
                                        <option value="" disabled selected>Pilih Tempat</option>
                                        <option value="">Lainnya</option>
                                        @foreach($tempats as $tempat)
                                            <option value="{{ $tempat->id }}">{{ $tempat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-filters" id="btn-filter">
                                        <img src="{{ asset('assets/img/icons/search-whites.svg') }}" alt="img">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table datanew table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Foto</th>
                                <th>Kode</th>
                                <th>Status</th>
                                <th>Kapasitas</th>
                                <th>Kategori</th>
                                <th>Gedung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruangans as $ruangan)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="delete-checkbox" value="{{ $ruangan->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <img src="{{ $ruangan->photo ? asset('storage/' . $ruangan->photo) : asset('assets/img/product/default.jpg') }}"
                                            alt="Foto" style="width: 50px; height: auto;">
                                    </td>
                                    <td>{{ $ruangan->code }}</td>
                                    <td>{{ $ruangan->status ? 'Dipinjam' : 'Tidak Dipinjam' }}</td>
                                    <td style="text-align: center; vertical-align: middle;">{{ $ruangan->capacity }}</td>
                                    <td>{{ ucfirst($ruangan->category) }}</td>
                                    <td>{{ $ruangan->tempat->name ?? 'Tidak ada' }}</td>
                                    <td>
                                        <a class="me-3" href="{{ route('ruangan.show', $ruangan) }}">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="Show">
                                        </a>
                                        <a class="me-3" href="{{ route('ruangan.edit', $ruangan) }}">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="Edit">
                                        </a>
                                        <a class="me-3" href="javascript:void(0);" data-id="{{ $ruangan->id }}"
                                            onclick="confirmDelete({{ $ruangan->id }})">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete">
                                        </a>
                                        <form id="delete-form-{{ $ruangan->id }}"
                                            action="{{ route('ruangan.destroy', $ruangan) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
    /* Tambahkan aturan CSS untuk border tabel */
    .table-bordered {
        border: 1px solid #dee2e6;
        /* Warna border tabel */
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        /* Border untuk sel tabel */
    }
</style>
<script>
    function confirmDelete(id) {
        if (confirm("Apakah anda yakin menghapusnya?")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
    // Pilih Semua Checkbox
    document.getElementById('select-all').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Hapus Data Terpilih
    function deleteSelected() {
        const selectedIds = Array.from(document.querySelectorAll('.delete-checkbox:checked')).map(el => el.value);
        if (selectedIds.length === 0) {
            alert('Pilih data yang akan dihapus!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
            fetch('{{ route('ruangan.bulkDelete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Filter Data
    document.getElementById('btn-filter').addEventListener('click', function () {
        const status = document.getElementById('filter-status').value;
        const category = document.getElementById('filter-category').value;
        const tempat = document.getElementById('filter-tempat').value;
        const query = new URLSearchParams({ status, category, tempat }).toString();
        window.location.href = `{{ route('ruangan.index') }}?${query}`;
    });
</script>

@endsection