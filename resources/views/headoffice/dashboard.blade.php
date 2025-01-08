@extends('layouts.headoffice.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count">
                    <div class="dash-counts">
                        <h4>{{ $data['peminjamans'] }}</h4>
                        <h5>Peminjaman</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="calendar"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das1">
                    <div class="dash-counts">
                        <h4>{{ $data['detail_peminjamans'] }}</h4>
                        <h5>Detail Peminjaman</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="file-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
