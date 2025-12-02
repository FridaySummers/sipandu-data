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
                    <div class="form-group"><label for="email">Email</label><div class="input-group"><i class="fas fa-user"></i><input type="email" id="email" name="email" placeholder="Masukkan email" required></div></div>
                    <div class="form-group"><label for="password">Password</label><div class="input-group"><i class="fas fa-lock"></i><input type="password" id="password" name="password" placeholder="Masukkan password" required><button type="button" class="toggle-password" id="toggle-password"><i class="fas fa-eye"></i></button></div></div>
                    <div class="form-group"><label for="role">Role</label><div class="input-group"><i class="fas fa-user-tag"></i><select id="role" name="role" required><option value="" hidden selected>Pilih Role</option><option value="admin">Super Admin (Bappeda)</option><option value="dinas">Admin Dinas</option><option value="user">User</option></select></div></div>
                    <div class="form-options"><label class="checkbox-container"><input type="checkbox" id="remember" name="remember"><span class="checkmark"></span>Ingat saya</label><a href="#" class="forgot-link">Lupa password?</a></div>
                    <button type="submit" class="btn btn-primary btn-full"><i class="fas fa-sign-in-alt"></i>Masuk ke Dashboard</button>
                </form>
                <div class="login-divider"><span>Quick Login</span></div>
                <div class="ql-card demo-accounts">
                    <div class="ql-tabs">
                        <button type="button" class="ql-tab" id="tab-super">Super Admin</button>
                        <button type="button" class="ql-tab" id="tab-admin">Admin Dinas</button>
                        <button type="button" class="ql-tab" id="tab-user">User Dinas</button>
                    </div>
                    <div id="panel-super" class="ql-panel" style="display:block">
                        <div class="demo-info"><strong>Super Admin</strong><span>admin.bappeda@kolakautara.go.id / sipandu2025</span></div>
                        <button type="button" class="btn btn-outline btn-sm" id="demo-super-use">Gunakan</button>
                    </div>
                    <div id="panel-admin" class="ql-panel" style="display:none">
                        <div class="form-group" style="width:100%">
                            <div class="input-group">
                                <i class="fas fa-building"></i>
                                <select id="demo-admin-dinas-select" style="flex:1">
                                    <option value="" hidden selected>Pilih Dinas</option>
                                    @foreach(($dinasList ?? collect()) as $d)
                                        @if($d->kode_dinas !== 'bappeda')
                                            <option value="{{ $d->kode_dinas }}">{{ $d->nama_dinas }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline btn-sm" id="demo-admin-use">Gunakan</button>
                    </div>
                    <div id="panel-user" class="ql-panel" style="display:none">
                        <div class="form-group" style="width:100%">
                            <div class="input-group">
                                <i class="fas fa-users"></i>
                                <select id="demo-user-dinas-select" style="flex:1">
                                    <option value="" hidden selected>Pilih Dinas</option>
                                    @foreach(($dinasList ?? collect()) as $d)
                                        <option value="{{ $d->kode_dinas }}">{{ $d->nama_dinas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline btn-sm" id="demo-user-use">Gunakan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-footer"><p>&copy; 2025 Bappeda Kabupaten Kolaka Utara</p><p>Dikembangkan oleh H. Agus Salim, S.Pi</p></div>
    </div>
    <div class="loading-overlay" id="loading-overlay"><div class="spinner"></div><p>Memverifikasi kredensial...</p></div>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
