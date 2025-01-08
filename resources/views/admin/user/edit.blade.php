@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Management</h4>
                <h6>Edit User</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <input type="password" name="password_confirmation" class="pass-inputs"
                                        placeholder="Re-enter new password">
                                    <span class="fas toggle-passworda fa-eye-slash"></span>
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
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="select form-control" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="headOffice" {{ $user->role == 'headOffice' ? 'selected' : '' }}>Head
                                        Office</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group uploadedimage">
                                <label>Profile Picture</label>
                                <div class="image-upload image-upload-new text-center">
                                    <div class="image-uploads">
                                        <img id="uploaded-image"
                                            src="{{ $user->photo ? \Illuminate\Support\Facades\Storage::url($user->photo) : asset('assets/img/icons/upload.svg') }}"
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
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .image-uploads {
        width: 100%;
        /* Lebar penuh kontainer */
        height: 300px;
        /* Tinggi maksimal */
        border: 1px solid #ddd;
        /* Border untuk kontainer */
        overflow: hidden;
        /* Gambar tidak keluar dari kontainer */
        position: relative;
        /* Untuk positioning gambar */
        padding: 5px;
        /* Jarak di dalam kontainer */
        background-color: #f8f9fa;
        /* Latar belakang untuk estetika */
    }

    .image-uploads img {
        width: 100%;
        /* Mengisi lebar kontainer */
        height: 100%;
        /* Mengisi tinggi kontainer */
        object-fit: contain;
        /* Menyesuaikan gambar tanpa memotong atau mendistorsi */
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
        /* Jarak antar tombol */
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

        uploadedImage.src = "{{ asset('assets/img/icons/upload.svg') }}"; // Set ke gambar default
        photoInput.value = ''; // Kosongkan input file
        removePhotoInput.value = '1'; // Tandai gambar untuk dihapus
    }

</script>
@endsection