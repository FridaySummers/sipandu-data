<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU DATA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=login-fixed-1">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}?v=login-fixed-1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .demo-tabs {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .demo-tabs-header {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .demo-tab {
            flex: 1;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #64748b;
            font-size: 14px;
        }
        
        .demo-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: white;
        }
        
        .demo-tab:hover {
            color: #3b82f6;
            background: #f1f5f9;
        }
        
        .demo-tab-content {
            display: none;
            padding: 20px;
        }
        
        .demo-tab-content.active {
            display: block;
        }
        
        .demo-accounts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .demo-accounts-grid::-webkit-scrollbar {
            width: 6px;
        }
        
        .demo-accounts-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .demo-accounts-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .demo-accounts-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .demo-account {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .demo-account:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .demo-info {
            text-align: center;
        }
        
        .demo-info strong {
            display: block;
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 6px;
            font-weight: 600;
        }
        
        .demo-info span {
            display: block;
            font-size: 13px;
            color: #64748b;
            line-height: 1.4;
        }
        
        .role-badge {
            display: inline-block;
            background: #3b82f6;
            color: #ffffff !important;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <a href="{{ url('/') }}" class="back-btn"><i class="fas fa-arrow-left"></i>Kembali ke Beranda</a>
        </div>
        <div class="login-card">
            <div class="login-logo">
                <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" class="logo-img" />
                <h1>SIPANDU DATA</h1>
                <p>Sistem Pemantauan dan Update Data Perencanaan</p>
            </div>
            <div class="login-content">
                <form class="login-form" id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="email" id="email" name="email" placeholder="Masukkan email" required>
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
                    
                    <!-- Role Selection -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <div class="input-group">
                            <i class="fas fa-user-tag"></i>
                            <select id="role" name="role" required>
                                <option value="" hidden selected>Pilih Role</option>
                                <option value="super_admin">Super Admin (Bappeda)</option>
                                <option value="admin_dinas">Admin Dinas</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Dinas Selection (Awalnya Hidden) -->
                    <div class="form-group" id="dinas-group" style="display: none;">
                        <label for="dinas_id">Pilih Dinas</label>
                        <div class="input-group">
                            <i class="fas fa-building"></i>
                            <select id="dinas_id" name="dinas_id" class="form-control">
                                <option value="">-- Pilih Dinas --</option>
                                @foreach($dinas as $dinasItem)
                                    <option value="{{ $dinasItem->id }}">{{ $dinasItem->nama_dinas }}</option>
                                @endforeach
                            </select>
                        </div>
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
                
                <div class="login-divider">
                    <span>Demo Credentials</span>
                </div>
                
                <!-- Clean Tabs Version tanpa Search -->
                <div class="demo-tabs">
                    <div class="demo-tabs-header">
                        <div class="demo-tab active" data-tab="super-admin">
                            Super Admin
                        </div>
                        <div class="demo-tab" data-tab="admin-dinas">
                            Admin Dinas
                        </div>
                        <div class="demo-tab" data-tab="user">
                            User
                        </div>
                    </div>
                    
                    <!-- Super Admin Tab -->
                    <div class="demo-tab-content active" id="super-admin-tab">
                        <div class="demo-accounts-grid">
                            <div class="demo-account" data-email="admin.bappeda@kolakautara.go.id" data-password="sipandu2025" data-role="super_admin">
                                <div class="demo-info">
                                    <strong>Super Admin Bappeda</strong>
                                    <span>admin.bappeda@kolakautara.go.id</span>
                                    <span>sipandu2025</span>
                                    <span class="role-badge">SUPER ADMIN</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admin Dinas Tab -->
                    <div class="demo-tab-content" id="admin-dinas-tab">
                        <div class="demo-accounts-grid">
                            <!-- Admin Dinas akan diisi oleh JavaScript -->
                        </div>
                    </div>
                    
                    <!-- User Tab -->
                    <div class="demo-tab-content" id="user-tab">
                        <div class="demo-accounts-grid">
                            <!-- User accounts akan diisi oleh JavaScript -->
                        </div>
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
    
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        // Data untuk semua akun demo
        const demoAccounts = {
            'super_admin': [
                {
                    email: 'admin.bappeda@kolakautara.go.id',
                    password: 'sipandu2025',
                    role: 'super_admin',
                    dinas: null,
                    name: 'Super Admin Bappeda'
                }
            ],
            'admin_dinas': [
                { email: 'admin.dpmptsp@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 1, name: 'DPMPTSP' },
                { email: 'admin.perdagangan@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 2, name: 'Perdagangan' },
                { email: 'admin.perindustrian@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 3, name: 'Perindustrian' },
                { email: 'admin.koperasi@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 4, name: 'Koperasi' },
                { email: 'admin.tanamanpangan@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 5, name: 'Tanaman Pangan' },
                { email: 'admin.perkebunan@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 6, name: 'Perkebunan' },
                { email: 'admin.perikanan@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 7, name: 'Perikanan' },
                { email: 'admin.ketapang@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 8, name: 'Ketahanan Pangan' },
                { email: 'admin.pariwisata@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 9, name: 'Pariwisata' },
                { email: 'admin.dlh@kolakautara.go.id', password: 'dinas123', role: 'admin_dinas', dinas: 10, name: 'DLH' }
            ],
            'user': [
                { email: 'user.dpmptsp@example.com', password: 'user123', role: 'user', dinas: 1, name: 'User DPMPTSP' },
                { email: 'user.perdagangan@example.com', password: 'user123', role: 'user', dinas: 2, name: 'User Perdagangan' },
                { email: 'user.perindustrian@example.com', password: 'user123', role: 'user', dinas: 3, name: 'User Perindustrian' },
                { email: 'user.koperasi@example.com', password: 'user123', role: 'user', dinas: 4, name: 'User Koperasi' },
                { email: 'user.tanamanpangan@example.com', password: 'user123', role: 'user', dinas: 5, name: 'User Tanaman Pangan' },
                { email: 'user.perkebunan@example.com', password: 'user123', role: 'user', dinas: 6, name: 'User Perkebunan' },
                { email: 'user.perikanan@example.com', password: 'user123', role: 'user', dinas: 7, name: 'User Perikanan' },
                { email: 'user.ketapang@example.com', password: 'user123', role: 'user', dinas: 8, name: 'User Ketahanan Pangan' },
                { email: 'user.pariwisata@example.com', password: 'user123', role: 'user', dinas: 9, name: 'User Pariwisata' },
                { email: 'user.dlh@example.com', password: 'user123', role: 'user', dinas: 10, name: 'User DLH' }
            ]
        };

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const dinasGroup = document.getElementById('dinas-group');
            const dinasSelect = document.getElementById('dinas_id');
            
            // Initialize demo accounts
            renderDemoAccounts('admin_dinas', 'admin-dinas-tab');
            renderDemoAccounts('user', 'user-tab');
            
            // Tab functionality
            document.querySelectorAll('.demo-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    document.querySelectorAll('.demo-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.demo-tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to current tab and content
                    this.classList.add('active');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
            
            // Handle role change
            roleSelect.addEventListener('change', function() {
                if (this.value === 'admin_dinas' || this.value === 'user') {
                    dinasGroup.style.display = 'block';
                    dinasSelect.setAttribute('required', 'required');
                } else {
                    dinasGroup.style.display = 'none';
                    dinasSelect.removeAttribute('required');
                    dinasSelect.value = '';
                }
            });
            
            // FIXED: Demo account buttons - handle Super Admin properly
            document.querySelectorAll('.demo-account').forEach(account => {
                account.addEventListener('click', function() {
                    const email = this.getAttribute('data-email');
                    const password = this.getAttribute('data-password');
                    const role = this.getAttribute('data-role');
                    const dinas = this.getAttribute('data-dinas');
                    
                    console.log('Demo account clicked:', { email, password, role, dinas });
                    
                    // Fill form
                    document.getElementById('email').value = email;
                    document.getElementById('password').value = password;
                    document.getElementById('role').value = role;
                    
                    // Trigger change event untuk role
                    const event = new Event('change');
                    document.getElementById('role').dispatchEvent(event);
                    
                    // Fill dinas if exists (for admin_dinas and user)
                    if (dinas) {
                        document.getElementById('dinas_id').value = dinas;
                    }
                    
                    // Auto-scroll to form
                    document.getElementById('login-form').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                    
                    // Show success message
                    setTimeout(() => {
                        alert(`Akun ${email} telah dipilih! Klik "Masuk ke Dashboard" untuk login.`);
                    }, 300);
                });
            });
            
            // Toggle password visibility
            const togglePassword = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                });
            }
            
            // Form submission loading
            const loginForm = document.getElementById('login-form');
            const loadingOverlay = document.getElementById('loading-overlay');
            
            if (loginForm && loadingOverlay) {
                loginForm.addEventListener('submit', function() {
                    loadingOverlay.style.display = 'flex';
                });
            }

            // Auto-hide loading overlay after 5 seconds
            setTimeout(function() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            }, 5000);
            
            function renderDemoAccounts(accountType, containerId) {
                const container = document.getElementById(containerId).querySelector('.demo-accounts-grid');
                const accounts = demoAccounts[accountType];
                
                container.innerHTML = '';
                
                accounts.forEach(account => {
                    const accountElement = document.createElement('div');
                    accountElement.className = 'demo-account';
                    accountElement.setAttribute('data-email', account.email);
                    accountElement.setAttribute('data-password', account.password);
                    accountElement.setAttribute('data-role', account.role);
                    accountElement.setAttribute('data-dinas', account.dinas);
                    accountElement.setAttribute('data-name', account.name);
                    
                    let badgeText = '';
                    
                    if (accountType === 'super_admin') {
                        badgeText = 'SUPER ADMIN';
                    } else if (accountType === 'admin_dinas') {
                        badgeText = 'ADMIN DINAS';
                    } else {
                        badgeText = 'USER';
                    }
                    
                    accountElement.innerHTML = `
                        <div class="demo-info">
                            <strong>${account.name}</strong>
                            <span>${account.email}</span>
                            <span>${account.password}</span>
                            <span class="role-badge">${badgeText}</span>
                        </div>
                    `;
                    
                    container.appendChild(accountElement);
                });
            }
        });
    </script>
</body>
</html>