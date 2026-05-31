// SIPANDU DATA - Dashboard JavaScript
// Dashboard-specific functionality

class DashboardManager {
  constructor() {
    this.currentPage = 'dashboard';
    this.sidebarCollapsed = false;
    this.charts = {};
    this.activities = [];
    this.init();
  }

  init() {
    this.checkAuthentication();
    this.initializeUI();
    this.bindEvents();
    this.startNotificationPolling();
    this.loadDashboardData();
    //this.renderActivityFeed(); DIMATIIN AGAR TIDAK TERTIMPA DENGAN DATABASE
    const active = document.querySelector('.page.active');
    if (active && active.id) {
      const pageName = active.id.replace('-page','');
      this.switchPage(pageName);
    }
  }

  // Check if user is authenticated
  checkAuthentication() {
    if (!authHandler.isAuthenticated()) {
      return;
    }

    const currentUser = authHandler.getCurrentUser();
    this.updateUserInfo(currentUser);
  }

  // Update user information in the UI
  updateUserInfo(user) {
    const userName = document.getElementById('user-name');
    const sidebarUserName = document.getElementById('sidebar-user-name');
    const sidebarUserRole = document.getElementById('sidebar-user-role');

    if (userName) userName.textContent = user.name;
    if (sidebarUserName) sidebarUserName.textContent = user.name;
    if (sidebarUserRole) sidebarUserRole.textContent = user.position;
  }

  // Initialize UI components
  initializeUI() {
    this.initializeSidebar();
    this.initializeNavigation();
    this.initializeDropdowns();
    // Hilangkan notifikasi dummy; akan digantikan data real dari server
  }

  // Initialize sidebar functionality
  initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        this.sidebarCollapsed = !this.sidebarCollapsed;

        if (this.sidebarCollapsed) {
          sidebar.classList.add('collapsed');
        } else {
          sidebar.classList.remove('collapsed');
        }

        // Store preference
        localStorage.setItem('sipandu_sidebar_collapsed', this.sidebarCollapsed);
      });
    }

    // Restore sidebar state
    const savedState = localStorage.getItem('sipandu_sidebar_collapsed');
    if (savedState === 'true') {
      this.sidebarCollapsed = true;
      sidebar.classList.add('collapsed');
    }
  }

  // Initialize navigation
  initializeNavigation() {
    // Sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');
        // Jika href menuju file lain, biarkan browser melakukan navigasi normal
        if (href && !href.startsWith('#')) {
          return;
        }
        e.preventDefault();
        const page = link.dataset.page;
        if (page) {
          this.switchPage(page);
          this.updateActiveNavigation(link);
        }
      });
    });

    // Mobile bottom navigation
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
    bottomNavItems.forEach(item => {
      item.addEventListener('click', (e) => {
        const href = item.getAttribute('href');
        if (href && !href.startsWith('#')) {
          return;
        }
        e.preventDefault();
        const page = item.dataset.page;
        if (page) {
          this.switchPage(page);
          this.updateActiveBottomNav(item);
        }
      });
    });
  }

  // Switch between pages
  switchPage(pageName) {
    // Hide all pages
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => page.classList.remove('active'));

    // Show target page
    const targetPage = document.getElementById(pageName + '-page');
    if (targetPage) {
      targetPage.classList.add('active');
      this.currentPage = pageName;

      // Page-specific initialization
      if (pageName === 'dashboard') {
        this.refreshCharts();
        this.initializeActivity();
      } else if (pageName === 'data-management') {
        this.initializeDataManagement();
      } else if (pageName === 'reports') {
        // removed
      } else if (pageName === 'forum') {
        this.initializeForum();
      } else if (pageName === 'calendar') {
        this.initializeCalendar();
      } else if (pageName === 'dinas-status') {
        this.initializeDinasStatus();
      } else if (pageName === 'settings') {
        this.initializeSettings();
      }
    }
  }

  // Update active navigation state
  updateActiveNavigation(activeLink) {
    const navItems = document.querySelectorAll('.sidebar .nav-item');
    navItems.forEach(item => item.classList.remove('active'));
    activeLink.closest('.nav-item').classList.add('active');
  }

  // Update active bottom navigation state
  updateActiveBottomNav(activeItem) {
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
    bottomNavItems.forEach(item => item.classList.remove('active'));
    activeItem.classList.add('active');
  }

  // Initialize dropdown functionality
  initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-dropdown]');

    dropdownTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdownId = trigger.dataset.dropdown;
        const dropdown = document.getElementById(dropdownId);

        if (dropdown) {
          // Close other dropdowns
          document.querySelectorAll('.dropdown.show').forEach(d => {
            if (d !== dropdown) d.classList.remove('show');
          });

          dropdown.classList.toggle('show');
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
      document.querySelectorAll('.dropdown.show').forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    });

    // Notification and user menu dropdowns
    const notifications = document.getElementById('notifications');
    const userMenu = document.getElementById('user-menu');

    if (notifications) {
      notifications.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown) {
          dropdown.classList.toggle('show');
          document.getElementById('user-dropdown')?.classList.remove('show');
        }
      });
    }

    if (userMenu) {
      userMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown) {
          dropdown.classList.toggle('show');
          document.getElementById('notification-dropdown')?.classList.remove('show');
        }
      });
    }
  }

  startNotificationPolling() {
    let lastTs = '';
    const badge = document.getElementById('notification-count');
    const notifList = document.getElementById('notification-list');
    const markAllBtn = document.querySelector('.mark-all-read');

    const renderItems = (items) => {
      if (!notifList) return;
      const html = items.map(it => {
        const icon = 'calendar-plus';
        const ts = new Date(it.timestamp).toLocaleString('id-ID');
        const unreadClass = it.unread ? 'unread' : '';
        return `<div class="notification-item ${unreadClass}"><div class="notification-icon info"><i class="fas fa-${icon}"></i></div><div class="notification-content"><div class="notification-title">${it.title}</div><div class="notification-message">${it.description} • ${ts}</div></div></div>`;
      }).join('');
      notifList.innerHTML = html;
    };

    const setLoading = (on) => {
      if (!notifList) return;
      notifList.innerHTML = on ? `<div class="thread-meta">Memuat notifikasi...</div>` : '';
    };

    const setBadge = (count) => {
      if (!badge) return;
      badge.textContent = String(count || 0);
      badge.style.display = (count || 0) > 0 ? 'block' : 'none';
    };

    const fetchInitial = () => {
      setLoading(true);
      fetch('/notifications').then(r=>r.json()).then(items=>{
        setLoading(false);
        if (!Array.isArray(items)) items = [];
        renderItems(items);
        const unread = items.filter(i=> i.unread).length;
        setBadge(unread);
        if (items[0]?.timestamp) lastTs = items[0].timestamp;
      }).catch(()=>{
        setLoading(false);
        notifList && (notifList.innerHTML = `<div class="thread-meta" style="color:#ef4444">Gagal memuat notifikasi</div>`);
      });
    };

    const pollNew = () => {
      const url = '/notifications?since='+encodeURIComponent(lastTs||'');
      fetch(url).then(r=>r.json()).then(items=>{
        if (!Array.isArray(items)) items = [];
        if (!items.length) return;
        const html = items.map(it => {
          const icon = 'calendar-plus';
          const ts = new Date(it.timestamp).toLocaleString('id-ID');
          const unreadClass = it.unread ? 'unread' : '';
          return `<div class="notification-item ${unreadClass}"><div class="notification-icon info"><i class="fas fa-${icon}"></i></div><div class="notification-content"><div class="notification-title">${it.title}</div><div class="notification-message">${it.description} • ${ts}</div></div></div>`;
        }).join('');
        if (notifList) notifList.innerHTML = html + (notifList.innerHTML||'');
        const incUnread = items.filter(i=> i.unread).length;
        setBadge((parseInt(badge?.textContent||'0',10)||0) + incUnread);
        if (items[0]?.timestamp) lastTs = items[0].timestamp;
      }).catch(()=>{});
    };

    if (markAllBtn) {
      markAllBtn.addEventListener('click', () => {
        fetch('/notifications/read-all', { method:'POST', headers: { 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.content || '') } })
          .then(r=> r.ok ? r.json() : Promise.reject())
          .then(()=>{
            setBadge(0);
            document.querySelectorAll('#notification-list .notification-item.unread').forEach(el=> el.classList.remove('unread'));
          }).catch(()=>{
            notifList && (notifList.innerHTML = `<div class="thread-meta" style="color:#ef4444">Gagal memperbarui status notifikasi</div>`);
          });
      });
    }

    fetchInitial();
    setInterval(pollNew, 10000);
  }

  initializeActivity() {
    const list = document.getElementById('activity-list');
    const opdSel = document.getElementById('activity-opd');
    const prev = document.getElementById('activity-prev');
    const next = document.getElementById('activity-next');
    const pageText = document.getElementById('activity-page-text');
    let page = 1, size = 10, opd = '', lastTs = '';
    const render = () => {
      const url = '/dashboard/activity?page='+page+'&size='+size+(opd?('&opd='+encodeURIComponent(opd)):'');
      fetch(url).then(r=>r.json()).then(rows=>{
        if (!Array.isArray(rows)) rows = [];
        list.innerHTML = rows.map(r=>{
          const icon = r.action==='create'?'plus-circle':(r.action==='update'?'pen-to-square':(r.action==='delete'?'trash':'check-circle'));
          const sub = r.approval_status ? (' • '+r.approval_status) : '';
          const title = (r.username||'-')+' ('+(r.role||'-')+')';
          const meta = new Date(r.timestamp).toLocaleString('id-ID')+' • '+(r.entity||'')+' • ID '+(r.record_id||'-');
          const name = (r.metadata && r.metadata.name) ? r.metadata.name : ((r.metadata && r.metadata.judul_data) ? r.metadata.judul_data : '-');
          return `<div class="thread-item"><div class="thread-item-inner"><div class="thread-avatar"><i class="fas fa-${icon}"></i></div><div><div class="thread-title">${title}</div><div class="thread-subtitle">${(r.opd||'-')}${sub}</div><div class="thread-meta">${meta}</div></div><div class="thread-stats"><div><i class="fas fa-database"></i>${name}</div></div></div></div>`;
        }).join('');
        const count = rows.length;
        pageText && (pageText.textContent = `Menampilkan ${count ? (((page-1)*size+1)+' - '+((page-1)*size+count)) : '0 - 0'} dari ${((page-1)*size)+count} aktivitas`);
        if (prev) prev.disabled = page<=1; if (next) next.disabled = count < size;
        if (rows[0]?.timestamp) lastTs = rows[0].timestamp;
      }).catch(()=>{ if(list) list.innerHTML=''; });
    };
    const poll = () => {
      const url = '/dashboard/activity?since='+encodeURIComponent(lastTs||'');
      fetch(url).then(r=>r.json()).then(rows=>{
        if (!Array.isArray(rows)) rows = [];
        if (!rows.length) return;
        if (rows[0]?.timestamp) lastTs = rows[0].timestamp;
      }).catch(()=>{});
    };
    render();
    setInterval(poll, 10000);
    if (opdSel) opdSel.addEventListener('change', ()=>{ opd = opdSel.value; page=1; render(); });
    if (prev) prev.addEventListener('click', ()=>{ if(page>1){ page--; render(); } });
    if (next) next.addEventListener('click', ()=>{ page++; render(); });
  }

  initializeDataManagement() {
    const opdSelect = document.getElementById('dm-target-opd');
    if (opdSelect && opdSelect.options.length <= 1) {
      dinasData.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.name;
        opt.textContent = d.name;
        opdSelect.appendChild(opt);
      });
    }

    const dropzone = document.getElementById('dm-dropzone');
    const fileInput = document.getElementById('dm-file-input');
    const selectBtn = document.getElementById('dm-select-files');
    const fileList = document.getElementById('dm-file-list');
    const uploadBtn = document.getElementById('dm-upload-btn');

    const renderFiles = (files) => {
      fileList.innerHTML = Array.from(files).map(f => `
        <div class="file-item">
          <div class="file-meta"><span>${f.name}</span><span>${(f.size/1024).toFixed(1)} KB</span></div>
          <button class="btn btn-outline btn-sm">Hapus</button>
        </div>
      `).join('');
    };

    const handleFiles = (files) => { renderFiles(files); };

    if (selectBtn) selectBtn.onclick = () => fileInput.click();
    if (fileInput) fileInput.onchange = (e) => handleFiles(e.target.files);
    if (dropzone) {
      ['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.add('drag'); }));
      ['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.remove('drag'); }));
      dropzone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files));
    }

    if (uploadBtn) uploadBtn.onclick = () => {
      Utils.showToast('Upload diproses', 'success');
    };
  }

  // initializeReports removed

  initializeForum() {
    const list = document.getElementById('thread-list');
    if (!list) return; // halaman forum tidak aktif, lewati
    return; // UI forum ditangani oleh script di forum.blade.php, hindari duplikasi

    if (opdSelect && opdSelect.options.length === 0) {
      const ph=document.createElement('option'); ph.value=''; ph.textContent='Pilih OPD'; opdSelect.appendChild(ph);
      const src = (typeof window!== 'undefined' && window.dinasData) ? window.dinasData : (typeof dinasData !== 'undefined' ? dinasData : []);
      src.forEach(d=>{ const o=document.createElement('option'); o.value=d.name; o.textContent=d.name; opdSelect.appendChild(o); });
      if (typeof window !== 'undefined' && typeof window.refreshCustomSelect === 'function') { window.refreshCustomSelect(opdSelect); }
    }

    const TITLE_MAX = 80;
    const CONTENT_MAX = 500;

    const getLen = (el) => (el?.value?.length||0);
    const setCount = (el, max, label) => { if (label) label.textContent = `${getLen(el)}/${max}`; };
    const showErr = (el, msg) => { if (el) el.textContent = msg || ''; };
    const isValid = () => {
      const t = titleInput?.value?.trim() || '';
      const c = document.getElementById('forum-content')?.value?.trim() || '';
      const o = opdSelect?.value || '';
      const k = catSelect?.value || '';
      showErr(titleErr, t.length>=10 ? '' : 'Minimal 10 karakter');
      showErr(contentErr, c.length>=20 ? '' : 'Minimal 20 karakter');
      showErr(opdErr, o ? '' : 'Pilih OPD');
      showErr(catErr, k ? '' : 'Pilih kategori');
      setCount(titleInput, TITLE_MAX, titleCount);
      setCount(document.getElementById('forum-content'), CONTENT_MAX, contentCount);
      const okTitle = t.length>=10;
      const okContent = c.length>=20;
      const okOpd = !!o;
      const okCat = !!k;
      const ok = okTitle && okContent && okOpd && okCat;
      const setState = (row, good) => { if (!row) return; row.classList.remove('invalid','valid'); row.classList.add(good? 'valid':'invalid'); };
      setState(titleRow, okTitle);
      setState(contentRow, okContent);
      setState(opdRow, okOpd);
      setState(catRow, okCat);
      if (saveBtn) saveBtn.disabled = !ok;
      return ok;
    };

    if (titleInput) titleInput.addEventListener('input', isValid);
    if (contentInput) contentInput.addEventListener('input', isValid);
    if (opdSelect) opdSelect.addEventListener('change', isValid);
    if (catSelect) catSelect.addEventListener('change', isValid);

    let threads = [
      {id:1,title:'Diskusi Metodologi Pengumpulan Data Ekonomi Regional',subtitle:'Bagaimana pendekatan terbaik untuk mengumpulkan data inflasi di daerah?',author:'Ahmad Yani',opd:'Dinas Perdagangan',category:'Metodologi',date:'2025-01-10',lastReply:'2 jam lalu',likes:24,replies:12,views:158,messages:['Gunakan definisi variabel yang konsisten','Pertimbangkan seasonal adjustment']},
      {id:2,title:'Best Practice Pelaporan Data Pendidikan',subtitle:'Mari berbagi pengalaman tentang cara efektif merekap dan validasi',author:'Budi Santoso',opd:'Dinas Pendidikan',category:'Best Practice',date:'2025-01-08',lastReply:'1 jam lalu',likes:31,replies:15,views:234,messages:['Template pelaporan terbaru dilampirkan','Contoh validasi kolom']},
      {id:3,title:'Update Regulasi Pelaporan RKPD 2025',subtitle:'Informasi terbaru mengenai perubahan format pelaporan',author:'Admin Bappeda',opd:'Bappeda',category:'Pengumuman',date:'2025-01-15',lastReply:'1 jam lalu',likes:42,replies:6,views:342,messages:['Regulasi terbaru No. 5/2025','Sosialisasi pekan depan']},
      {id:4,title:'Integrasi Data Kesehatan dengan Sistem Nasional',subtitle:'Perlu bantuan untuk integrasi data e-health nasional',author:'Sri Aminah',opd:'Dinas Kesehatan',category:'Teknis',date:'2025-01-12',lastReply:'5 jam lalu',likes:18,replies:8,views:89,messages:['Endpoint API tersedia','Mapping kolom selesai']},
      {id:5,title:'Kendala Validasi Data Pertanian',subtitle:'Apa kendala masalah dengan validasi data produksi padi?',author:'Rina Wati',opd:'Dinas Pertanian',category:'Problem Solving',date:'2025-01-14',lastReply:'3 jam lalu',likes:15,replies:9,views:112,messages:['Outlier pada produksi padi','Butuh panduan pembersihan data']}
    ];

    let activeId = null;

    const render = () => {
      list.innerHTML = threads.map(t=>`
        <div class="thread-item" data-id="${t.id}">
          <div class="thread-item-inner">
            <div class="thread-avatar">${t.author.charAt(0)}</div>
            <div>
              <div class="thread-title">${t.title}</div>
              <div class="thread-subtitle">${t.subtitle}</div>
              <div class="thread-tags"><span class="tag">${t.opd}</span><span class="tag">${t.category}</span><span class="tag">${DateUtils.formatDate(t.date,{day:'2-digit',month:'short',year:'numeric'})}</span></div>
              <div class="thread-meta">Balasan terakhir: ${t.lastReply}</div>
            </div>
            <div class="thread-stats"><span><i class="fas fa-thumbs-up"></i>${t.likes}</span><span><i class="fas fa-comment"></i>${t.replies}</span><span><i class="fas fa-eye"></i>${t.views} views</span><button class="btn btn-outline btn-sm">Buka</button></div>
          </div>
        </div>
      `).join('');

      list.querySelectorAll('.thread-item').forEach(item=>{
        item.addEventListener('click', ()=>{
          const t=threads.find(x=> x.id===parseInt(item.dataset.id));
          if(!t) return;
          activeId=t.id;
          const msgs=t.messages.map(m=>`<div class='file-item'><div>${m}</div></div>`).join('');
          if (detailBody) detailBody.innerHTML = `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;"><h3>${t.title}</h3><span class="thread-meta">${t.author} • ${t.opd} • ${t.category}</span></div>${msgs}<div class='form-row' style='margin-top:12px'><label>Balasan</label><textarea id='fdm-reply-input' rows='3' placeholder='Tulis balasan...'></textarea></div>`;
          if (detailModal) detailModal.style.display = 'flex';
        });
      });
    };

    render();

    const filterList = () => {
      const query=(search?.value||'').toLowerCase();
      const cat=category?.value||'';
      document.querySelectorAll('#thread-list .thread-item').forEach(item=>{
        const text=item.textContent.toLowerCase();
        const hasQuery=query? text.includes(query) : true;
        const meta=item.querySelector('.thread-tags')?.textContent||'';
        const hasCat=cat? meta.includes(cat) : true;
        item.style.display = (hasQuery && hasCat) ? 'block' : 'none';
      });
    };
    if (search) search.addEventListener('input', Utils.debounce(filterList, 200));
    if (category) category.addEventListener('change', filterList);

    if (replySend) replySend.onclick = () => {
      if (!activeId || !replyInput || !replyInput.value.trim()) return;
      const t = threads.find(x=> x.id===activeId);
      if (!t) return;
      const inp = document.getElementById('fdm-reply-input');
      if (!inp || !inp.value.trim()) return;
      t.messages.push(inp.value.trim());
      inp.value='';
      render();
      Utils.showToast('Balasan dikirim', 'success');
    };
    if (replyCancel) replyCancel.onclick = () => { if (detailModal) detailModal.style.display='none'; };
    if (detailClose) detailClose.onclick = () => { if (detailModal) detailModal.style.display='none'; };

    const toggleModal = (show) => { if (modal) modal.style.display = show ? 'flex' : 'none'; };
    if (newBtn) newBtn.onclick = () => { toggleModal(true); isValid(); titleInput && titleInput.focus(); };
    if (closeBtn) closeBtn.onclick = () => toggleModal(false);
    if (cancelBtn) cancelBtn.onclick = () => toggleModal(false);
    if (saveBtn) saveBtn.onclick = () => {
      if (!isValid()) { Utils.showToast('Lengkapi input diskusi', 'error'); return; }
      const title = titleInput?.value?.trim();
      const opd = opdSelect?.value || '';
      const cat = catSelect?.value || '';
      const content = document.getElementById('forum-content')?.value?.trim() || '';
      if (!title) { Utils.showToast('Judul wajib diisi', 'error'); return; }
      const id = threads.length ? Math.max(...threads.map(t=>t.id))+1 : 1;
      threads.unshift({ id, title, subtitle: content.slice(0,120), author: authHandler.getCurrentUser()?.name || 'User', opd, category: cat, date: new Date().toISOString().slice(0,10), lastReply:'baru saja', likes:0, replies:0, views:0, messages:[content] });
      render();
      toggleModal(false);
      titleInput && (titleInput.value='');
      document.getElementById('forum-content') && (document.getElementById('forum-content').value='');
      if (opdSelect) { opdSelect.selectedIndex = 0; if (typeof window !== 'undefined' && typeof window.refreshCustomSelect === 'function') { window.refreshCustomSelect(opdSelect); } }
      if (catSelect) { catSelect.selectedIndex = 0; if (typeof window !== 'undefined' && typeof window.refreshCustomSelect === 'function') { window.refreshCustomSelect(catSelect); } }
      [titleRow,contentRow,opdRow,catRow].forEach(r=> r && r.classList.remove('valid','invalid'));
      isValid();
      Utils.showToast('Diskusi dibuat', 'success');
    };

    if (contentInput) contentInput.addEventListener('keydown', (e)=>{ if ((e.ctrlKey||e.metaKey) && e.key==='Enter') { e.preventDefault(); if (saveBtn && !saveBtn.disabled) saveBtn.click(); } });
  }

  initializeCalendar() {
    // Disabled FE demo. Calendar page handles rendering via backend scripts in the view.
    return;
  }

  initializeDinasStatus() {
    const table = document.getElementById('ds-table');
    const search = document.getElementById('ds-search');
    const statusSel = document.getElementById('ds-status');
    const exportBtn = document.getElementById('ds-export');
    const kTotal = document.querySelector('#ds-kpi-total .kpi-value');
    const kComp = document.querySelector('#ds-kpi-complete .kpi-value');
    const kProg = document.querySelector('#ds-kpi-progress .kpi-value');
    const kPend = document.querySelector('#ds-kpi-pending .kpi-value');
    if (!table) return;
    let sortKey = 'name'; let sortDir = 'asc';
    const getSorted = (list) => {
      return list.slice().sort((a,b)=>{
        const va = a[sortKey]; const vb = b[sortKey];
        if (typeof va === 'string') return sortDir==='asc' ? va.localeCompare(vb) : vb.localeCompare(va);
        return sortDir==='asc' ? va - vb : vb - va;
      });
    };
    const renderKPI = (list) => {
      const total = list.length;
      const comp = list.filter(d=> d.status==='Complete').length;
      const prog = list.filter(d=> d.status==='Progress').length;
      const pend = list.filter(d=> d.status==='Menunggu Persetujuan').length;
      if (kTotal) kTotal.textContent = total;
      if (kComp) kComp.textContent = comp;
      if (kProg) kProg.textContent = prog;
      if (kPend) kPend.textContent = pend;
    };
    const statusLabel = (st) => (st==='Menunggu Persetujuan' ? 'Menunggu Persetujuan' : st);
    const render = (q='', s='') => {
      const list = dinasData
        .filter(d=> d.name.toLowerCase().includes(q.toLowerCase()))
        .filter(d=> s ? d.status===s : true);
      renderKPI(list);
      const sorted = getSorted(list);
      const rows = sorted.map(d=> {
        const progClass = d.progress >= 80 ? 'high' : (d.progress >= 40 ? 'medium' : 'low');
        const statClass = d.status==='Complete' ? 'status-complete' : (d.status==='Progress' ? 'status-progress' : 'status-menunggu');
        return `
        <tr class="row-accent ${d.status==='Complete' ? 'accent-green' : (d.status==='Progress' ? 'accent-blue' : 'accent-amber')}">
          <td><i class="${d.icon}" style="color:${d.color}; margin-right:8px"></i>${d.name}</td>
          <td>${d.fullName}</td>
          <td><div class="progress-line"><div class="progress-line-fill ${progClass}" style="width:${d.progress}%"></div></div></td>
          <td><span class="status-badge ${statClass}">${statusLabel(d.status)}</span></td>
        </tr>
      `}).join('');
      table.innerHTML = `<thead><tr>
        <th data-sort="name">OPD</th>
        <th data-sort="fullName">Nama Lengkap</th>
        <th data-sort="progress">Progress</th>
        <th data-sort="status">Status</th>
      </tr></thead><tbody>${rows}</tbody>`;
      table.querySelectorAll('th[data-sort]').forEach(th=>{
        th.style.cursor='pointer';
        th.onclick = () => { sortKey = th.dataset.sort; sortDir = sortDir==='asc' ? 'desc' : 'asc'; render(search?.value||'', statusSel?.value||''); };
      });
    };
    render('', '');
    if (search) search.oninput = (e)=> render(e.target.value, statusSel?.value||'');
    if (statusSel) statusSel.onchange = (e)=> render(search?.value||'', e.target.value);
    if (exportBtn) exportBtn.onclick = () => {
      const list = dinasData
        .filter(d=> d.name.toLowerCase().includes((search?.value||'').toLowerCase()))
        .filter(d=> statusSel?.value ? d.status===statusSel.value : true);
      const rows = [['OPD','Nama Lengkap','Progress','Status']].concat(list.map(d=> [d.name, d.fullName, d.progress, statusLabel(d.status)]));
      const csv = rows.map(r=> r.map(x=>`"${x}"`).join(',')).join('\n');
      const blob = new Blob([csv], {type:'text/csv'}); const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = 'status-dinas.csv'; a.click(); URL.revokeObjectURL(url);
    };
  }

  initializeSettings() {
    const profile = document.getElementById('profile-info');
    const themeSelect = document.getElementById('set-theme');
    const notifSelect = document.getElementById('set-notif');
    const saveBtn = document.getElementById('set-save');
    const user = authHandler.getCurrentUser();
    if (profile && user) {
      const hasForm = !!document.getElementById('profile-photo-input');
      if (!hasForm && profile.children.length === 0) {
        const info = document.createElement('div');
        info.className = 'file-item';
        info.innerHTML = `<div><div class="thread-title">${user.name}</div><div class="thread-meta">${user.position}</div></div><div>${user.role}</div>`;
        profile.appendChild(info);
      }
    }
    if (saveBtn) saveBtn.onclick = () => {
      localStorage.setItem('sipandu_pref_theme', themeSelect?.value || 'light');
      localStorage.setItem('sipandu_pref_notif', notifSelect?.value || 'all');
      Utils.showToast('Pengaturan disimpan', 'success');
    };
  }

  // Bind events
  bindEvents() {
    // Logout button
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn && !logoutBtn.dataset.enhanced) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.handleLogout();
      });
    }

    // Quick action buttons
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    quickActionBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const action = btn.querySelector('span').textContent;
        Utils.showToast(`${action} feature coming soon!`, 'info');
      });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
      searchInput.addEventListener('input', Utils.debounce((e) => {
        const query = e.target.value.toLowerCase();
        if (query.length > 2) {
          this.performSearch(query);
        }
      }, 300));
    }
  }

  // Handle logout
  handleLogout() {
    const btn = document.getElementById('logout-btn');
    if (btn && btn.dataset.enhanced) return;
    if (confirm('Apakah Anda yakin ingin keluar?')) { authHandler.logout(); }
  }

  // Perform search
  performSearch(query) {
    // Simple search implementation
    const results = dinasData.filter(dinas => 
      dinas.name.toLowerCase().includes(query) ||
      dinas.fullName.toLowerCase().includes(query)
    );

    if (results.length > 0) {
      Utils.showToast(`Found ${results.length} results for "${query}"`, 'success');
    } else {
      Utils.showToast(`No results found for "${query}"`, 'info');
    }
  }

  // Load dashboard data
  loadDashboardData() {
    // Simulate API call
    setTimeout(() => {
      //this.updateKPICards(); DIMATIIN DULU UNTUK BACKEND biar engga ketimpa data demo fe
      this.initializeCharts();
    }, 500);
  }

  // Update KPI cards with animation
  updateKPICards() {
    const stats = this.calculateStatistics();

    // Animate KPI numbers
    this.animateKPI('total-dinas', stats.totalDinas);
    this.animateKPI('complete-data', stats.completeData);
    this.animateKPI('avg-progress', stats.avgProgress, '%');
    this.animateKPI('pending-reviews', stats.pendingReviews);
  }

  // Calculate statistics from dinas data
  calculateStatistics() {
    const totalDinas = dinasData.length;
    const completeData = dinasData.filter(d => d.status === 'Complete').length;
    const avgProgress = Math.round(dinasData.reduce((sum, d) => sum + d.progress, 0) / totalDinas);
    const pendingReviews = dinasData.filter(d => d.status === 'Menunggu Persetujuan').length + 
                          dinasData.filter(d => d.status === 'Progress').length;

    return { totalDinas, completeData, avgProgress, pendingReviews };
  }

  // Animate KPI numbers
  animateKPI(elementId, finalValue, suffix = '') {
    const element = document.getElementById(elementId);
    if (!element) return;

    const startValue = 0;
    const duration = 1500;
    const startTime = Date.now();

    const animate = () => {
      const currentTime = Date.now();
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Easing function
      const easedProgress = 1 - Math.pow(1 - progress, 3);
      const currentValue = Math.round(startValue + (finalValue - startValue) * easedProgress);

      element.textContent = currentValue + suffix;

      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    };

    requestAnimationFrame(animate);
  }

  // Initialize charts
  initializeCharts() {
    // Initialize charts if ChartManager is available
    if (typeof ChartManager !== 'undefined') {
      this.chartManager = new ChartManager();
      this.chartManager.initializeAllCharts();
    }
  }

  // Refresh charts
  refreshCharts() {
    if (this.chartManager) {
      this.chartManager.refreshAllCharts();
    }
  }

  // Render activity feed
  renderActivityFeed() {
    const activities = [
      {
        id: 1,
        type: 'upload',
        title: 'Data Upload',
        description: 'Perdagangan mengupload data triwulan III',
        time: '5 menit lalu',
        icon: 'upload',
        color: '#10b981'
      },
      {
        id: 2,
        type: 'approval',
        title: 'Data Approved',
        description: 'Data Perikanan telah disetujui oleh Bappeda',
        time: '2 jam lalu',
        icon: 'check-circle',
        color: '#22c55e'
      },
      {
        id: 3,
        type: 'discussion',
        title: 'New Discussion',
        description: 'Forum diskusi baru: Standarisasi Format Data',
        time: '4 jam lalu',
        icon: 'comments',
        color: '#3b82f6'
      },
      {
        id: 4,
        type: 'deadline',
        title: 'Reminder',
        description: 'Deadline pengumpulan data: 3 hari lagi',
        time: '1 hari lalu',
        icon: 'exclamation-triangle',
        color: '#f59e0b'
      },
      {
        id: 5,
        type: 'system',
        title: 'System Update',
        description: 'SIPANDU DATA telah diperbarui ke versi 1.1',
        time: '2 hari lalu',
        icon: 'cog',
        color: '#64748b'
      }
    ];

    const activityList = document.getElementById('activity-list');
    if (activityList) {
      activityList.innerHTML = activities.map(activity => `
        <div class="activity-item">
          <div class="activity-icon" style="background-color: ${activity.color}">
            <i class="fas fa-${activity.icon}"></i>
          </div>
          <div class="activity-content">
            <div class="activity-title">${activity.title}</div>
            <div class="activity-description">${activity.description}</div>
            <div class="activity-time">${activity.time}</div>
          </div>
        </div>
      `).join('');
    }
  }

  // Mobile responsive handling
  handleMobileResize() {
    const isMobile = window.innerWidth <= 768;
    const sidebar = document.getElementById('sidebar');

    if (isMobile && sidebar) {
      sidebar.classList.add('collapsed');
    }
  }
}

// Auto-refresh functionality
class AutoRefresh {
  constructor(dashboardManager) {
    this.dashboardManager = dashboardManager;
    this.interval = null;
    this.refreshRate = 30000; // 30 seconds
  }

  start() {
    this.interval = setInterval(() => {
      if (this.dashboardManager.currentPage === 'dashboard') {
        this.dashboardManager.loadDashboardData();
        console.log('Dashboard auto-refreshed');
      }
    }, this.refreshRate);
  }

  stop() {
    if (this.interval) {
      clearInterval(this.interval);
      this.interval = null;
    }
  }
}

// Initialize dashboard when DOM is ready
let dashboardManager;
let autoRefresh;

document.addEventListener('DOMContentLoaded', () => {
  const path = window.location.pathname;
  const backendPaths = ['/dashboard','/data-management','/forum','/calendar','/dinas-status','/settings'];
  const initOnBackend = backendPaths.includes(path);
  if (path.includes('dashboard.html') || initOnBackend) {
    dashboardManager = new DashboardManager();
    const routeMap = {
      '/dashboard': 'dashboard',
      '/data-management': 'data-management',
      '/forum': 'forum',
      '/calendar': 'calendar',
      '/dinas-status': 'dinas-status',
      '/settings': 'settings',
    };
    const target = routeMap[path];
    if (target) {
      dashboardManager.switchPage(target);
    }

    // Start auto-refresh only on dashboard
    if (path === '/dashboard' || path.includes('dashboard.html')) {
      //autoRefresh = new AutoRefresh(dashboardManager);
      //autoRefresh.start();
    }

    // Handle window resize
    window.addEventListener('resize', Utils.debounce(() => {
      dashboardManager.handleMobileResize();
    }, 250));

    // Handle visibility change (pause auto-refresh when tab is not active)
    document.addEventListener('visibilitychange', () => {
      if (!autoRefresh) return;
      if (document.hidden) {
        autoRefresh.stop();
      } else {
        autoRefresh.start();
      }
    });

    console.log('Dashboard initialized successfully');
  }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
  if (autoRefresh) {
    autoRefresh.stop();
  }
});
