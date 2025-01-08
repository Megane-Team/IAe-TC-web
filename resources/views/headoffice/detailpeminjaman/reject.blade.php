@extends('layouts.headoffice.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Form Alasan Peminjaman</h4>
                <h6>Alasan ditolak Peminjaman</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('detailpeminjaman.reject.update', $detailpeminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="canceledReason">Alasan Ditolak</label>
                        <textarea name="canceledReason" id="canceledReason"
                            class="form-control">{{ $detailpeminjaman->canceledReason }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('detailpeminjaman.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection