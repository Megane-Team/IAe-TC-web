@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Kendaraan</h4>
                <h6>Buat kendaraan baru</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nama Kendaraan</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Plat Nomor</label>
                                <input type="text" name="plat" id="plat" class="form-control" required>
                                @error('plat')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category" id="category" class="select" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="mobil">Mobil</option>
                                    <option value="motor">Motor</option>
                                    <option value="truk">Truk</option>
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Warna</label>
                                <input type="text" name="color" id="color" class="form-control" required>
                                @error('color')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="select" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="1">Dipinjam</option>
                                    <option value="0">Tidak Dipinjam</option>
                                </select>
                                @error('status')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kondisi</label>
                                <select name="condition" id="condition" class="select" required>
                                    <option value="" disabled selected>Pilih Kondisi</option>
                                    <option value="bagus">Bagus</option>
                                    <option value="kurang_bagus">Kurang Bagus</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                                @error('condition')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" name="capacity" id="capacity" class="form-control" required>
                                @error('capacity')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Parkiran</label>
                                <select name="tempat_id" id="tempat_id" class="form-control form-small select" required>
                                    <option value="" disabled selected>Pilih Parkiran</option>
                                    @foreach($tempats as $tempat)
                                        <option value="{{ $tempat->id }}">{{ $tempat->name }}</option>
                                    @endforeach
                                </select>
                                @error('tempat_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Garansi</label>
                                <div class="input-groupicon">
                                    <input type="text" name="warranty" id="warranty" placeholder="DD-MM -YYYY"
                                        class="form-control datetimepicker" required>
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img">
                                    </div>
                                </div>
                                @error('warranty')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Pajak</label>
                                <div class="input-groupicon">
                                    <input type="text" name="tax" id="tax" placeholder="DD-MM-YYYY"
                                        class="form-control datetimepicker" required>
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img">
                                    </div>
                                </div>
                                @error('tax_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Foto Kendaraan</label>
                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                    <label>Upload (Single File) <a href="javascript:void(0)"
                                            class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                    <label class="custom-file-container__custom-file">
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input"
                                            id="photo" name="photo" accept="image/*"> <!-- Hapus 'required' -->
                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                                    @error('photo')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-submit me-2">Simpan</button>
                                <a href="{{ route('kendaraan.index') }}" class="btn btn-cancel">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection