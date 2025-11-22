@extends('layouts.app')

@section('title', 'Login')
@section('body-class', 'login-page')

@section('content')
<div class="login-container">
    <div class="login-header">
        <a href="{{ route('home') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
        </a>
    </div>

    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-chart-line"></i>
            <h1>SIPANDU DATA</h1>
            <p>Sistem Pemantauan dan Update Data Perencanaan</p>
        </div>

        <div class="login-content">
            <form class="login-form" id="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="toggle-password" id="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <div class="input-group">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Super Admin (Bappeda)</option>
                            <option value="dinas">Admin Dinas</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember">
                        <span class="checkmark"></span>
                        Ingat saya
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="login-divider">
                <span>Demo Credentials</span>
            </div>

            <div class="demo-accounts">
                <div class="demo-account" data-username="admin.bappeda" data-password="sipandu2025" data-role="admin">
                    <div class="demo-info">
                        <strong>Super Admin</strong>
                        <span>admin.bappeda / sipandu2025</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Gunakan</button>
                </div>
                <div class="demo-account" data-username="admin.perdagangan" data-password="dinas123" data-role="dinas">
                    <div class="demo-info">
                        <strong>Admin Dinas</strong>
                        <span>admin.perdagangan / dinas123</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Gunakan</button>
                </div>
                <div class="demo-account" data-username="user.demo" data-password="user123" data-role="user">
                    <div class="demo-info">
                        <strong>User Demo</strong>
                        <span>user.demo / user123</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Gunakan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="login-footer">
        <p>&copy; 2025 Bappeda Kabupaten Kolaka Utara</p>
        <p>Dikembangkan oleh H. Agus Salim, S.Pi</p>
    </div>
</div>

<div class="loading-overlay" id="loading-overlay">
    <div class="spinner"></div>
    <p>Memverifikasi kredensial...</p>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggle-password')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush
