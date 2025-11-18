document.addEventListener('DOMContentLoaded', () => {
  if (!authHandler.isAuthenticated()) { window.location.href = 'login.html'; return; }
  const current = authHandler.getCurrentUser();
  const userName = document.getElementById('user-name');
  const sidebarUserName = document.getElementById('sidebar-user-name');
  const sidebarUserRole = document.getElementById('sidebar-user-role');
  if (userName) userName.textContent = current.name;
  if (sidebarUserName) sidebarUserName.textContent = current.name;
  if (sidebarUserRole) sidebarUserRole.textContent = current.position;

  UIComponents.renderNotifications();

  const storageKey = 'sipandu_dm_records';
  const loadRecords = () => { try { return JSON.parse(localStorage.getItem(storageKey)) || []; } catch { return []; } };
  const saveRecords = (data) => { localStorage.setItem(storageKey, JSON.stringify(data)); };
  let records = loadRecords();

  const searchInput = document.getElementById('dm-search');
  const statusFilter = document.getElementById('dm-status-filter');
  const table = document.getElementById('dm-table');
  const backBtn = document.getElementById('dm-back');
  // Modal elements
  const modal = document.getElementById('dm-modal');
  const openBtn = document.getElementById('dm-add-open');
  const closeBtn = document.getElementById('dm-add-close');
  const cancelBtn = document.getElementById('dm-add-cancel');
  const saveBtn = document.getElementById('dm-add-save');
  const addOPD = document.getElementById('dm-add-opd');
  const addCategory = document.getElementById('dm-add-category');
  const addName = document.getElementById('dm-add-name');
  const addPeriod = document.getElementById('dm-add-period');
  const addPic = document.getElementById('dm-add-pic');
  const prevBox = document.getElementById('dm-prev-content');
  const addDropzone = document.getElementById('dm-add-dropzone');
  const addFilesInput = document.getElementById('dm-add-files');
  const addSelectBtn = document.getElementById('dm-add-select');

  const fillOPD = (select) => {
    if (!select) return; if (select.options.length > 1) return;
    dinasData.forEach(d => { const opt = document.createElement('option'); opt.value = d.name; opt.textContent = d.name; select.appendChild(opt); });
  };
  const renderTable = (q = '', s = '') => {
    const list = records
      .filter(r => r.name.toLowerCase().includes(q.toLowerCase()))
      .filter(r => (s ? r.status === s : true));
    const rows = list.map(r => `
      <tr>
        <td>${r.opd}</td>
        <td>${r.category}</td>
        <td>${r.name}</td>
        <td>${r.period}</td>
        <td>${r.status}</td>
        <td>
          <div class="progress-line"><div class="progress-line-fill" style="width:${r.progress}%; background:#2563eb"></div></div>
        </td>
        <td>${new Date(r.createdAt).toLocaleDateString('id-ID')}</td>
        <td>${r.pic}</td>
        <td><button class="btn btn-outline btn-sm" data-id="${r.id}">Detail</button></td>
      </tr>
    `).join('');
    table.innerHTML = `<thead><tr><th>Dinas</th><th>Kategori</th><th>Nama Data</th><th>Periode</th><th>Status</th><th class="col-progress">Progress</th><th>Tanggal Update</th><th>Penanggung Jawab</th><th class="col-actions">Aksi</th></tr></thead><tbody>${rows}</tbody>`;
    const total = records.length;
    const complete = records.filter(r=> r.status==='Approved').length;
    const progress = records.filter(r=> r.status==='In Review').length;
    const pending = records.filter(r=> r.status==='Pending').length;
    const setKpi = (id, value) => { const el = document.querySelector(`#${id} .kpi-value`); if (el) el.textContent = value; };
    setKpi('kpi-total', total); setKpi('kpi-complete', complete); setKpi('kpi-progress', progress); setKpi('kpi-pending', pending);
  };
  renderTable('', '');
  const debouncedRender = Utils.debounce((q, s) => renderTable(q, s), 200);
  if (searchInput) searchInput.oninput = (e) => debouncedRender(e.target.value, statusFilter?.value || '');
  if (statusFilter) statusFilter.onchange = (e) => renderTable(searchInput?.value || '', e.target.value);

  if (backBtn) backBtn.onclick = () => { if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'dashboard.html'; } };

  fillOPD(addOPD);
  addPeriod.value = new Date().toLocaleString('id-ID', { month: 'short', year: 'numeric' });

  const refreshPrev = () => {
    const prev = records.filter(r => r.opd === addOPD.value && r.category === addCategory.value).sort((a,b)=> new Date(b.createdAt) - new Date(a.createdAt))[0];
    prevBox.innerHTML = prev ? `
      <div class="file-item"><div><div class="thread-title">${prev.name}</div><div class="thread-meta">${prev.opd} • ${prev.category} • ${prev.period}</div></div><div class="file-meta"><span>${prev.status}</span><span>${prev.pic}</span><span>${prev.progress}%</span></div></div>
    ` : 'Tidak ada data sebelumnya';
  };
  addOPD.onchange = refreshPrev;
  addCategory.onchange = refreshPrev;

  const toggleModal = (show) => { if (modal) modal.style.display = show ? 'flex' : 'none'; };
  if (openBtn) openBtn.onclick = () => { toggleModal(true); refreshPrev(); };
  if (closeBtn) closeBtn.onclick = () => toggleModal(false);
  if (cancelBtn) cancelBtn.onclick = () => toggleModal(false);
  let tempFiles = [];
  const handleFiles = (files) => { tempFiles = Array.from(files).map(f=>({ name:f.name, size:f.size })); Utils.showToast(`${tempFiles.length} berkas dipilih`, 'success'); };
  if (addSelectBtn) addSelectBtn.onclick = () => addFilesInput.click();
  if (addFilesInput) addFilesInput.onchange = (e)=> handleFiles(e.target.files);
  if (addDropzone) {
    ['dragenter','dragover'].forEach(ev => addDropzone.addEventListener(ev, (e) => { e.preventDefault(); addDropzone.classList.add('drag'); }));
    ['dragleave','drop'].forEach(ev => addDropzone.addEventListener(ev, (e) => { e.preventDefault(); addDropzone.classList.remove('drag'); }));
    addDropzone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files));
  }

  if (saveBtn) saveBtn.onclick = () => {
    if (!addOPD.value || !addCategory.value || !addName.value || !addPeriod.value) { Utils.showToast('Lengkapi data', 'error'); return; }
    const rec = {
      id: Date.now(),
      opd: addOPD.value,
      category: addCategory.value,
      name: addName.value,
      period: addPeriod.value,
      status: 'Pending',
      pic: addPic.value || current.name,
      progress: 0,
      createdAt: new Date().toISOString(),
      files: tempFiles
    };
    records.unshift(rec);
    saveRecords(records);
    renderTable(searchInput?.value || '', statusFilter?.value || '');
    Utils.showToast('Data ditambahkan', 'success');
    toggleModal(false);
  };

  const notifications = document.getElementById('notifications');
  const userMenu = document.getElementById('user-menu');
  if (notifications) notifications.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('notification-dropdown')?.classList.toggle('show'); document.getElementById('user-dropdown')?.classList.remove('show'); });
  if (userMenu) userMenu.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('user-dropdown')?.classList.toggle('show'); document.getElementById('notification-dropdown')?.classList.remove('show'); });
  document.addEventListener('click', () => { document.getElementById('notification-dropdown')?.classList.remove('show'); document.getElementById('user-dropdown')?.classList.remove('show'); });

  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) logoutBtn.onclick = (e) => { e.preventDefault(); authHandler.logout(); };
});
