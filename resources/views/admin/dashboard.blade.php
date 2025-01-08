@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count">
                    <div class="dash-counts">
                        <h4>{{ $data['users'] }}</h4>
                        <h5>Users</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="user"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das1">
                    <div class="dash-counts">
                        <h4>{{ $data['tempats'] }}</h4>
                        <h5>Tempat</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="map-pin"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das2">
                    <div class="dash-counts">
                        <h4>{{ $data['kendaraans'] }}</h4>
                        <h5>Kendaraan</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="truck"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3">
                    <div class="dash-counts">
                        <h4>{{ $data['ruangans'] }}</h4>
                        <h5>Ruangan</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="box"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das4">
                    <div class="dash-counts">
                        <h4>{{ $data['barangs'] }}</h4>
                        <h5>Barang</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="archive"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
.dash-count.das4 {
    background-color: #f4d03f; /* Warna kuning sebagai contoh */
    color: #fff; /* Warna teks */
    border: 1px solid #e0ac20; /* Tambahan border jika diperlukan */
}

.dash-count.das4 .dash-imgs i {
    color: #fff; /* Warna ikon */
}
</style>
@endsection