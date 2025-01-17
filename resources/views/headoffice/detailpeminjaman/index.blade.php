@extends('layouts.headoffice.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Daftar Peminjaman</h4>
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
                                <a href="{{ route('detailpeminjaman.downloadPDF') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="PDF">
                                    <img src="{{ asset('assets/img/icons/pdf.svg') }}" alt="PDF">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('detailpeminjaman.exportExcel') }}" data-bs-toggle="tooltip"
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
                                            <label>Pilih Status</label>
                                            <select id="filter-status" class="select">
                                                <option value="">Semua Status</option>
                                                <option value="draft">Draft</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="returned">Returned</option>
                                                <option value="canceled">Canceled</option>
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
                                <th>User</th>
                                <th>Status</th>
                                <th>Tanggal Dipinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Alasan Dibatalkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailpeminjamans as $detail)
                                <tr>
                                    <td>{{ $detail->user->name }}</td>
                                    <td>{{ ucfirst($detail->status) }}</td>
                                    <td>{{ $detail->borrowedDate ? \Carbon\Carbon::parse($detail->borrowedDate)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $detail->returnDate ? \Carbon\Carbon::parse($detail->returnDate)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $detail->canceledReason ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-rounded btn-outline-info"
                                            onclick="window.location.href='{{ route('detailpeminjaman.show', $detail->id) }}'">Lihat</button>

                                        @if($detail->status === 'pending')
                                            <form action="{{ route('detailpeminjaman.approve', $detail->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-rounded btn-outline-success">Approve</button>
                                            </form>

                                            <a href="{{ route('detailpeminjaman.reject', $detail->id) }}"
                                                class="btn btn-rounded btn-outline-danger">Reject</a>
                                        @endif
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
        const status = document.getElementById('filter-status').value;
        const query = new URLSearchParams({ status }).toString();
        window.location.href = `{{ route('detailpeminjaman.index') }}?${query}`;
    });
</script>

@endsection