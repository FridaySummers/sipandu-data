<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data - SIPANDU DATA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tambah Data Baru</h1>
            <p>Isi form berikut untuk menambahkan data perencanaan baru</p>
        </div>

        <form action="{{ route('datamanagement.store') }}" method="POST" enctype="multipart/form-data" class="data-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="judul_data">Judul Data *</label>
                    <input type="text" id="judul_data" name="judul_data" 
                           placeholder="Masukkan judul data" required
                           value="{{ old('judul_data') }}">
                    @error('judul_data')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tahun_perencanaan">Tahun Perencanaan *</label>
                    <input type="text" id="tahun_perencanaan" name="tahun_perencanaan" 
                           placeholder="Contoh: 2025" maxlength="4" required
                           value="{{ old('tahun_perencanaan') }}">
                    @error('tahun_perencanaan')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" 
                          placeholder="Deskripsi singkat tentang data ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            @if($user->isSuperAdmin())
            <div class="form-group">
                <label for="dinas_id">Pilih Dinas *</label>
                <select id="dinas_id" name="dinas_id" required>
                    <option value="">-- Pilih Dinas --</option>
                    @foreach($dinasOptions as $dinas)
                        <option value="{{ $dinas->id }}" {{ old('dinas_id') == $dinas->id ? 'selected' : '' }}>
                            {{ $dinas->nama_dinas }}
                        </option>
                    @endforeach
                </select>
                @error('dinas_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <div class="form-group">
                <label for="file_path">Upload File *</label>
                <div class="file-upload">
                    <input type="file" id="file_path" name="file_path" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    <div class="file-info">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik untuk upload file</p>
                        <small>Format yang didukung: PDF, DOC, DOCX, XLS, XLSX (Maks. 2MB)</small>
                    </div>
                </div>
                @error('file_path')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                <a href="{{ route('datamanagement') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file_path');
            const fileInfo = document.querySelector('.file-info');
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    fileInfo.innerHTML = `
                        <i class="fas fa-file"></i>
                        <p>${fileName}</p>
                        <small>File siap diupload</small>
                    `;
                }
            });
        });
    </script>
</body>
</html>