@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tambah Ruangan</h4>
                <h6>Buat ruangan baru</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kode Ruangan</label>
                                <input type="text" class="form-control" id="code" name="code" required>
                                @error('code')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="select" id="status" name="status" required>
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
                                <label>Kapasitas</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" required>
                                @error('capacity')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="select" id="category" name="category" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="kelas">Kelas</option>
                                    <option value="lab">Lab</option>
                                    <option value="gudang">Gudang</option>
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Pilih Gedung</label>
                                    <select class="form-control form-small select" id="tempat_id" name="tempat_id"
                                        required>
                                        <option value="" disabled selected>Pilih Gedung</option>
                                        @foreach($tempats as $tempat)
                                            <option value="{{ $tempat->id }}">{{ $tempat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tempat_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Foto Ruangan</label>
                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                    <label>Upload (Single File) <a href="javascript:void(0)"
                                            class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                    <label class="custom-file-container__custom-file">
                                        <input type="file" class="custom-file-container__custom-file__custom-file-input"
                                            id="photo" name="photo" accept="image/*">
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
                                <a href="{{ route('ruangan.index') }}" class="btn btn-cancel">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection