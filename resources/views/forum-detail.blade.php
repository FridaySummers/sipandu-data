@extends('layouts.app')
@section('title', 'Detail Diskusi')
@section('body-class', 'dashboard-page force-light')

@section('content')
<div class="page active" id="forum-detail-page">
    <div class="forum-detail-header">
        <a href="{{ route('forum') }}" class="forum-back-btn" title="Kembali ke Forum">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Detail Diskusi</h1>
    </div>
    <div class="forum-detail-wrap" data-thread-id="{{ $thread->id }}">
        <div class="detail-body" id="fdp-body">
            <div class="thread-empty" style="border-radius:20px;padding:48px;text-align:center;color:#64748b;background:#f8fafc;border:2px dashed #cbd5e1">
                <i class="fas fa-spinner fa-spin" style="font-size:2rem;color:#cbd5e1;display:block;margin-bottom:12px"></i>
                Memuat diskusi...
            </div>
        </div>
        <div class="detail-reply">
            <textarea id="fdp-reply-input" rows="2" placeholder="Tulis komentar Anda..."></textarea>
            <button class="btn btn-primary" id="fdp-reply-send" type="button">
                <i class="fas fa-paper-plane"></i> Kirim
            </button>
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
    var threadId = parseInt(document.querySelector('.forum-detail-wrap')?.dataset.threadId || '0', 10) || 0;
    var detailBody = document.getElementById('fdp-body');
    var replySend = document.getElementById('fdp-reply-send');
    var replyInput = document.getElementById('fdp-reply-input');
    var currentUserId = parseInt(document.body.dataset.userId || '0', 10) || 0;
    var userRole = document.body.dataset.userRole || '';

    function initials(name) {
        var n = String(name || '').trim();
        if (!n) return 'A';
        return (n.split(/\s+/)[0][0] || 'A').toUpperCase();
    }

    function safeText(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function formatDate(d) {
        if (!d) return '';
        var dt = new Date(String(d).replace(' ', 'T'));
        if (isNaN(dt.getTime())) return String(d).split(' ')[0] || d;
        return dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function formatTime(d) {
        if (!d) return '';
        var dt = new Date(String(d).replace(' ', 'T'));
        if (isNaN(dt.getTime())) return d;
        return dt.toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function catCls(c) {
        var k = String(c || '').toLowerCase();
        if (k.indexOf('problem') > -1) return 'fc-cat-problem';
        if (k.indexOf('keuangan') > -1) return 'fc-cat-keuangan';
        if (k.indexOf('pengumum') > -1) return 'fc-cat-pengumuman';
        if (k.indexOf('umum') > -1) return 'fc-cat-umum';
        if (k.indexOf('koordinasi') > -1 || k.indexOf('panduan') > -1) return 'fc-cat-koordinasi';
        if (k.indexOf('permintaan') > -1 || k.indexOf('saran') > -1) return 'fc-cat-permintaan';
        if (k.indexOf('kebijakan') > -1) return 'fc-cat-kebijakan';
        if (k.indexOf('technical') > -1 || k.indexOf('dinas') > -1 || k.indexOf('teknis') > -1) return 'fc-cat-dinas';
        return 'fc-cat-default';
    }

    function replyItem(r, t, isPinned) {
        var canDel = (r.user_id === currentUserId) || (userRole === 'super_admin');
        var canPin = (t && t.user_id === currentUserId) || (userRole === 'super_admin');
        var delBtn = canDel ? '<button class="btn btn-outline btn-xs" data-del-reply="' + r.id + '" type="button" title="Hapus"><i class="fas fa-trash"></i></button>' : '';
        var pinBtn = canPin ? '<button class="btn btn-outline btn-xs" ' + (r.pinned ? 'data-unpin-reply' : 'data-pin-reply') + '="' + r.id + '" type="button" title="Pin"><i class="fas fa-thumbtack"></i></button>' : '';
        var uname = r.username || 'Anonim';
        var avatarR = r.photo_url ? '<img src="' + safeText(r.photo_url) + '" alt="">' : initials(uname);
        var badge = isPinned ? '<span class="reply-pin-badge"><i class="fas fa-thumbtack"></i> Terpin</span>' : '';
        return '<div class="reply-item' + (isPinned ? ' pinned' : '') + '">' +
            '<div style="display:flex;align-items:flex-start;gap:12px">' +
            '<div class="reply-avatar">' + avatarR + '</div>' +
            '<div class="reply-content-wrap" style="flex:1;min-width:0">' +
            '<div class="reply-user">' + safeText(uname) + badge + '</div>' +
            '<div class="reply-content">' + safeText(r.content || '') + '</div>' +
            '<div class="reply-meta">' + formatTime(r.created_at) + '</div>' +
            '</div></div>' +
            '<div class="reply-actions">' + pinBtn + delBtn + '</div></div>';
    }

    async function openDetail() {
        if (!detailBody) return;
        try {
            var res = await fetch('/forum/threads?search=&org=', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            var threads = res.ok ? await res.json() : [];
            var t = (threads || []).find(function (x) { return x.id === threadId; });
            if (!t) {
                detailBody.innerHTML = '<div class="reply-empty"><i class="fas fa-exclamation-circle"></i><div class="reply-empty-box">Diskusi tidak ditemukan.</div></div>';
                return;
            }
            var rRes = await fetch('/forum/threads/' + threadId + '/replies', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            var replies = rRes.ok ? await rRes.json() : [];
            if (!Array.isArray(replies)) replies = [];

            var author = t.author || 'Anonim';
            var avatar = t.author_photo_url ? '<img src="' + safeText(t.author_photo_url) + '" alt="">' : initials(author);
            var catChip = t.category ? '<span class="fc-chip ' + catCls(t.category) + '">' + safeText(t.category) + '</span>' : '';

            var head = '<div class="detail-card">' +
                '<div class="detail-header">' +
                '<div class="detail-avatar">' + avatar + '</div>' +
                '<div><div class="detail-author">' + safeText(author) + '<span class="detail-date">' + formatDate(t.created_at) + '</span></div></div>' +
                '</div>' +
                '<div class="detail-title">' + safeText(t.title || '') + '</div>' +
                (catChip ? '<div class="detail-meta">' + catChip + '</div>' : '') +
                '<div class="detail-content">' + safeText(t.content || '') + '</div>' +
                '<div class="detail-stats">' +
                '<span class="detail-stat-chip"><i class="fas fa-comment"></i> ' + replies.length + ' komentar</span>' +
                '<span class="detail-stat-chip"><i class="fas fa-eye"></i> ' + (t.views || 0) + ' views</span>' +
                '<span class="detail-stat-chip"><i class="fas fa-thumbs-up"></i> ' + (t.likes || 0) + ' suka</span>' +
                '</div></div>';

            var pinned = replies.filter(function (r) { return !!r.pinned; });
            var normal = replies.filter(function (r) { return !r.pinned; });
            var pinHead = pinned.length ? '<div class="reply-section-head"><i class="fas fa-thumbtack"></i> Komentar Terpin</div>' : '';
            var replyHeader = '<div class="reply-header"><i class="fas fa-comments"></i> Komentar (' + replies.length + ')</div>';
            var bodyHtml = '';

            if (replies.length === 0) {
                bodyHtml = '<div class="reply-empty"><i class="far fa-comment-dots"></i><div class="reply-empty-box">Belum ada komentar.<br>Jadilah yang pertama berkomentar!</div></div>';
            } else {
                bodyHtml = pinHead + pinned.map(function (r) { return replyItem(r, t, true); }).join('') +
                    normal.map(function (r) { return replyItem(r, t, false); }).join('');
            }

            detailBody.innerHTML = head + '<div class="reply-list">' + replyHeader + bodyHtml + '</div>';
        } catch (_) {
            detailBody.innerHTML = '<div class="reply-empty"><i class="fas fa-exclamation-circle"></i><div class="reply-empty-box">Gagal memuat diskusi.</div></div>';
        }
    }

    replySend?.addEventListener('click', async function () {
        if (!replyInput || !threadId) return;
        var content = replyInput.value.trim();
        if (!content) return;
        replySend.disabled = true;
        try {
            var res = await fetch('/forum/threads/' + threadId + '/replies', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ content: content })
            });
            if (res.ok) {
                replyInput.value = '';
                if (window.Utils && Utils.showToast) Utils.showToast('Komentar terkirim', 'success');
                await openDetail();
            } else {
                if (window.Utils && Utils.showToast) Utils.showToast('Gagal mengirim komentar', 'error');
            }
        } catch (_) {
            if (window.Utils && Utils.showToast) Utils.showToast('Gagal mengirim komentar', 'error');
        }
        replySend.disabled = false;
    });

    replyInput?.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') replySend?.click();
    });

    document.addEventListener('click', async function (e) {
        var del = e.target.closest('[data-del-reply]');
        if (del) {
            var id = parseInt(del.getAttribute('data-del-reply'), 10);
            var ok = false;
            try {
                ok = window.Utils && Utils.confirm
                    ? await Utils.confirm('Balasan akan dihapus permanen. Lanjutkan?', { title: 'Hapus Balasan', okText: 'Hapus', cancelText: 'Batal', variant: 'danger' })
                    : confirm('Hapus balasan ini?');
            } catch (_) { ok = confirm('Hapus balasan ini?'); }
            if (!ok) return;
            try {
                var res = await fetch('/forum/threads/' + threadId + '/replies/' + id, {
                    method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin'
                });
                if (res.ok) {
                    if (window.Utils && Utils.showToast) Utils.showToast('Balasan dihapus', 'success');
                    await openDetail();
                } else {
                    if (window.Utils && Utils.showToast) Utils.showToast('Gagal menghapus balasan', 'error');
                }
            } catch (_) {
                if (window.Utils && Utils.showToast) Utils.showToast('Gagal menghapus balasan', 'error');
            }
            return;
        }
        var pin = e.target.closest('[data-pin-reply]');
        if (pin) {
            var pinId = parseInt(pin.getAttribute('data-pin-reply'), 10);
            try {
                var pRes = await fetch('/forum/threads/' + threadId + '/replies/' + pinId + '/pin', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin'
                });
                if (pRes.ok) await openDetail();
            } catch (_) {}
            return;
        }
        var unpin = e.target.closest('[data-unpin-reply]');
        if (unpin) {
            var unpinId = parseInt(unpin.getAttribute('data-unpin-reply'), 10);
            try {
                var uRes = await fetch('/forum/threads/' + threadId + '/replies/' + unpinId + '/unpin', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin'
                });
                if (uRes.ok) await openDetail();
            } catch (_) {}
        }
    });

    openDetail();
});
</script>
@endpush
