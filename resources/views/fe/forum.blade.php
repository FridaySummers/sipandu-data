@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="forum-page">
        <div class="page-header"><h1>Forum Diskusi</h1><p>Koordinasi dan diskusi lintas OPD</p></div>
        <div class="forum-threads">
            <div class="threads-header">
                <h3>Topik Terbaru</h3>
                <div class="threads-actions">
                    <div class="search-box compact"><i class="fas fa-search"></i><input id="forum-search" type="text" placeholder="Cari diskusi, topik, atau pengguna..."></div>
                    <select id="forum-category"><option value="">Semua Kategori</option><option value="Metodologi">Metodologi</option><option value="Best Practice">Best Practice</option><option value="Pengumuman">Pengumuman</option><option value="Problem Solving">Problem Solving</option></select>
                    <button class="btn btn-primary btn-sm" id="forum-new"><i class="fas fa-plus"></i> Buat Diskusi Baru</button>
                </div>
            </div>
            <div class="thread-list" id="thread-list"></div>
        </div>
        <div class="modal-overlay" id="forum-modal" style="display:none;">
            <div class="modal">
                <div class="modal-header"><h3>Buat Topik Baru</h3><button class="btn btn-outline btn-sm" id="forum-close">✕</button></div>
                <div class="modal-body">
                    <div class="form-row"><label>Judul</label><input id="forum-title" type="text" placeholder="Judul topik"></div>
                    <div class="form-row"><label>OPD</label><select id="forum-opd"></select></div>
                    <div class="form-row"><label>Kategori</label><select id="forum-cat"><option value="Metodologi">Metodologi</option><option value="Best Practice">Best Practice</option><option value="Pengumuman">Pengumuman</option><option value="Problem Solving">Problem Solving</option></select></div>
                    <div class="form-row"><label>Konten</label><textarea id="forum-content" rows="4" placeholder="Isi diskusi"></textarea></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" id="forum-cancel">Batal</button><button class="btn btn-primary" id="forum-save">Simpan</button></div>
            </div>
        </div>
        <div class="modal-overlay" id="forum-detail-modal" style="display:none;">
            <div class="modal" style="max-width: 860px;">
                <div class="modal-header"><h3>Detail Diskusi</h3><button class="btn btn-outline btn-sm" id="fdm-close">✕</button></div>
                <div class="modal-body" id="fdm-body">Pilih topik untuk melihat detail</div>
                <div class="modal-footer"><div style="flex:1"></div><button class="btn btn-secondary" id="fdm-reply-cancel">Batal</button><button class="btn btn-primary" id="fdm-reply-send">Kirim</button></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', ()=>{ UIComponents.renderNotifications(); });
</script>
@endpush
