@extends('layouts.admin.app') <!-- Pastikan ini adalah layout yang benar -->

@section('content') 
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Log Aktivitas</h4>
                <h6>Manage your activity logs</h6>
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
                            <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img"></a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table datanew table-bordered"> <!-- Tambahkan class 'table-bordered' -->
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Nama User</th> <!-- Menambahkan kolom untuk nama pengguna -->
                                <th>Aktivitas</th>
                                <th>Waktu Dibuat</th> <!-- Ubah label untuk kejelasan -->
                                <th>Waktu Diperbarui</th> <!-- Tambahkan kolom baru -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user_id }}</td> <!-- Pastikan ini sesuai dengan nama kolom di database -->
                                    <td>{{ $log->user->name ?? 'N/A' }}</td> <!-- Menampilkan nama pengguna -->
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->created_at? \Carbon\Carbon::parse ($log->created_at)->format('d-m-Y H:i:s') : '-' }}</td> <!-- Format waktu -->
                                    <td>{{ $log->updated_at ? \Carbon\Carbon::parse($log->updated_at)->format('d-m-Y H:i:s') : '-' }}</td>
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
        border: 1px solid #dee2e6; /* Warna border tabel */
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6; /* Border untuk sel tabel */
    }
</style>
@endsection
