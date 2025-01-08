@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Ruangan</h4>
                <h6>Update your room</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ruangan.update', $ruangan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Kode Ruangan</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code', $ruangan->code) }}" required>
                                @error('code')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="select" id="status" name="status" required>
                                    <option value="1" {{ $ruangan->status ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="0" {{ !$ruangan->status ? 'selected' : '' }}>Tidak Dipinjam</option>
                                </select>
                                @error('status')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" class="form-control" id="capacity" name="capacity"
                                    value="{{ old('capacity', $ruangan->capacity) }}" required>
                                @error('capacity')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="select" id="category" name="category" required>
                                    <option value="kelas" {{ $ruangan->category == 'kelas' ? 'selected' : '' }}>Kelas</option>
                                    <option value="lab" {{ $ruangan->category == 'lab' ? 'selected' : '' }}>Lab</option>
                                    <option value="gudang" {{ $ruangan->category == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Tempat</label>
                                <select class="form-control form-small select" id="tempat_id" name="tempat_id" required>
                                    <option value="">Pilih Gedung</option>
                                    @foreach($tempats as $tempat)
                                        <option value="{{ $tempat->id }}" {{ $ruangan->tempat_id == $tempat->id ? 'selected' : '' }}>
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
                                <label>Foto Ruangan</label>
                                <div class="image-upload">
                                    <input type="file" name="photo" id="photo" onchange="previewImage(event)">
                                    <div class="image-uploads">
                                        <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                                <div id="preview-container" class="mt-3">
                                    @if ($ruangan->photo)
                                        <div class="product-list" id="existing-photo">
                                            <ul class="row">
                                                <li>
                                                    <div class="productviews">
                                                        <div class="productviewsimg">
                                                            <img src="{{ asset('storage/' . $ruangan->photo) }}"
                                                                alt="Foto Ruangan" id="preview-image">
                                                        </div>
                                                        <div class="productviewscontent">
                                                            <div class="productviewsname">
                                                                <h2 id="file-name">{{ basename($ruangan->photo) }}</h2>
                                                                <h3>{{ number_format(filesize(storage_path('app/public/' . $ruangan->photo)) / 1024, 2) }}
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
                                            <img src="${e.target.result}" alt="Foto Ruangan" id="preview-image">
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

        // Clear the file input
        const fileInput = document.getElementById('photo');
        fileInput.value = '';

        // Add hidden input to indicate removal of the photo
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