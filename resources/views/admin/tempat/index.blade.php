@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Tempat</h4>
                <h6>Manage your places</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('tempat.create') }}" class="btn btn-added">
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Tempat
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
                                    <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete" class="me-1">
                                    Hapus Terpilih
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a href="{{ route('tempat.downloadPDF') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="PDF">
                                    <img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="PDF">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tempat.exportExcel') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Excel">
                                    <img src="{{ asset('assets/img/icons/excel.svg') }}" alt="Excel">
                                </a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                        src="{{ asset('assets/img/icons/printer.svg') }}" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-0" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select id="filter-category" class="select">
                                                <option disabled selected>Choose..</option>
                                                <option value="">Lainnya</option>
                                                <option value="gedung">Gedung</option>
                                                <option value="parkiran">Parkiran</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto" id="btn-filter">
                                                <img src="{{ asset('assets/img/icons/search-whites.svg') }}" alt="img">
                                            </a>
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
                                <th>
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tempats as $tempat)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="delete-checkbox" value="{{ $tempat->id }}">
                                            <span class="checkmarks"></span>
                                        </label>

                                    </td>
                                    <td>
                                        <img src="{{ $tempat->photo ? asset('storage/' . $tempat->photo) : asset('assets/img/product/default.jpg') }}"
                                            alt="Foto" style="width: 50px; height: auto;">
                                    </td>
                                    <td>{{ $tempat->name }}</td>
                                    <td>{{ ucfirst($tempat->category) }}</td>
                                    <td>
                                        <a class="me-3" href="{{ route('tempat.show', $tempat) }}">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="Show">
                                        </a>
                                        <a class="me-3" href="{{ route('tempat.edit', $tempat) }}">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="Edit">
                                        </a>
                                        <a class="me-3" href="javascript:void(0);" data-id="{{ $tempat->id }}"
                                            onclick="confirmDelete('{{ $tempat->id }}')">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete">
                                        </a>
                                        <form id="delete-form-{{ $tempat->id }}"
                                            action="{{ route('tempat.destroy', $tempat) }}" method="POST"
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
    // Filter Kategori
    document.getElementById('btn-filter').addEventListener('click', function () {
        const category = document.getElementById('filter-category').value;
        window.location.href = `{{ route('tempat.index') }}?category=${category}`;
    });

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
            fetch("{{ route('tempat.bulkDelete') }}", {
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

    // Konfirmasi Hapus Satu Data
    function confirmDelete(id) {
        if (confirm("Apakah anda yakin menghapusnya?")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

@endsection