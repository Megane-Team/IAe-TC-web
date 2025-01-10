@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Profile Management</h4>
                <h6>Edit Profile</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" name="nik" class="form-control" value="{{ $user->nik }}">
                                @error('nik')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password" class="pass-input"
                                        placeholder="Enter new password">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password_confirmation" class="pass-input"
                                        placeholder="Re-enter new password">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Column -->
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="text" name="phoneNumber" class="form-control"
                                    value="{{ $user->phoneNumber }}">
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <input type="text" name="unit" class="form-control" value="{{ $user->unit }}">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group uploadedimage">
                                <label>Profile Picture</label>
                                <div class="image-upload image-upload-new text-center">
                                    <div class="image-uploads">
                                        <img id="uploaded-image"
                                            src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/img/icons/upload.svg') }}"
                                            alt="Profile Picture">
                                    </div>
                                    <input type="file" name="photo" id="photo" class="d-none"
                                        onchange="previewImage(event)">
                                    <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary btn-sm me-2"
                                    onclick="triggerUpload()">Upload</button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="removeExistingPhoto()">Hapus</button>
                            </div>
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-lg-12 text-end">
                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .alert {
        margin-bottom: 20px;
        font-size: 14px;
        border-radius: 5px;
    }

    .image-uploads {
        width: 100%;
        height: 300px;
        border: 1px solid #ddd;
        overflow: hidden;
        position: relative;
        padding: 5px;
        background-color: #f8f9fa;
    }

    .image-uploads img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .form-group label {
        font-weight: bold;
    }

    .uploadedimage {
        position: relative;
        display: block;
    }

    .text-center.mt-3 {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
</style>

<script>
    function triggerUpload() {
        document.getElementById('photo').click();
    }

    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        const uploadedImage = document.getElementById('uploaded-image');
        if (file) {
            reader.onload = function (e) {
                uploadedImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function removeExistingPhoto() {
        const uploadedImage = document.getElementById('uploaded-image');
        const photoInput = document.getElementById('photo');
        const removePhotoInput = document.getElementById('remove_photo');

        uploadedImage.src = "{{ asset('assets/img/icons/upload.svg') }}";
        photoInput.value = '';
        removePhotoInput.value = '1';
    }
</script>
@endsection