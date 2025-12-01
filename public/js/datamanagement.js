document.addEventListener('DOMContentLoaded', () => {
  const current = authHandler.getCurrentUser();
  const userName = document.getElementById('user-name');
  const sidebarUserName = document.getElementById('sidebar-user-name');
  const sidebarUserRole = document.getElementById('sidebar-user-role');
  if (current) {
    if (userName) userName.textContent = current.name;
    if (sidebarUserName) sidebarUserName.textContent = current.name;
    if (sidebarUserRole) sidebarUserRole.textContent = current.position;
  }

  UIComponents.renderNotifications();

  const storageKey = 'sipandu_dm_records';
  const loadRecords = () => { try { return JSON.parse(localStorage.getItem(storageKey)) || []; } catch { return []; } };
  const saveRecords = (data) => { localStorage.setItem(storageKey, JSON.stringify(data)); };
  let records = loadRecords();
  if (!records.length) {
    records = [
      { id: Date.now()+1, opd:'Perdagangan', category:'Harga', name:'Harga Bahan Pokok 2025', period:new Date().toLocaleString('id-ID',{month:'short',year:'numeric'}), status:'Approved', pic:'1111', priority:'Medium', progress:100, createdAt:new Date().toISOString(), files:[], schema:{} },
      { id: Date.now()+2, opd:'Perindustrian', category:'Produksi', name:'Output Industri 2025', period:new Date().toLocaleString('id-ID',{month:'short',year:'numeric'}), status:'In Review', pic:'1111', priority:'High', progress:60, createdAt:new Date().toISOString(), files:[], schema:{} },
      { id: Date.now()+3, opd:'Koperasi', category:'SDM', name:'Pelatihan Koperasi 2025', period:new Date().toLocaleString('id-ID',{month:'short',year:'numeric'}), status:'Pending', pic:'1111', priority:'Low', progress:0, createdAt:new Date().toISOString(), files:[], schema:{} },
      { id: Date.now()+4, opd:'Tanaman Pangan', category:'Produksi', name:'Produksi Padi 2025', period:new Date().toLocaleString('id-ID',{month:'short',year:'numeric'}), status:'Approved', pic:'1111', priority:'Medium', progress:90, createdAt:new Date().toISOString(), files:[], schema:{} },
      { id: Date.now()+5, opd:'DLH', category:'SDM', name:'Kualitas Udara 2025', period:new Date().toLocaleString('id-ID',{month:'short',year:'numeric'}), status:'In Review', pic:'1111', priority:'Medium', progress:55, createdAt:new Date().toISOString(), files:[], schema:{} }
    ];
    saveRecords(records);
  }

  const searchInput = document.getElementById('dm-search');
  const statusFilter = document.getElementById('dm-status-filter');
  const opdFilter = document.getElementById('dm-opd-filter');
  const priorityFilter = document.getElementById('dm-priority-filter');
  const table = document.getElementById('dm-table');
  const pageText = document.getElementById('dm-page-text');
  const prevPage = document.getElementById('dm-page-prev');
  const nextPage = document.getElementById('dm-page-next');
  
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
  const schemaBox = document.getElementById('dm-add-schema');
  const sumOPD = document.getElementById('dm-sum-opd');
  const sumCat = document.getElementById('dm-sum-category');
  const sumName = document.getElementById('dm-sum-name');
  const sumPeriod = document.getElementById('dm-sum-period');
  const sumPic = document.getElementById('dm-sum-pic');
  const sumFiles = document.getElementById('dm-sum-files');
  let editId = null;

  const fillOPD = (select) => {
    if (!select) return;
    const current = Array.from(select.options).map(o=>o.value);
    dinasData.forEach(d => { if (!current.includes(d.name)) { const opt = document.createElement('option'); opt.value = d.name; opt.textContent = d.name; select.appendChild(opt); } });
    if (select.id === 'dm-opd-filter') { select.value = ''; }
    if (window.refreshCustomSelect) window.refreshCustomSelect(select);
  };
  let page = 1; const pageSize = 10;
  const renderTable = (q = '', s = '', opd = '', pr = '') => {
    const list = records
      .map(r => ({...r, priority: r.priority || 'Medium'}))
      .filter(r => r.name.toLowerCase().includes(q.toLowerCase()))
      .filter(r => (s ? r.status === s : true))
      .filter(r => (opd ? r.opd === opd : true))
      .filter(r => (pr ? r.priority === pr : true));
    const total = list.length;
    const start = total ? (page - 1) * pageSize + 1 : 0;
    const end = Math.min(page * pageSize, total);
    const slice = list.slice((page - 1) * pageSize, (page - 1) * pageSize + pageSize);
    const rows = slice.map((r, idx) => {
      const progClass = r.progress >= 80 ? 'high' : (r.progress >= 40 ? 'medium' : 'low');
      return `
      <tr class="row-accent ${r.status==='Approved' ? 'accent-green' : (r.status==='In Review' ? 'accent-amber' : 'accent-red')}" data-id="${r.id}">
        <td>${start + idx}</td>
        <td>${r.opd}</td>
        <td>${r.name}</td>
        <td>${r.category}</td>
        <td>${r.period}</td>
        <td><span class="priority-badge ${r.priority ? r.priority.toLowerCase() : 'medium'}">${r.priority}</span></td>
        <td><span class="status-badge ${r.status==='Approved' ? 'status-complete' : (r.status==='In Review' ? 'status-progress' : 'status-pending')}">${r.status}</span></td>
        <td>
          <div class="progress-line"><div class="progress-line-fill ${progClass}" style="width:${r.progress}%"></div></div>
        </td>
        <td>${r.pic}</td>
        <td>${new Date(r.createdAt).toLocaleDateString('id-ID')}</td>
        <td>
          <button class="btn btn-outline btn-sm" data-act="edit" data-id="${r.id}" style="color:#2563eb"><i class="fas fa-pen"></i></button>
          <button class="btn btn-outline btn-sm" data-act="del" data-id="${r.id}" style="color:#ef4444"><i class="fas fa-trash"></i></button>
        </td>
      </tr>
    `}).join('');
    table.innerHTML = `<thead><tr><th>No</th><th>Dinas</th><th>Nama Data</th><th>Kategori</th><th>Periode</th><th>Priority</th><th>Status</th><th class="col-progress">Progress</th><th>PIC</th><th>Update</th><th class="col-actions">Aksi</th></tr></thead><tbody>${rows}</tbody>`;
    if (table) table.querySelectorAll('button[data-act]').forEach(b=>{
      b.onclick = () => {
        const id = b.dataset.id; const act = b.dataset.act;
        if (act === 'del') {
          Utils.confirm('Hapus data ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){
            if(!yes) return;
            records = records.filter(x=> x.id != id);
            saveRecords(records);
            renderTable(searchInput?.value || '', statusFilter?.value || '', opdFilter?.value || '', priorityFilter?.value || '');
            Utils.showToast('Data dihapus','success');
          });
          return;
        }
        const item = records.find(x=> x.id == id);
        if (!item) return;
        editId = id;
        addOPD.value = item.opd; addCategory.value = item.category; addName.value = item.name; addPeriod.value = item.period; addPic.value = item.pic;
        if (addStatus) addStatus.value = item.status;
        if (addPriority) addPriority.value = item.priority;
        if (addProgress) addProgress.value = item.progress;
        refreshPrev(); renderSummary(); renderSchema(addOPD.value);
        if (window.refreshCustomSelect) window.refreshCustomSelect(addOPD);
        toggleModal(true);
      };
    });
    const complete = records.filter(r=> r.status==='Approved').length;
    const progress = records.filter(r=> r.status==='In Review').length;
    const pending = records.filter(r=> r.status==='Pending').length;
    const setKpi = (id, value) => { const el = document.querySelector(`#${id} .kpi-value`); if (el) el.textContent = value; };
    setKpi('kpi-total', records.length); setKpi('kpi-complete', complete); setKpi('kpi-progress', progress); setKpi('kpi-pending', pending);
    const setDelta = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = `+${value}`; };
    setDelta('kpi-total-delta', 0); setDelta('kpi-complete-delta', 0); setDelta('kpi-progress-delta', 0); setDelta('kpi-pending-delta', 0);
    if (pageText) pageText.textContent = `Menampilkan ${start} - ${end} dari ${total} data`;
    if (prevPage) prevPage.disabled = page <= 1;
    if (nextPage) nextPage.disabled = page * pageSize >= total;
  };
  renderTable('', '', '', '');
  const debouncedRender = Utils.debounce((q, s, o, p) => renderTable(q, s, o, p), 200);
  if (searchInput) searchInput.oninput = (e) => { page = 1; debouncedRender(e.target.value, statusFilter?.value || '', opdFilter?.value || '', priorityFilter?.value || ''); };
  if (statusFilter) statusFilter.onchange = (e) => { page = 1; renderTable(searchInput?.value || '', e.target.value, opdFilter?.value || '', priorityFilter?.value || ''); };
  if (opdFilter) opdFilter.onchange = (e) => { page = 1; renderTable(searchInput?.value || '', statusFilter?.value || '', e.target.value, priorityFilter?.value || ''); };
  if (priorityFilter) priorityFilter.onchange = (e) => { page = 1; renderTable(searchInput?.value || '', statusFilter?.value || '', opdFilter?.value || '', e.target.value); };
  
  if (prevPage) prevPage.onclick = () => { if (page > 1) { page--; renderTable(searchInput?.value || '', statusFilter?.value || '', opdFilter?.value || '', priorityFilter?.value || ''); } };
  if (nextPage) nextPage.onclick = () => { page++; renderTable(searchInput?.value || '', statusFilter?.value || '', opdFilter?.value || '', priorityFilter?.value || ''); };

  if (backBtn) backBtn.onclick = () => { if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'dashboard.html'; } };

  fillOPD(addOPD);
  fillOPD(opdFilter);
  if (window.refreshCustomSelect) { window.refreshCustomSelect(addOPD); window.refreshCustomSelect(opdFilter); }
  addPeriod.value = new Date().toLocaleString('id-ID', { month: 'short', year: 'numeric' });

  const schemas = {
    'Perdagangan': { tables:[
      { title:'Kontribusi Sektor Perdagangan terhadap PDRB (HB) 2025-2029', years:[2025,2026,2027,2028,2029], rows:['PDRB Sektor Perdagangan HB','Total PDRB Harga berlaku','Kontribusi Sektor Perdagangan terhadap PDRB (HB)'] },
      { title:'Perkembangan Ekspor/Impor ADHB 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Nilai Ekspor ADHB','Nilai Impor ADHB','Nilai Ekspor Bersih ADHB'] }
    ] },
    'Koperasi': { title:'Perkembangan Perkoperasian 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Jumlah Koperasi Sehat','Jumlah Layanan Izin Usaha Simpan Pinjam','Jumlah Kesehatan KSP/USP yang dinilai','Jumlah Pendidikan dan Pelatihan Perkoperasian','Rasio Pertumbuhan Wirausaha Baru berskala mikro'] },
    'Tanaman Pangan': { title:'Tanaman Pangan 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Luas Panen (ha)','Produksi (ton)','Produktivitas (ku/ha)'], crops:['Padi','Jagung','Ubi Kayu','Ubi Jalar','Kacang Kedelai','Kacang Tanah','Kacang Hijau'] },
    'Perkebunan': { title:'Perkebunan 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Luas Tanam','Produksi','Produktivitas'] },
    'Ketapang': { title:'Ketapang 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Luas Panen','Produksi','Produktivitas'] },
    'Pariwisata': { title:'Pariwisata 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Kunjungan Wisatawan','Pendapatan Sektor Pariwisata','Jumlah Destinasi'] },
    'DLH': { title:'Lingkungan Hidup 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Kualitas Udara','Kualitas Air','Pengelolaan Sampah'] },
    'Perikanan': { title:'Perikanan 2019-2023', years:[2019,2020,2021,2022,2023], rows:['Produksi Tangkap','Produksi Budidaya','Nilai Ekspor'] }
  };

  const renderSchema = (opd) => {
    if (!schemaBox) return;
    const s = schemas[opd];
    if (!s) { schemaBox.innerHTML = '<div class="thread-meta">Tidak ada form khusus untuk OPD ini. Gunakan kolom umum di kiri.</div>'; return; }
    const renderTable = (title, years, rows, key='tbl') => {
      const thead = `<thead><tr><th>Uraian</th>${years.map(y=>`<th>${y}</th>`).join('')}</tr></thead>`;
      const tbody = rows.map((r,ri)=> `<tr><td>${r}</td>${years.map((y,yi)=>`<td><input type="text" data-schema-key="${key}|${ri}|${y}" class="form-control"/></td>`).join('')}</tr>`).join('');
      return `<div class="card" style="margin-bottom:12px"><div class="card-header"><h3>${title}</h3></div><div class="card-body"><div class="table-wrap"><table class="table table-compact">${thead}<tbody>${tbody}</tbody></table></div></div></div>`;
    };
    if (s.tables) {
      schemaBox.innerHTML = s.tables.map((t,i)=> renderTable(t.title, t.years, t.rows, `t${i}`)).join('');
      return;
    }
    if (s.crops) {
      const years = s.years; const base = renderTable(s.title, years, s.rows, 'base');
      const crops = s.crops.map((c,ci)=> renderTable(`${c}`, years, s.rows, `crop${ci}`)).join('');
      schemaBox.innerHTML = base + crops;
      return;
    }
    schemaBox.innerHTML = renderTable(s.title, s.years, s.rows, 'main');
  };

  const collectSchemaValues = () => {
    const out = {}; if (!schemaBox) return out;
    schemaBox.querySelectorAll('[data-schema-key]').forEach(inp => { out[inp.dataset.schemaKey] = inp.value; });
    return out;
  };

  const renderSummary = () => {
    if (sumOPD) sumOPD.textContent = addOPD.value || '-';
    if (sumCat) sumCat.textContent = addCategory.value || '-';
    if (sumName) sumName.textContent = addName.value || '-';
    if (sumPeriod) sumPeriod.textContent = addPeriod.value || '-';
    if (sumPic) sumPic.textContent = addPic.value || current.name || '-';
    if (sumFiles) sumFiles.textContent = `${tempFiles.length} file`;
  };

  const refreshPrev = () => {
    if (!prevBox) return;
    const prev = records.filter(r => r.opd === addOPD.value && r.category === addCategory.value).sort((a,b)=> new Date(b.createdAt) - new Date(a.createdAt))[0];
    prevBox.innerHTML = prev ? `
      <div class="file-item"><div><div class="thread-title">${prev.name}</div><div class="thread-meta">${prev.opd} • ${prev.category} • ${prev.period}</div></div><div class="file-meta"><span>${prev.status}</span><span>${prev.pic}</span><span>${prev.progress}%</span></div></div>
    ` : '';
  };
  addOPD.onchange = () => { refreshPrev(); renderSummary(); renderSchema(addOPD.value); };
  addCategory.onchange = () => { refreshPrev(); renderSummary(); };
  addName.oninput = renderSummary;
  addPeriod.oninput = renderSummary;
  addPic.oninput = renderSummary;

  const toggleModal = (show) => { if (modal) modal.style.display = show ? 'flex' : 'none'; };
  if (openBtn) openBtn.onclick = () => {
    fillOPD(addOPD);
    if (window.refreshCustomSelect) window.refreshCustomSelect(addOPD);
    toggleModal(true);
    refreshPrev();
    renderSummary();
    renderSchema(addOPD.value);
  };
  if (closeBtn) closeBtn.onclick = () => toggleModal(false);
  if (cancelBtn) cancelBtn.onclick = () => toggleModal(false);
  if (modal) modal.addEventListener('click', (e)=> { if (e.target === modal) toggleModal(false); });
  let tempFiles = [];
  const handleFiles = (files) => { tempFiles = Array.from(files).map(f=>({ name:f.name, size:f.size })); Utils.showToast(`${tempFiles.length} berkas dipilih`, 'success'); renderSummary(); };
  if (addSelectBtn) addSelectBtn.onclick = () => addFilesInput.click();
  if (addFilesInput) addFilesInput.onchange = (e)=> handleFiles(e.target.files);
  if (addDropzone) {
    ['dragenter','dragover'].forEach(ev => addDropzone.addEventListener(ev, (e) => { e.preventDefault(); addDropzone.classList.add('drag'); }));
    ['dragleave','drop'].forEach(ev => addDropzone.addEventListener(ev, (e) => { e.preventDefault(); addDropzone.classList.remove('drag'); }));
    addDropzone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files));
  }

  const addStatus = document.getElementById('dm-add-status');
  const addPriority = document.getElementById('dm-add-priority');
  const addProgress = document.getElementById('dm-add-progress');
  if (saveBtn) saveBtn.onclick = () => {
    if (!addOPD.value || !addCategory.value || !addName.value || !addPeriod.value) { Utils.showToast('Lengkapi data', 'error'); return; }
    const rec = {
      id: editId || Date.now(),
      opd: addOPD.value,
      category: addCategory.value,
      name: addName.value,
      period: addPeriod.value,
      status: addStatus?.value || 'Pending',
      pic: addPic.value || current.name,
      priority: addPriority?.value || 'Medium',
      progress: Number(addProgress?.value || 0),
      createdAt: new Date().toISOString(),
      files: tempFiles,
      schema: collectSchemaValues()
    };
    if (editId) { records = records.map(x=> x.id===editId ? { ...rec, createdAt: x.createdAt } : x); } else { records.unshift(rec); }
    saveRecords(records);
    page = 1;
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (opdFilter) opdFilter.value = '';
    if (priorityFilter) priorityFilter.value = '';
    if (window.refreshCustomSelect) window.refreshCustomSelect(opdFilter);
    renderTable('', '', '', '');
    Utils.showToast(editId ? 'Data diperbarui' : 'Data ditambahkan', 'success');
    toggleModal(false);
    editId = null;
    const newRow = table?.querySelector(`tbody tr[data-id="${rec.id}"]`);
    if (newRow) { newRow.classList.add('row-new'); newRow.scrollIntoView({block:'nearest'}); }
  };

  const dpmKey = 'sipandu_dm_dpmptsp';
  const dpmTable = document.getElementById('dpm-table');
  const dpmAdd = document.getElementById('dpm-add');
  const dpmModal = document.getElementById('dpm-modal');
  const dpmClose = document.getElementById('dpm-close');
  const dpmCancel = document.getElementById('dpm-cancel');
  const dpmSave = document.getElementById('dpm-save');
  const dpmInd = document.getElementById('dpm-indikator');
  const dpmTipe = document.getElementById('dpm-tipe');
  const dpmY25 = document.getElementById('dpm-y2025');
  const dpmY26 = document.getElementById('dpm-y2026');
  const dpmY27 = document.getElementById('dpm-y2027');
  const dpmY28 = document.getElementById('dpm-y2028');
  const dpmY29 = document.getElementById('dpm-y2029');
  let dpmEditId = null;
  const loadDpm = () => { try { return JSON.parse(localStorage.getItem(dpmKey)) || []; } catch { return []; } };
  const saveDpm = (data) => localStorage.setItem(dpmKey, JSON.stringify(data));
  let dpmRows = loadDpm();
  const renderDpm = () => {
    const rows = dpmRows.map(r=>`
      <tr>
        <td>${r.indikator}</td>
        <td><span class="tag">${r.tipe}</span></td>
        <td>${r.y2025||'-'}</td>
        <td>${r.y2026||'-'}</td>
        <td>${r.y2027||'-'}</td>
        <td>${r.y2028||'-'}</td>
        <td>${r.y2029||'-'}</td>
        <td><button class="btn btn-outline btn-sm" data-act="edit" data-id="${r.id}"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm" data-act="del" data-id="${r.id}" style="color:#ef4444"><i class="fas fa-trash"></i></button></td>
      </tr>
    `).join('');
    if (dpmTable) dpmTable.innerHTML = `<thead><tr><th>Indikator</th><th>Tipe</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Aksi</th></tr></thead><tbody>${rows}</tbody>`;
    if (dpmTable) dpmTable.querySelectorAll('button[data-act]').forEach(b=>{
      b.onclick = () => {
        const id=b.dataset.id; const act=b.dataset.act; const item=dpmRows.find(x=> x.id==id);
        if (!item) return;
        if (act==='del') { Utils.confirm('Hapus data DPMPTSP ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){ if(!yes) return; dpmRows = dpmRows.filter(x=> x.id!=id); saveDpm(dpmRows); renderDpm(); Utils.showToast('Data DPMPTSP dihapus','success'); }); return; }
        dpmEditId = id;
        dpmInd.value = item.indikator; dpmTipe.value = item.tipe;
        dpmY25.value = item.y2025||''; dpmY26.value = item.y2026||''; dpmY27.value = item.y2027||''; dpmY28.value = item.y2028||''; dpmY29.value = item.y2029||'';
        if (dpmModal) dpmModal.style.display='flex';
      };
    });
  };
  renderDpm();
  const toggleDpmModal = (show) => { if (dpmModal) dpmModal.style.display = show ? 'flex' : 'none'; };
  if (dpmAdd) dpmAdd.onclick = () => { dpmEditId=null; dpmInd.value=''; dpmTipe.value=''; dpmY25.value=''; dpmY26.value=''; dpmY27.value=''; dpmY28.value=''; dpmY29.value=''; toggleDpmModal(true); };
  if (dpmClose) dpmClose.onclick = () => toggleDpmModal(false);
  if (dpmCancel) dpmCancel.onclick = () => toggleDpmModal(false);
  if (dpmModal) dpmModal.addEventListener('click',(e)=>{ if(e.target===dpmModal) toggleDpmModal(false); });
  if (dpmSave) dpmSave.onclick = () => {
    const ind = dpmInd.value.trim(); if (!ind) { Utils.showToast('Isi indikator', 'error'); return; }
    if (!dpmTipe.value) { Utils.showToast('Pilih tipe data (PMDN/PMA)', 'error'); return; }
    const rec = {
      id: dpmEditId || Date.now(),
      indikator: ind,
      tipe: dpmTipe.value,
      y2025: dpmY25.value.trim(),
      y2026: dpmY26.value.trim(),
      y2027: dpmY27.value.trim(),
      y2028: dpmY28.value.trim(),
      y2029: dpmY29.value.trim()
    };
    if (dpmEditId) { dpmRows = dpmRows.map(x=> x.id===dpmEditId ? rec : x); } else { dpmRows.unshift(rec); }
    saveDpm(dpmRows); renderDpm(); toggleDpmModal(false); Utils.showToast('Data DPMPTSP disimpan', 'success');
  };

  const pdPdrbKey = 'sipandu_dm_perdagangan_pdrb';
  const pdEksKey = 'sipandu_dm_perdagangan_eks';
  const pdPdrbTable = document.getElementById('pd-pdrb-table');
  const pdEksTable = document.getElementById('pd-eks-table');
  const pdTabPdrb = document.getElementById('pd-tab-pdrb');
  const pdTabEks = document.getElementById('pd-tab-eks');
  const pdAddPdrb = document.getElementById('pd-add-pdrb');
  const pdAddEks = document.getElementById('pd-add-eks');
  const pdPdrbInline = document.getElementById('pd-pdrb-inline');
  const pdEksInline = document.getElementById('pd-eks-inline');
  const pdPdrbUra = document.getElementById('pd-pdrb-uraian');
  const pdExUra = document.getElementById('pd-eks-uraian');
  const pdPdrbCancel = document.getElementById('pd-pdrb-cancel');
  const pdPdrbSave = document.getElementById('pd-pdrb-save');
  const pdEksCancel = document.getElementById('pd-eks-cancel');
  const pdEksSave = document.getElementById('pd-eks-save');
  const getVal = (id) => document.getElementById(id)?.value?.trim() || '';
  const loadJson = (k) => { try { return JSON.parse(localStorage.getItem(k)) || []; } catch { return []; } };
  const saveJson = (k,d) => localStorage.setItem(k, JSON.stringify(d));
  let pdPdrbRows = loadJson(pdPdrbKey);
  let pdEksRows = loadJson(pdEksKey);
  const renderPdPdrb = () => {
    const rows = pdPdrbRows.map((r,i)=>`
      <tr>
        <td>${i+1}</td>
        <td>${r.uraian}</td>
        <td>${r.y2025||'-'}</td><td>${r.y2026||'-'}</td><td>${r.y2027||'-'}</td><td>${r.y2028||'-'}</td><td>${r.y2029||'-'}</td>
      </tr>
    `).join('');
    if (pdPdrbTable) pdPdrbTable.innerHTML = `<thead><tr><th>No.</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody>${rows}</tbody>`;
  };
  const renderPdEks = () => {
    const rows = pdEksRows.map((r,i)=>`
      <tr>
        <td>${i+1}</td>
        <td>${r.uraian}</td>
        <td>${r.y2019||'-'}</td><td>${r.y2020||'-'}</td><td>${r.y2021||'-'}</td><td>${r.y2022||'-'}</td><td>${r.y2023||'-'}</td>
      </tr>
    `).join('');
    if (pdEksTable) pdEksTable.innerHTML = `<thead><tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody>${rows}</tbody>`;
  };
  renderPdPdrb(); renderPdEks();
  const showPdrb = () => { if(pdPdrbTable){ pdPdrbTable.parentElement.style.display='block'; } if(pdEksTable){ pdEksTable.parentElement.style.display='none'; } };
  const showEks = () => { if(pdPdrbTable){ pdPdrbTable.parentElement.style.display='none'; } if(pdEksTable){ pdEksTable.parentElement.style.display='block'; } };
  if (pdTabPdrb) pdTabPdrb.onclick = showPdrb;
  if (pdTabEks) pdTabEks.onclick = showEks;
  showPdrb();
  const toggle = (el,show) => { if (el) el.style.display = show ? 'block' : 'none'; };
  if (pdAddPdrb) pdAddPdrb.onclick = () => { toggle(pdPdrbInline,true); toggle(pdEksInline,false); };
  if (pdAddEks) pdAddEks.onclick = () => { toggle(pdEksInline,true); toggle(pdPdrbInline,false); };
  if (pdPdrbCancel) pdPdrbCancel.onclick = () => toggle(pdPdrbInline,false);
  if (pdEksCancel) pdEksCancel.onclick = () => toggle(pdEksInline,false);
  if (pdPdrbSave) pdPdrbSave.onclick = () => {
    const row = { uraian: pdPdrbUra?.value?.trim()||'', y2025:getVal('pd-pdrb-2025'), y2026:getVal('pd-pdrb-2026'), y2027:getVal('pd-pdrb-2027'), y2028:getVal('pd-pdrb-2028'), y2029:getVal('pd-pdrb-2029') };
    if (!row.uraian) { Utils.showToast('Isi uraian', 'error'); return; }
    pdPdrbRows.unshift(row); saveJson(pdPdrbKey,pdPdrbRows); renderPdPdrb(); toggle(pdPdrbInline,false); Utils.showToast('Data Perdagangan disimpan', 'success');
  };
  if (pdEksSave) pdEksSave.onclick = () => {
    const row = { uraian: pdExUra?.value?.trim()||'', y2019:getVal('pd-eks-2019'), y2020:getVal('pd-eks-2020'), y2021:getVal('pd-eks-2021'), y2022:getVal('pd-eks-2022'), y2023:getVal('pd-eks-2023') };
    if (!row.uraian) { Utils.showToast('Isi uraian', 'error'); return; }
    pdEksRows.unshift(row); saveJson(pdEksKey,pdEksRows); renderPdEks(); toggle(pdEksInline,false); Utils.showToast('Data Perdagangan disimpan', 'success');
  };

  const setupPanel = (prefix, years, storageKey) => {
    const table = document.getElementById(`${prefix}-table`);
    const addBtn = document.getElementById(`${prefix}-add`);
    const inline = document.getElementById(`${prefix}-inline`);
    const ura = document.getElementById(`${prefix}-uraian`);
    const cancelBtn = document.getElementById(`${prefix}-cancel`);
    const saveBtn = document.getElementById(`${prefix}-save`);
    let rows = loadJson(storageKey);
    const render = () => {
      const headYears = years.map(y=>`<th>${y}</th>`).join('');
      const body = rows.map((r,i)=>`<tr><td>${i+1}</td><td>${r.uraian}</td>${years.map(y=>`<td>${r[`y${y}`]||'-'}</td>`).join('')}</tr>`).join('');
      if (table) table.innerHTML = `<thead><tr><th>No.</th><th>Uraian</th>${headYears}</tr></thead><tbody>${body}</tbody>`;
    };
    render();
    const toggleInline = (show) => { if (inline) inline.style.display = show ? 'block' : 'none'; };
    if (addBtn) addBtn.onclick = () => { toggleInline(true); };
    if (cancelBtn) cancelBtn.onclick = () => toggleInline(false);
    if (saveBtn) saveBtn.onclick = () => {
      const row = { uraian: ura?.value?.trim() || '' };
      if (!row.uraian) { Utils.showToast('Isi uraian', 'error'); return; }
      years.forEach(y=> { row[`y${y}`] = document.getElementById(`${prefix}-${y}`)?.value?.trim() || ''; });
      rows.unshift(row); saveJson(storageKey, rows); render(); toggleInline(false); Utils.showToast('Data disimpan', 'success');
    };
  };

  setupPanel('pkb',[2019,2020,2021,2022,2023],'sipandu_dm_perkebunan');
  setupPanel('ktp',[2019,2020,2021,2022,2023],'sipandu_dm_ketapang');
  setupPanel('pws',[2019,2020,2021,2022,2023],'sipandu_dm_pariwisata');
  setupPanel('dlh',[2019,2020,2021,2022,2023],'sipandu_dm_dlh');
  setupPanel('prk',[2019,2020,2021,2022,2023],'sipandu_dm_perikanan');

  const notifications = document.getElementById('notifications');
  const userMenu = document.getElementById('user-menu');
  if (notifications) notifications.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('notification-dropdown')?.classList.toggle('show'); document.getElementById('user-dropdown')?.classList.remove('show'); });
  if (userMenu) userMenu.addEventListener('click', (e) => { e.stopPropagation(); document.getElementById('user-dropdown')?.classList.toggle('show'); document.getElementById('notification-dropdown')?.classList.remove('show'); });
  document.addEventListener('click', () => { document.getElementById('notification-dropdown')?.classList.remove('show'); document.getElementById('user-dropdown')?.classList.remove('show'); });

  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) logoutBtn.onclick = (e) => { e.preventDefault(); authHandler.logout(); };
});
