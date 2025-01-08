@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Tempat</h4>
                <h6>Update your place</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('tempat.update', $tempat) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Nama Tempat</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $tempat->name) }}" required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="parkiran" {{ $tempat->category == 'parkiran' ? 'selected' : '' }}>
                                        Parkiran</option>
                                    <option value="gedung" {{ $tempat->category == 'gedung' ? 'selected' : '' }}>Gedung
                                    </option>
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Foto Tempat</label>
                                <div class="image-upload">
                                    <input type="file" name="photo" id="photo" onchange="previewImage(event)">
                                    <div class="image-uploads">
                                        <img src="{{asset('assets/img/icons/upload.svg')}}" alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                                <div id="preview-container" class="mt-3">
                                    @if ($tempat->photo)
                                        <div class="product-list" id="existing-photo">
                                            <ul class="row">
                                                <li>
                                                    <div class="productviews">
                                                        <div class="productviewsimg">
                                                            <img src="{{ asset('storage/' . $tempat->photo) }}"
                                                                alt="Foto Tempat" id="preview-image">
                                                        </div>
                                                        <div class="productviewscontent">
                                                            <div class="productviewsname">
                                                                <h2 id="file-name">{{ basename($tempat->photo) }}</h2>
                                                                <h3>{{ number_format(filesize(storage_path('app/public/' . $tempat->photo)) / 1024, 2) }}
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
                                <a href="{{ route('tempat.index') }}" class="btn btn-cancel">Kembali</a>
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

        if (existingPhoto) {
            // Update existing photo view
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    existingPhoto.querySelector('#preview-image').src = e.target.result;
                    existingPhoto.querySelector('#file-name').innerText = file.name;
                    existingPhoto.querySelector('h3').innerText = `${(file.size / 1024).toFixed(2)} KB`;
                };
                reader.readAsDataURL(file);
            }
        } else {
            // Add new preview if no existing photo
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const newPreview = `
                        <div class="product-list" id="existing-photo">
                            <ul class="row">
                                <li>
                                    <div class="productviews">
                                        <div class="productviewsimg">
                                            <img src="${e.target.result}" alt="Foto Tempat" id="preview-image">
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
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function removeExistingPhoto() {
        const existingPhoto = document.getElementById('existing-photo');
        if (existingPhoto) {
            existingPhoto.remove();
        }

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
