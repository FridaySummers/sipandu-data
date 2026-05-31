<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU DATA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=login-role-clean-2">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}?v=login-role-clean-2">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <a href="{{ url('/') }}" class="back-btn"><i class="fas fa-arrow-left"></i>Kembali ke Beranda</a>
        </div>
            <div class="login-card">
            <div class="login-logo"><img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" class="logo-img" /><h1>SIPANDU DATA</h1><p>Sistem Pemantauan dan Update Data Perencanaan</p></div>
            <div class="login-content">
                <form class="login-form" id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group @error('email') error @enderror">
                            <i class="fas fa-user"></i>
                            <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group @error('password') error @enderror">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                            <button type="button" class="toggle-password" id="toggle-password"><i class="fas fa-eye"></i></button>
                        </div>
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <div class="input-group @error('role') error @enderror">
                            <i class="fas fa-user-tag"></i>
                            <select id="role" name="role" required>
                                <option value="" hidden {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                                <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Super Admin (Bappeda)</option>
                                <option value="dinas" {{ old('role')==='dinas' ? 'selected' : '' }}>Admin Dinas</option>
                                <option value="user" {{ old('role')==='user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        @error('role')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" id="dinas-group" style="display: none;">
                        <label for="dinas">Pilih Dinas</label>
                        <div class="input-group @error('dinas') error @enderror">
                            <i class="fas fa-building"></i>
                            <select id="dinas" name="dinas">
                                <option value="" hidden {{ old('dinas') ? '' : 'selected' }}>Pilih Dinas</option>
                                <option value="dpmptsp" {{ old('dinas')==='dpmptsp' ? 'selected' : '' }}>DPMPTSP</option>
                                <option value="dinas-perdagangan" {{ old('dinas')==='dinas-perdagangan' ? 'selected' : '' }}>Dinas Perdagangan</option>
                                <option value="dinas-perindustrian" {{ old('dinas')==='dinas-perindustrian' ? 'selected' : '' }}>Dinas Perindustrian</option>
                                <option value="dinas-koperasi-dan-ukm" {{ old('dinas')==='dinas-koperasi-dan-ukm' ? 'selected' : '' }}>Dinas Koperasi dan UKM</option>
                                <option value="dinas-pertanian-tanaman-pangan" {{ old('dinas')==='dinas-pertanian-tanaman-pangan' ? 'selected' : '' }}>Dinas Pertanian Tanaman Pangan</option>
                                <option value="dinas-perkebunan-dan-peternakan" {{ old('dinas')==='dinas-perkebunan-dan-peternakan' ? 'selected' : '' }}>Dinas Perkebunan dan Peternakan</option>
                                <option value="dinas-perikanan" {{ old('dinas')==='dinas-perikanan' ? 'selected' : '' }}>Dinas Perikanan</option>
                                <option value="dinas-ketahanan-pangan" {{ old('dinas')==='dinas-ketahanan-pangan' ? 'selected' : '' }}>Dinas Ketahanan Pangan</option>
                                <option value="dinas-pariwisata" {{ old('dinas')==='dinas-pariwisata' ? 'selected' : '' }}>Dinas Pariwisata</option>
                                <option value="dinas-lingkungan-hidup" {{ old('dinas')==='dinas-lingkungan-hidup' ? 'selected' : '' }}>Dinas Lingkungan Hidup</option>
                                <option value="badan-pendapatan-daerah" {{ old('dinas')==='badan-pendapatan-daerah' ? 'selected' : '' }}>Badan Pendapatan Daerah</option>
                            </select>
                        </div>
                        @error('dinas')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container"><input type="checkbox" id="remember" name="remember"><span class="checkmark"></span>Ingat saya</label>
                        <a href="#" class="forgot-link">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full"><i class="fas fa-sign-in-alt"></i>Masuk ke Dashboard</button>
                </form>
                
            </div>
        </div>
        <div class="login-footer"><p>&copy; 2025 Bappeda Kabupaten Kolaka Utara</p><p>Dikembangkan oleh H. Agus Salim, S.Pi</p></div>
    </div>
    <div class="loading-overlay" id="loading-overlay"><div class="spinner"></div><p>Memverifikasi kredensial...</p></div>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
