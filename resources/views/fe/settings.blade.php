@extends('layouts.app')
@section('title', 'Pengaturan')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="settings-page">
        <div class="page-header"><h1>Pengaturan</h1><p>Preferensi akun dan aplikasi</p></div>
        <div class="settings-grid">
            <div class="card"><div class="card-header"><h3>Preferensi Tampilan</h3></div><div class="card-body"><div class="form-row"><label>Mode Tema</label><select id="set-theme"><option value="light">Terang</option><option value="dark">Gelap</option></select></div><div class="form-row"><label>Notifikasi</label><select id="set-notif"><option value="all">Semua</option><option value="important">Penting saja</option><option value="none">Nonaktif</option></select></div><button class="btn btn-primary" id="set-save"><i class="fas fa-save"></i>Simpan</button></div></div>
            <div class="card"><div class="card-header"><h3>Profil</h3></div><div class="card-body" id="profile-info"></div></div>
        </div>

        
    </div>
@endsection

@push('scripts')
 
@endpush
