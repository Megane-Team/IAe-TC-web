@extends('layouts.admin.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Import Data Tempat</h4>
                <h6 class="text-muted">Upload file Excel untuk menambahkan data tempat</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h4>File harus dalam format Excel (.xlsx atau .xls)</h4>
                    <p class="text-muted">Pastikan file yang diunggah memiliki kolom heading berikut:</p>
                    <ul>
                        <li><strong>Nama Tempat</strong> - Isi kolom <code>name</code> dengan nama tempat.</li>
                        <li><strong>Kategori</strong> - Isi kolom <code>category</code> dengan pilihan <code>gedung</code> atau <code>parkiran</code>.</li>
                        <li><strong>Foto</strong> - Isi kolom <code>photo</code> untuk foto tempat (Letakkan di kolom <strong>C</strong>).</li>
                    </ul>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="{{ route('tempat.downloadSample') }}" class="btn btn-primary btn-block">Download Contoh
                            File</a>
                    </div>
                </div>
                <form action="{{ route('tempat.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="file">Upload File Excel</label>
                        <div class="image-upload">
                            <label for="file" class="image-uploads">
                                <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="Upload Icon">
                                <h4>Seret dan jatuhkan file untuk diunggah atau klik di sini</h4>
                            </label>
                            <input type="file" id="file" name="file" accept=".xls,.xlsx" required
                                onchange="updateFileName()">
                        </div>
                        <span id="file-name" class="text-muted mt-2" style="display: block;">Tidak ada file yang
                            dipilih</span>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="productdetails">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Nama Tempat</span>
                                        <span class="badge bg-success text-white">Wajib</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Kategori</span>
                                        <span class="badge bg-success text-white">Wajib</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Foto</span>
                                        <span class="badge bg-info text-white">Opsional</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-3 mt-4">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{{ route('tempat.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function updateFileName() {
        const fileInput = document.getElementById('file');
        const fileNameDisplay = document.getElementById('file-name');
        
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = `File yang dipilih: ${fileInput.files[0].name}`;
        } else {
            fileNameDisplay.textContent = 'Tidak ada file yang dipilih';
        }
    }
</script>

@endsection
