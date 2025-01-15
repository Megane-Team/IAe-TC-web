@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Pengguna</h4>
                <h6>Manage your users</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('user.create') }}" class="btn btn-added">
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Pengguna
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
                                <a href="{{ route('user.downloadPDF') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="PDF">
                                    <img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="PDF">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exportExcel') }}" data-bs-toggle="tooltip"
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
                                            <select id="filter-role" class="select">
                                                <option disabled selected>Pilih Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="headOffice">Head Office</option>
                                                <option value="user">User</option>
                                                <option value="">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select id="filter-unit" class="form-control form-small select">
                                                <option disabled selected>Pilih Unit</option>
                                                <option value="">Semua Unit</option>
                                                @foreach ($units as $unit) 
                                                    <option value="{{ $unit->unit }}">{{ $unit->unit }}</option>
                                                @endforeach
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
                                <th>Email</th>
                                <th>NIK</th>
                                <th>Role</th>
                                <th>Unit</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="delete-checkbox" value="{{ $user->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/img/product/default.jpg') }}"
                                            alt="Foto" style="width: 50px; height: auto;">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->nik }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->unit }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->phoneNumber }}</td>
                                    <td>
                                        <a class="me-3" href="{{ route('user.show', $user) }}">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="Show">
                                        </a>
                                        <a class="me-3" href="{{ route('user.edit', $user) }}">
                                            <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="Edit">
                                        </a>
                                        <a class="me-3" href="javascript:void(0);" data-id="{{ $user->id }}"
                                            onclick="confirmDelete('{{ $user->id }}')">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete">
                                        </a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user) }}"
                                            method="POST" style="display: none;">
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

    document.getElementById('btn-filter').addEventListener('click', function () {
        const role = document.getElementById('filter-role').value;
        const unit = document.getElementById('filter-unit').value;
        window.location.href = `{{ route('user.index') }}?role=${role}&unit=${unit}`;
    });


    document.getElementById('select-all').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });


    function deleteSelected() {
        const selectedIds = Array.from(document.querySelectorAll('.delete-checkbox:checked')).map(el => el.value);
        if (selectedIds.length === 0) {
            alert('Pilih data yang akan dihapus!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
            fetch("{{ route('user.bulkDelete') }}", {
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


    function confirmDelete(id) {
        if (confirm("Apakah anda yakin menghapusnya?")) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection