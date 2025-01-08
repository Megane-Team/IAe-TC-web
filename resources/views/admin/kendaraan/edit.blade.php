@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Kendaraan</h4>
                <h6>Update your vehicle</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tempat_id" value="{{ $kendaraan->tempat_id }}">

                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nama Kendaraan</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $kendaraan->name) }}"
                                    class="form-control" required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Plat Nomor</label>
                                <input type="text" name="plat" id="plat" value="{{ old('plat', $kendaraan->plat) }}"
                                    class="form-control" required>
                                @error('plat')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1" {{ old('status', $kendaraan->status) ? 'selected' : '' }}>Dipinjam
                                    </option>
                                    <option value="0" {{ !old('status', $kendaraan->status) ? 'selected' : '' }}>Tidak
                                        Dipinjam</option>
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
                                    <option value="bagus" {{ $kendaraan->condition == 'bagus' ? 'selected' : '' }}>Bagus
                                    </option>
                                    <option value="kurang_bagus" {{ $kendaraan->condition == 'kurang_bagus' ? 'selected' : '' }}>Kurang Bagus</option>
                                    <option value="rusak" {{ $kendaraan->condition == 'rusak' ? 'selected' : '' }}>Rusak
                                    </option>
                                </select>
                                @error('condition')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Kapasitas -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" name="capacity" id="capacity"
                                    value="{{ old('capacity', $kendaraan->capacity) }}" class="form-control" required>
                                @error('capacity')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category" id="category" class="select" required>
                                    <option value="mobil" {{ old('category', $kendaraan->category) == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="motor" {{ old('category', $kendaraan->category) == 'motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="truk" {{ old('category', $kendaraan->category) == 'truk' ? 'selected' : '' }}>Truk</option>
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Warna -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Warna</label>
                                <input type="text" name="color" id="color" value="{{ old('color', $kendaraan->color) }}"
                                    class="form-control" required>
                                @error('color')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="select" required>
                                    <option value="1" {{ old('status', $kendaraan->status) ? 'selected' : '' }}>Dipinjam
                                    </option>
                                    <option value="0" {{ !old('status', $kendaraan->status) ? 'selected' : '' }}>Tidak
                                        Dipinjam</option>
                                </select>
                                @error('status')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Garansi</label>
                                <div class="input-groupicon">
                                    <input type="text" name="warranty" id="warranty"
                                        value="{{ old('warranty', \Carbon\Carbon::parse($kendaraan->warranty)->format('d-m-Y')) }}"
                                        class="form-control datetimepicker" required>
                                    @error('warranty')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                    <div class="addonset">
                                        <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Tanggal Pajak</label>
                                <div class="input-groupicon">
                                <input type="text" name="tax" id="tax"
                                    value="{{ old('tax', \Carbon\Carbon::parse($kendaraan->tax)->format('d-m-Y')) }}"
                                    class="form-control datetimepicker" required>
                                @error('tax')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                <div class="addonset">
                                    <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Parkiran</label>
                                <select class="form-control form-small select" id="tempat_id" name="tempat_id"
                                    required>
                                    <option value="" disabled selected>Pilih Parkiran</option>
                                    @foreach($tempats as $tempat)
                                        <option value="{{ $tempat->id }}" {{ $kendaraan->tempat_id == $tempat->id ? 'selected' : '' }}>
                                            {{ $tempat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tempat_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Foto Kendaraan</label>
                            <div class="image-upload">
                                <input type="file" name="photo" id="photo" onchange="previewImage(event)">
                                <div class="image-uploads">
                                    <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            <div id="preview-container" class="mt-3">
                                @if ($kendaraan->photo)
                                    <div class="product-list" id="existing-photo">
                                        <ul class="row">
                                            <li>
                                                <div class="productviews">
                                                    <div class="productviewsimg">
                                                        <img src="{{ asset('storage/' . $kendaraan->photo) }}"
                                                            alt="Foto Kendaraan" id="preview-image">
                                                    </div>
                                                    <div class="productviewscontent">
                                                        <div class="productviewsname">
                                                            <h2 id="file-name">{{ basename($kendaraan->photo) }}</h2>
                                                            <h3>{{ number_format(filesize(storage_path('app/public/' . $kendaraan->photo)) / 1024, 2) }}
                                                                KB</h3>
                                                        </div>
                                                        <a href="javascript:void(0);" class="hideset"
                                                            onclick="removeExistingPhoto()">x</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @error('photo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-submit me-2">Update</button>
                            <a href="{{ route('kendaraan.index')}}" class="btn btn-cancel">Kembali</a>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

<script>
    function previewImage(event) {
        const previewContainer = document.getElementById('preview-container');
        const existingPhoto = document.getElementById('existing-photo');

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (existingPhoto) {
                    existingPhoto.querySelector('#preview-image').src = e.target.result;
                    existingPhoto.querySelector('#file-name').innerText = file.name;
                    existingPhoto.querySelector('h3').innerText = `${(file.size / 1024).toFixed(2)} KB`;
                } else {
                    const newPreview = `
                        <div class="product-list" id="existing-photo">
                            <ul class="row">
                                <li>
                                    <div class="productviews">
                                        <div class="productviewsimg">
                                            <img src="${e.target.result}" alt="Foto Kendaraan" id="preview-image">
                                        </div>
                                        <div class="productviewscontent">
                                            <div class="productviewsname">
                                                <h2 id="file-name">${file.name}</h2>
                                                <h3>${(file.size / 1024).toFixed(2)} KB</h3>
                                            </div>
                                            <a href="javascript:void(0);" class="hideset" onclick="removeExistingPhoto()">x</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>`;
                    previewContainer.innerHTML = newPreview;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function removeExistingPhoto() {
        const existingPhoto = document.getElementById('existing-photo');
        if (existingPhoto) {
            existingPhoto.remove();
        }

        const fileInput = document.getElementById('photo');
        fileInput.value = '';

        const previewContainer = document.getElementById('preview-container');
        let removeInput = document.querySelector('input[name="remove_photo"]');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_photo';
            removeInput.value = '1';
            previewContainer.appendChild(removeInput);
        }
    }
</script>