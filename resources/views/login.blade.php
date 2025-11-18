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
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan email" 
                            value="{{ old('email') }}"
                            required
                        >
                    </div>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password" 
                            required
                        >
                        <button type="button" class="toggle-password" id="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember" name="remember">
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
        </div>
    </div>
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
