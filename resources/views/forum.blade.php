@extends('layouts.app')

@section('title', 'Forum Diskusi')
@section('body-class', 'dashboard-page force-light')

@section('content')
<div class="page active" id="forum-page">
    <div class="forum-hero">
        <div class="forum-hero-text">
            <h1>Forum Diskusi</h1>
            <p>Kolaborasi dan diskusi antar instansi pemerintah</p>
        </div>
        <button class="forum-hero-btn" id="forum-new-btn" type="button">
            <i class="fas fa-plus"></i> Topik Baru
        </button>
    </div>

    <div class="forum-kpi">
        <div class="forum-kpi-card">
            <div class="forum-kpi-icon blue"><i class="fas fa-comments"></i></div>
            <div>
                <div class="forum-kpi-value" id="forum-kpi-topics">0</div>
                <div class="forum-kpi-label">Total Topik</div>
            </div>
        </div>
        <div class="forum-kpi-card">
            <div class="forum-kpi-icon green"><i class="fas fa-reply"></i></div>
            <div>
                <div class="forum-kpi-value" id="forum-kpi-replies">0</div>
                <div class="forum-kpi-label">Total Komentar</div>
            </div>
        </div>
        <div class="forum-kpi-card">
            <div class="forum-kpi-icon purple"><i class="fas fa-eye"></i></div>
            <div>
                <div class="forum-kpi-value" id="forum-kpi-views">0</div>
                <div class="forum-kpi-label">Total Views</div>
            </div>
        </div>
    </div>

    <div class="forum-main-grid">
        <div class="forum-feed">
            <div class="forum-toolbar">
                <div class="forum-search-wrap">
                    <i class="fas fa-search"></i>
                    <input id="forum-search" type="text" placeholder="Cari topik diskusi...">
                </div>
                <select class="forum-filter-select" id="forum-category">
                    <option value="">Semua Kategori</option>
                    <option value="Umum">Umum</option>
                    <option value="Panduan & Tutorial">Panduan & Tutorial</option>
                    <option value="Problem Solving">Problem Solving</option>
                    <option value="Pengumuman">Pengumuman</option>
                    <option value="Koordinasi">Koordinasi</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Saran & Masukan">Saran & Masukan</option>
                </select>
                <select class="forum-filter-select" id="forum-org">
                    <option value="">Semua Dinas</option>
                </select>
            </div>

            <div id="thread-list" class="thread-list">
                <div class="thread-empty"><i class="fas fa-spinner fa-spin"></i>Memuat diskusi...</div>
            </div>
        </div>

        <aside class="forum-sidebar">
            <div class="forum-sidebar-card">
                <div class="forum-sidebar-head"><i class="fas fa-tags"></i> Kategori</div>
                <ul class="category-list" id="forum-cat-list">
                    <li><a href="#" data-cat="" class="active"><i class="fas fa-border-all"></i> Semua</a></li>
                    <li><a href="#" data-cat="Umum"><i class="fas fa-comment-dots"></i> Umum</a></li>
                    <li><a href="#" data-cat="Panduan & Tutorial"><i class="fas fa-book-open"></i> Panduan & Tutorial</a></li>
                    <li><a href="#" data-cat="Problem Solving"><i class="fas fa-lightbulb"></i> Problem Solving</a></li>
                    <li><a href="#" data-cat="Pengumuman"><i class="fas fa-bullhorn"></i> Pengumuman</a></li>
                    <li><a href="#" data-cat="Koordinasi"><i class="fas fa-handshake"></i> Koordinasi</a></li>
                    <li><a href="#" data-cat="Technical Support"><i class="fas fa-wrench"></i> Technical Support</a></li>
                    <li><a href="#" data-cat="Saran & Masukan"><i class="fas fa-star"></i> Saran & Masukan</a></li>
                </ul>
            </div>
        </aside>
    </div>

    <div class="modal-overlay" id="forum-modal" style="display:none;">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-pen-to-square" style="color:#2563eb;margin-right:8px"></i>Buat Topik Baru</h3>
                <button class="btn btn-outline btn-sm" id="forum-modal-close" type="button">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="forum-title-row">
                    <label>Judul Topik</label>
                    <div>
                        <input id="forum-title" type="text" maxlength="80" placeholder="Tulis judul yang jelas dan informatif...">
                        <div class="field-meta"><span id="forum-title-count">0/80</span><span class="field-error" id="forum-title-err"></span></div>
                    </div>
                </div>
                <div class="form-row" id="forum-cat-row">
                    <label>Kategori</label>
                    <div>
                        <select id="forum-cat-select">
                            <option value="">Pilih kategori</option>
                            <option value="Umum">Umum</option>
                            <option value="Panduan & Tutorial">Panduan & Tutorial</option>
                            <option value="Problem Solving">Problem Solving</option>
                            <option value="Pengumuman">Pengumuman</option>
                            <option value="Koordinasi">Koordinasi</option>
                            <option value="Technical Support">Technical Support</option>
                            <option value="Saran & Masukan">Saran & Masukan</option>
                        </select>
                        <span class="field-error" id="forum-cat-err"></span>
                    </div>
                </div>
                <div class="form-row" id="forum-opd-row">
                    <label>Dinas</label>
                    <div>
                        <select id="forum-opd-select">
                            <option value="">Pilih dinas</option>
                        </select>
                        <span class="field-error" id="forum-opd-err"></span>
                    </div>
                </div>
                <div class="form-row" id="forum-content-row">
                    <label>Isi Diskusi</label>
                    <div>
                        <textarea id="forum-content" rows="4" maxlength="500" placeholder="Jelaskan topik diskusi Anda secara detail..."></textarea>
                        <div class="field-meta"><span id="forum-content-count">0/500</span><span class="field-error" id="forum-content-err"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="forum-modal-cancel" type="button">Batal</button>
                <button class="btn btn-primary" id="forum-modal-save" type="button" disabled><i class="fas fa-paper-plane"></i> Publikasikan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/forum.css') }}?v=2">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var currentUserId = parseInt(document.body.dataset.userId || '0', 10) || 0;
    var userRole = document.body.dataset.userRole || '';
    var userDinasId = parseInt(document.body.dataset.dinasId || '0', 10) || 0;

    var listEl = document.getElementById('thread-list');
    var searchEl = document.getElementById('forum-search');
    var categoryEl = document.getElementById('forum-category');
    var orgEl = document.getElementById('forum-org');
    var catListEl = document.getElementById('forum-cat-list');
    var modal = document.getElementById('forum-modal');
    var newBtn = document.getElementById('forum-new-btn');
    var closeBtn = document.getElementById('forum-modal-close');
    var cancelBtn = document.getElementById('forum-modal-cancel');
    var saveBtn = document.getElementById('forum-modal-save');
    var titleInput = document.getElementById('forum-title');
    var catSelect = document.getElementById('forum-cat-select');
    var opdSelect = document.getElementById('forum-opd-select');
    var contentInput = document.getElementById('forum-content');
    var titleCount = document.getElementById('forum-title-count');
    var contentCount = document.getElementById('forum-content-count');
    var titleErr = document.getElementById('forum-title-err');
    var catErr = document.getElementById('forum-cat-err');
    var opdErr = document.getElementById('forum-opd-err');
    var contentErr = document.getElementById('forum-content-err');
    var titleRow = document.getElementById('forum-title-row');
    var catRow = document.getElementById('forum-cat-row');
    var opdRow = document.getElementById('forum-opd-row');
    var contentRow = document.getElementById('forum-content-row');
    var kpiTopics = document.getElementById('forum-kpi-topics');
    var kpiReplies = document.getElementById('forum-kpi-replies');
    var kpiViews = document.getElementById('forum-kpi-views');

    var threads = [];
    var dinasList = [];
    var searchTimer = null;

    function toast(msg, type) {
        if (window.Utils && Utils.showToast) Utils.showToast(msg, type || 'info');
    }

    function safeText(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function initials(name) {
        var n = String(name || '').trim();
        if (!n) return 'A';
        return (n.split(/\s+/)[0][0] || 'A').toUpperCase();
    }

    function timeAgo(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr.replace(' ', 'T'));
        if (isNaN(d.getTime())) return dateStr.split(' ')[0] || dateStr;
        var diff = Math.floor((Date.now() - d.getTime()) / 1000);
        if (diff < 60) return 'baru saja';
        if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
        if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
        if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function catAccent(cat) {
        var k = String(cat || '').toLowerCase();
        if (k.indexOf('problem') > -1) return 'cat-problem';
        if (k.indexOf('pengumum') > -1) return 'cat-pengumuman';
        if (k.indexOf('umum') > -1) return 'cat-umum';
        if (k.indexOf('koordinasi') > -1 || k.indexOf('panduan') > -1) return 'cat-koordinasi';
        if (k.indexOf('technical') > -1 || k.indexOf('teknis') > -1) return 'cat-dinas';
        if (k.indexOf('saran') > -1) return 'cat-permintaan';
        return 'cat-default';
    }

    function catTagClass(cat) {
        var k = String(cat || '').toLowerCase();
        if (k.indexOf('problem') > -1) return 'tag-problem';
        if (k.indexOf('pengumum') > -1) return 'tag-pengumuman';
        if (k.indexOf('umum') > -1) return 'tag-umum';
        if (k.indexOf('koordinasi') > -1 || k.indexOf('panduan') > -1) return 'tag-koordinasi';
        if (k.indexOf('technical') > -1) return 'tag-dinas-cat';
        if (k.indexOf('saran') > -1) return 'tag-permintaan';
        return 'tag-default';
    }

    function dinasName(id) {
        var d = dinasList.find(function (x) { return x.id === id; });
        return d ? d.name : '';
    }

    function updateKpi() {
        if (kpiTopics) kpiTopics.textContent = threads.length;
        if (kpiReplies) kpiReplies.textContent = threads.reduce(function (s, t) { return s + (t.replies || 0); }, 0);
        if (kpiViews) kpiViews.textContent = threads.reduce(function (s, t) { return s + (t.views || 0); }, 0);
    }

    function updateCatSidebar() {
        if (!catListEl) return;
        var active = categoryEl ? categoryEl.value : '';
        catListEl.querySelectorAll('a').forEach(function (a) {
            a.classList.toggle('active', (a.getAttribute('data-cat') || '') === active);
        });
    }

    function renderThreads() {
        if (!listEl) return;
        updateKpi();
        if (!threads.length) {
            listEl.innerHTML = '<div class="thread-empty"><i class="fas fa-comments"></i>Belum ada diskusi.<br>Klik <strong>Topik Baru</strong> untuk memulai percakapan pertama.</div>';
            return;
        }
        listEl.innerHTML = threads.map(function (t) {
            var author = t.author || 'Anonim';
            var avatar = t.author_photo_url
                ? '<img src="' + safeText(t.author_photo_url) + '" alt="">'
                : initials(author);
            var preview = String(t.content || '').slice(0, 160);
            if ((t.content || '').length > 160) preview += '...';
            var canDel = (t.user_id === currentUserId) || (userRole === 'super_admin');
            var delBtn = canDel
                ? '<button class="forum-card-del" data-del-thread="' + t.id + '" type="button" title="Hapus"><i class="fas fa-trash-alt"></i></button>'
                : '';
            var dinasLabel = dinasName(t.dinas_id);
            var tags = [
                dinasLabel ? '<span class="forum-tag tag-dinas">' + safeText(dinasLabel) + '</span>' : '',
                t.category ? '<span class="forum-tag ' + catTagClass(t.category) + '">' + safeText(t.category) + '</span>' : ''
            ].filter(Boolean).join('');
            return '<article class="forum-card" data-thread-id="' + t.id + '">' +
                '<div class="forum-card-accent ' + catAccent(t.category) + '"></div>' +
                '<div class="forum-card-body">' +
                '<div class="forum-card-top">' +
                '<div class="forum-card-avatar">' + avatar + '</div>' +
                '<div class="forum-card-head">' +
                '<h3 class="forum-card-title">' + safeText(t.title) + '</h3>' +
                '<div class="forum-card-meta"><span>' + safeText(author) + '</span><span class="dot">·</span><span>' + timeAgo(t.created_at) + '</span></div>' +
                '</div>' + delBtn +
                '</div>' +
                (preview ? '<p class="forum-card-preview">' + safeText(preview) + '</p>' : '') +
                (tags ? '<div class="forum-card-tags">' + tags + '</div>' : '') +
                '<div class="forum-card-footer">' +
                '<div class="forum-stat-pills">' +
                '<span class="forum-stat-pill"><i class="fas fa-thumbs-up"></i>' + (t.likes || 0) + '</span>' +
                '<span class="forum-stat-pill"><i class="fas fa-comment"></i>' + (t.replies || 0) + ' balasan</span>' +
                '<span class="forum-stat-pill"><i class="fas fa-eye"></i>' + (t.views || 0) + ' views</span>' +
                '</div>' +
                '<span class="forum-card-link" data-open-thread="' + t.id + '">Lihat diskusi <i class="fas fa-arrow-right"></i></span>' +
                '</div></div></article>';
        }).join('');
    }

    async function loadThreads() {
        if (!listEl) return;
        var q = searchEl ? searchEl.value.trim() : '';
        var cat = categoryEl ? categoryEl.value : '';
        var org = orgEl ? orgEl.value : '';
        var url = '/forum/threads?search=' + encodeURIComponent(q) + '&category=' + encodeURIComponent(cat) + '&org=' + encodeURIComponent(org);
        listEl.innerHTML = '<div class="thread-empty"><i class="fas fa-spinner fa-spin"></i>Memuat diskusi...</div>';
        try {
            var res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            threads = res.ok ? await res.json() : [];
            if (!Array.isArray(threads)) threads = [];
            renderThreads();
        } catch (_) {
            listEl.innerHTML = '<div class="thread-empty" style="color:#ef4444"><i class="fas fa-exclamation-circle"></i>Gagal memuat diskusi.<br>Silakan muat ulang halaman.</div>';
        }
    }

    async function loadDinas() {
        try {
            var res = await fetch('/forum/dinas', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            dinasList = res.ok ? await res.json() : [];
            if (!Array.isArray(dinasList)) dinasList = [];
            [orgEl, opdSelect].forEach(function (sel) {
                if (!sel) return;
                var keep = sel.querySelector('option');
                sel.innerHTML = '';
                if (keep) sel.appendChild(keep);
                dinasList.forEach(function (d) {
                    var opt = document.createElement('option');
                    opt.value = String(d.id);
                    opt.textContent = d.name;
                    sel.appendChild(opt);
                });
                if (sel === opdSelect && userDinasId) sel.value = String(userDinasId);
            });
        } catch (_) {}
    }

    function toggleModal(show) {
        if (!modal) return;
        modal.style.display = show ? 'flex' : 'none';
        if (show) validateForm();
    }

    function validateForm() {
        var title = titleInput ? titleInput.value.trim() : '';
        var cat = catSelect ? catSelect.value : '';
        var opd = opdSelect ? opdSelect.value : '';
        var content = contentInput ? contentInput.value.trim() : '';
        var okTitle = title.length >= 10;
        var okCat = !!cat;
        var okOpd = !!opd;
        var okContent = content.length >= 20;
        if (titleCount) titleCount.textContent = title.length + '/80';
        if (contentCount) contentCount.textContent = content.length + '/500';
        if (titleErr) titleErr.textContent = okTitle ? '' : 'Minimal 10 karakter';
        if (catErr) catErr.textContent = okCat ? '' : 'Pilih kategori';
        if (opdErr) opdErr.textContent = okOpd ? '' : 'Pilih dinas';
        if (contentErr) contentErr.textContent = okContent ? '' : 'Minimal 20 karakter';
        function setRow(row, good) {
            if (!row) return;
            row.classList.remove('valid', 'invalid');
            row.classList.add(good ? 'valid' : 'invalid');
        }
        setRow(titleRow, okTitle);
        setRow(catRow, okCat);
        setRow(opdRow, okOpd);
        setRow(contentRow, okContent);
        if (saveBtn) saveBtn.disabled = !(okTitle && okCat && okOpd && okContent);
        return okTitle && okCat && okOpd && okContent;
    }

    function resetForm() {
        if (titleInput) titleInput.value = '';
        if (contentInput) contentInput.value = '';
        if (catSelect) catSelect.selectedIndex = 0;
        if (opdSelect) opdSelect.value = userDinasId ? String(userDinasId) : '';
        [titleRow, catRow, opdRow, contentRow].forEach(function (r) { if (r) r.classList.remove('valid', 'invalid'); });
        validateForm();
    }

    async function saveThread() {
        if (!validateForm()) { toast('Lengkapi semua field diskusi', 'error'); return; }
        var payload = {
            title: titleInput.value.trim(),
            category: catSelect.value,
            content: contentInput.value.trim(),
            dinas_id: parseInt(opdSelect.value, 10) || null
        };
        try {
            var res = await fetch('/forum/threads', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });
            if (!res.ok) { toast('Gagal membuat diskusi', 'error'); return; }
            var data = await res.json();
            toggleModal(false);
            resetForm();
            toast('Diskusi berhasil dibuat', 'success');
            if (data && data.id) {
                window.location.href = '/forum/threads/' + data.id + '/view';
            } else {
                await loadThreads();
            }
        } catch (_) { toast('Gagal membuat diskusi', 'error'); }
    }

    async function deleteThread(id) {
        var ok = false;
        try {
            ok = window.Utils && Utils.confirm
                ? await Utils.confirm('Topik diskusi akan dihapus permanen. Lanjutkan?', { title: 'Hapus Topik', okText: 'Hapus', cancelText: 'Batal', variant: 'danger' })
                : confirm('Hapus topik diskusi ini?');
        } catch (_) { ok = confirm('Hapus topik diskusi ini?'); }
        if (!ok) return;
        try {
            var res = await fetch('/forum/threads/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            if (res.ok) { toast('Topik dihapus', 'success'); await loadThreads(); }
            else toast('Gagal menghapus topik', 'error');
        } catch (_) { toast('Gagal menghapus topik', 'error'); }
    }

    if (newBtn) newBtn.addEventListener('click', function () { toggleModal(true); titleInput && titleInput.focus(); });
    if (closeBtn) closeBtn.addEventListener('click', function () { toggleModal(false); });
    if (cancelBtn) cancelBtn.addEventListener('click', function () { toggleModal(false); });
    if (saveBtn) saveBtn.addEventListener('click', saveThread);
    if (titleInput) titleInput.addEventListener('input', validateForm);
    if (contentInput) contentInput.addEventListener('input', validateForm);
    if (catSelect) catSelect.addEventListener('change', validateForm);
    if (opdSelect) opdSelect.addEventListener('change', validateForm);
    if (contentInput) contentInput.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && saveBtn && !saveBtn.disabled) saveThread();
    });
    if (searchEl) searchEl.addEventListener('input', function () { clearTimeout(searchTimer); searchTimer = setTimeout(loadThreads, 300); });
    if (categoryEl) categoryEl.addEventListener('change', function () { updateCatSidebar(); loadThreads(); });
    if (orgEl) orgEl.addEventListener('change', loadThreads);
    if (catListEl) catListEl.addEventListener('click', function (e) {
        var link = e.target.closest('a[data-cat]');
        if (!link) return;
        e.preventDefault();
        if (categoryEl) categoryEl.value = link.getAttribute('data-cat') || '';
        updateCatSidebar();
        loadThreads();
    });
    if (listEl) listEl.addEventListener('click', function (e) {
        if (e.target.closest('[data-del-thread]')) {
            e.stopPropagation();
            deleteThread(parseInt(e.target.closest('[data-del-thread]').getAttribute('data-del-thread'), 10));
            return;
        }
        var openEl = e.target.closest('[data-open-thread]');
        if (openEl) {
            e.stopPropagation();
            window.location.href = '/forum/threads/' + openEl.getAttribute('data-open-thread') + '/view';
            return;
        }
        var item = e.target.closest('.forum-card[data-thread-id]');
        if (item && !e.target.closest('button')) {
            window.location.href = '/forum/threads/' + item.getAttribute('data-thread-id') + '/view';
        }
    });
    if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) toggleModal(false); });

    loadDinas().then(loadThreads);
});
</script>
@endpush
