@extends('layouts.app')
@section('title', 'Pengaturan')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="settings-page">
        <div class="page-header"><h1>Pengaturan</h1><p>Preferensi akun dan aplikasi</p></div>
        <div class="settings-grid">
            <div class="card" id="profile-card">
              <div class="card-body">
                <div class="account-grid">
                  <div class="acc-left">
                    <div class="avatar-outer">
                      <div class="avatar-circle">
                        @php($pp = auth()->user()->profile_photo_path ?? null)
                        @if($pp)
                          <img src="{{ route('settings.photo.get', ['user' => auth()->id()]).('?v='.(optional(auth()->user()->updated_at)->timestamp ?? time())) }}" alt="Foto Profil" width="200" height="200" loading="lazy" onerror="this.onerror=null;this.src='https://via.placeholder.com/200x200.png?text=User'" />
                        @else
                          <img src="https://via.placeholder.com/200x200.png?text=User" alt="Foto Profil" width="200" height="200" />
                        @endif
                      </div>
                      <button class="upload-btn" type="button" id="profile-upload-trigger"><i class="fas fa-upload"></i></button>
                    </div>
                    <form method="POST" action="{{ route('settings.photo') }}" enctype="multipart/form-data" id="profile-photo-form" class="upload-form">
                      @csrf
                      <label class="file-picker solid">
                        <i class="fas fa-upload"></i>
                        <span>Pilih Foto</span>
                        <input type="file" name="photo" accept="image/jpeg,image/png" id="profile-photo-input" required />
                      </label>
                      <div class="hint">Format: JPG/PNG, max 5MB</div>
                    </form>
                  </div>
                  <div class="acc-right">
                    <div class="pr-header">Data Pribadi</div>
                    <div class="pr-field"><label>Nama Lengkap</label><input type="text" value="{{ auth()->user()->name ?? '-' }}" disabled /></div>
                    <div class="pr-field"><label>Email</label><input type="email" value="{{ auth()->user()->email ?? '-' }}" disabled /></div>
                    <div class="pr-field"><label>Jabatan</label><input type="text" value="{{ auth()->user()->position ?? (strtoupper(auth()->user()->role ?? 'USER')) }}" disabled /></div>
                    <div class="pr-actions"><button class="btn btn-primary" id="profile-save" type="submit" form="profile-photo-form">Simpan Perubahan</button></div>
                  </div>
                </div>
                @if(session('success'))
                  <div class="alert success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                  <div class="alert" style="margin-top:8px;color:#b91c1c;background:#fee2e2;border:1px solid #fecaca;border-radius:12px;padding:8px 12px">{{ session('error') }}</div>
                @endif
              </div>
            </div>

            @if(false)
            <div class="card" id="user-mgmt-card">
              <div class="card-header"><h3>Manajemen Akun</h3></div>
              <div class="card-body">
                <div class="um-actions">
                  <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="um-search" class="search-input" placeholder="Cari nama atau email" />
                  </div>
                  <button class="btn btn-primary" id="um-add">Tambah Akun</button>
                </div>
                <div class="table-wrap">
                  <table class="table" id="um-table">
                    <thead>
                      <tr class="um-table-title"><th colspan="5">Manajemen Akun</th></tr>
                      <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dinas</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
                <div id="um-success" class="alert success" style="display:none"></div>
                <div id="um-error" class="field-error"></div>
              </div>
              <div class="modal-overlay" id="um-create-overlay" style="display:none">
                <div class="modal">
                  <div class="modal-header">
                    <h3>Tambah Akun Baru</h3>
                    <button class="btn btn-outline btn-sm" id="um-create-close">✕</button>
                  </div>
                  <form id="um-create-form">
                    <div class="modal-body">
                      <div class="form-row">
                        <div class="form-col">
                          <label>Nama Akun</label>
                          <input type="text" id="um-create-name" placeholder="contoh: admin dinas DPMPTSP" required />
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-col">
                          <label>Email</label>
                          <input type="email" id="um-create-email" placeholder="contoh@email.com" required />
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-col">
                          <label>Role</label>
                          <select id="um-create-role" required>
                            <option value="">Pilih Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="admin_dinas">Admin Dinas</option>
                            <option value="user">User Dinas</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-row" id="um-create-dinas-row" style="display:none">
                        <div class="form-col">
                          <label>Dinas</label>
                          <select id="um-create-dinas">
                            <option value="">Pilih Dinas</option>
                            @php($allowedNames = [
                              'DPMPTSP',
                              'Dinas Perdagangan',
                              'Dinas Perindustrian',
                              'Dinas Koperasi dan UKM',
                              'Dinas Pertanian Tanaman Pangan',
                              'Dinas Perkebunan dan Peternakan',
                              'Dinas Perikanan',
                              'Dinas Ketahanan Pangan',
                              'Dinas Pariwisata',
                              'Dinas Lingkungan Hidup',
                              'Badan Pendapatan Daerah'
                            ])
                            @php($labelMap = [ 'DPMPTSP' => 'DPMPTSP - Penanaman Modal dan Pelayanan Terpadu' ])
                            @php($byName = \App\Models\Dinas::whereIn('nama_dinas', $allowedNames)->get()->keyBy('nama_dinas'))
                            @foreach($allowedNames as $nm)
                              @php($opt = $byName[$nm] ?? null)
                              <option value="{{ $opt ? $opt->id : '' }}">{{ $labelMap[$nm] ?? $nm }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-col">
                          <label>Password</label>
                          <input type="password" id="um-create-password" placeholder="Masukkan password" required />
                        </div>
                      </div>
                      <div id="um-create-error" class="field-error"></div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" id="um-create-cancel">Batal</button>
                      <button type="submit" class="btn btn-primary um-save"><i class="fas fa-plus"></i> Tambah Akun</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endif
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
        <div class="modal-overlay" id="logout-modal" style="display:none">
          <div class="modal">
            <div class="modal-header"><h3>Konfirmasi Logout</h3><button class="btn btn-outline btn-sm" id="lg-close">✕</button></div>
            <div class="modal-body"><p>Anda yakin ingin keluar dari sistem?</p><div id="lg-error" class="field-error"></div></div>
            <div class="modal-footer"><button class="btn btn-secondary" id="lg-cancel">Batal</button><button class="btn btn-danger" id="lg-confirm"><i class="fas fa-right-from-bracket"></i> Keluar</button></div>
          </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.settings-grid{display:grid;grid-template-columns:1fr;gap:20px}
.profile-row{display:flex;align-items:flex-start;gap:18px}
.profile-avatar{width:90px;height:90px;border:1px solid #cfe0ff;border-radius:50%;overflow:hidden;background:#fff}
.profile-avatar img{width:100%;height:100%;object-fit:cover;display:block;border-radius:50%}
.avatar-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#2563eb;background:#eff6ff}
.profile-form{display:flex;flex-direction:column;gap:10px;align-items:flex-start}
.file-picker{display:inline-flex;align-items:center;gap:8px;border:1px dashed #93c5fd;background:#f8fbff;color:#1e3a8a;border-radius:12px;padding:8px 12px;cursor:pointer}
.file-picker input{display:none}
.preview{margin-top:6px}
.preview img{width:160px;height:160px;border-radius:50%;border:1px solid #cfe0ff;object-fit:cover}
.hint{font-size:12px;color:#6b7280}
.alert.success{margin-top:8px;color:#14532d;background:#dcfce7;border:1px solid #86efac;border-radius:12px;padding:8px 12px}
.modal-overlay#logout-modal{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,0.25);z-index:60}
#logout-modal .modal{border-radius:16px;box-shadow:0 10px 30px rgba(2,44,34,0.12);border:1px solid #e5e7eb;background:#fff;width:420px;max-width:92vw}
#logout-modal .modal-header{padding:12px 16px;border-bottom:1px solid #e5e7eb}
#logout-modal .modal-body{padding:16px}
#logout-modal .modal-footer{padding:12px 16px;border-top:1px solid #e5e7eb;display:flex;gap:8px;justify-content:flex-end}
.field-error{color:#ef4444;font-size:12px;min-height:16px}
/* New account card */
.account-grid{display:grid;grid-template-columns:1fr 1.2fr;gap:20px;align-items:flex-start}
.avatar-outer{position:relative;width:220px}
.avatar-circle{width:200px;height:200px;border-radius:50%;overflow:hidden;border:1px solid #cfe0ff;background:#fff}
.avatar-circle img{width:100%;height:100%;object-fit:cover;display:block}
.upload-btn{position:absolute;bottom:10px;right:10px;width:40px;height:40px;border-radius:50%;border:none;background:#3b82f6;color:#fff;box-shadow:0 6px 16px rgba(59,130,246,0.3);cursor:pointer}
.upload-btn:hover{background:#2563eb}
.upload-form{margin-top:14px}
.file-picker.solid{display:flex;align-items:center;gap:8px;border:1px solid #cfe0ff;background:#fff;color:#1e3a8a;border-radius:12px;padding:10px 12px;cursor:pointer}
.file-picker.solid input{display:none}
.pr-header{font-size:18px;font-weight:600;color:#0f172a;margin-bottom:12px}
.pr-field{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
.pr-field input{background:#f5f7fb;border:1px solid #e5e7eb;border-radius:10px;padding:10px}
.pr-actions{display:flex;justify-content:flex-end;margin-top:8px}
.btn-primary#profile-save{background:#3b82f6;border-color:#3b82f6}
.btn-primary#profile-save:hover{background:#2563eb;border-color:#2563eb}
#user-mgmt-card .um-actions{display:flex;gap:12px;align-items:center;margin-bottom:16px;width:100%}
#user-mgmt-card .search-bar{flex:1;display:flex;align-items:center;gap:10px;background:#f0f6ff;border:1px solid #93c5fd;border-radius:12px;padding:10px 14px;color:#1e3a8a;height:44px}
#user-mgmt-card .search-bar .search-icon{color:#3b82f6}
#user-mgmt-card .search-bar .search-input{border:none;outline:none;background:transparent;width:100%;color:#1e3a8a}
#user-mgmt-card #um-add{height:44px;padding:0 16px}
#user-mgmt-card .search-bar .search-input::placeholder{color:#6b7280}
#user-mgmt-card .table-wrap{overflow:auto;border:1px solid #e5e7eb;border-radius:12px}
#user-mgmt-card table{width:100%;border-collapse:collapse}
#user-mgmt-card th,#user-mgmt-card td{padding:10px;border-bottom:1px solid #eef2f7;text-align:left}
#user-mgmt-card .thead th{background:#f8fbff;color:#1e3a8a}
#user-mgmt-card .um-table-title th{font-size:18px;font-weight:600;color:#1e3a8a;padding:15px 10px;text-align:center;background:#eff6ff}
#user-mgmt-card .btn-sm{padding:6px 10px;font-size:12px;border-radius:8px}
#user-mgmt-card .modal-overlay{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.25);backdrop-filter:blur(3px);z-index:60}
#user-mgmt-card .modal{border-radius:12px;box-shadow:0 20px 40px rgba(15,23,42,0.18);border:1px solid #e2e8f0;background:#fff;width:512px;max-width:92vw}
#user-mgmt-card .modal-header{padding:24px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center}
#user-mgmt-card .modal-header h3{font-size:16px;font-weight:600;color:#0f172a}
#user-mgmt-card .modal-header .btn{padding:8px;border-radius:8px}
#user-mgmt-card .modal-header .btn:hover{background:#f1f5f9}
#user-mgmt-card .modal-body{padding:24px;display:flex;flex-direction:column;gap:16px}
#user-mgmt-card .modal-footer{padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;gap:12px;justify-content:flex-end}
#user-mgmt-card .form-row{display:block;width:100%;margin-bottom:16px}
#user-mgmt-card .form-col{display:flex;flex-direction:column;gap:8px;width:100%}
#user-mgmt-card .form-col label{font-size:14px;color:#334155;font-weight:500;margin-bottom:8px;width:100%}
#user-mgmt-card .form-col input,#user-mgmt-card .form-col select{height:44px;padding:12px 16px;border:1px solid #d1d5db;border-radius:8px;width:100%;background:#fff;box-sizing:border-box;font-size:14px}
#user-mgmt-card .form-col input::placeholder{color:#9ca3af}
#user-mgmt-card .form-col input:focus,#user-mgmt-card .form-col select:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.15);outline:none}
.btn-primary.um-save{height:40px;padding:0 24px;background:#2563eb;border-color:#2563eb;border-radius:8px;color:#fff;box-shadow:0 6px 16px rgba(37,99,235,0.25)}
.btn-primary.um-save:hover{background:#1d4ed8;border-color:#1d4ed8}
#user-mgmt-card .btn.btn-secondary{height:40px;padding:0 24px;background:#fff;border:1px solid #cbd5e1;color:#334155;border-radius:8px}
#user-mgmt-card .btn.btn-secondary:hover{background:#f8fafc}
@media (max-width: 768px){
  .settings-grid{grid-template-columns:1fr}
  #user-mgmt-card .modal{width:95vw;max-width:95vw;margin:0 10px}
  #user-mgmt-card .form-row{margin-bottom:12px}
  #user-mgmt-card .form-col input,#user-mgmt-card .form-col select{height:42px;padding:10px 14px}
}
</style>
@endpush

@php($dinMap = \App\Models\Dinas::whereIn('nama_dinas', [
  'DPMPTSP','Dinas Perdagangan','Dinas Perindustrian','Dinas Koperasi dan UKM','Dinas Pertanian Tanaman Pangan','Dinas Perkebunan dan Peternakan','Dinas Perikanan','Dinas Ketahanan Pangan','Dinas Pariwisata','Dinas Lingkungan Hidup','Badan Pendapatan Daerah'
])->get()->mapWithKeys(function($d){ return [$d->nama_dinas => $d->id]; })->toArray())

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  var input=document.getElementById('profile-photo-input');
  var avatar=document.querySelector('#profile-card .avatar-circle img');
  var trigger=document.getElementById('profile-upload-trigger');
  if(trigger){ trigger.addEventListener('click',function(){ if(input){ input.click(); } }); }
  if(input){ input.addEventListener('change',function(){ var f=input.files&&input.files[0]; if(!f){return;} var url=URL.createObjectURL(f); if(avatar){ avatar.src=url; } }); }

  // Logout modal
  var lgModal=document.getElementById('logout-modal');
  var lgOpen=document.getElementById('acc-logout');
  var lgClose=document.getElementById('lg-close');
  var lgCancel=document.getElementById('lg-cancel');
  var lgConfirm=document.getElementById('lg-confirm');
  var lgError=document.getElementById('lg-error');
  function openLg(){lgError.textContent='';lgModal.style.display='flex'}
  function closeLg(){lgModal.style.display='none'}
  if(lgOpen){ lgOpen.addEventListener('click',function(){var uid=document.body.dataset.userId||'';if(!uid){lgError.textContent='Session tidak valid. Silakan login ulang.';openLg();return;}openLg()}); }
  if(lgClose){ lgClose.addEventListener('click',closeLg); }
  if(lgCancel){ lgCancel.addEventListener('click',closeLg); }
  if(lgConfirm){ lgConfirm.addEventListener('click',function(){ var lf=document.getElementById('logout-form'); if(lf){ lf.submit(); } }); }

  var role = document.body.dataset.userRole || '';
  if(false && role==='super_admin'){
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var tblBody = document.querySelector('#um-table tbody');
    var search = document.getElementById('um-search');
    var addBtn = document.getElementById('um-add');
    var err = document.getElementById('um-error');
    var editModal = buildUserModal('Ubah Akun');
    var umc=document.getElementById('user-mgmt-card'); if(umc){ umc.appendChild(editModal.overlay); }
    var createOverlay = document.getElementById('um-create-overlay');
    var createClose = document.getElementById('um-create-close');
    var createForm = document.getElementById('um-create-form');
    var iName = document.getElementById('um-create-name');
    var iEmail = document.getElementById('um-create-email');
    var sRole = document.getElementById('um-create-role');
    var dinasRow = document.getElementById('um-create-dinas-row');
    var sDinasSelect = document.getElementById('um-create-dinas');
    var iPwd = document.getElementById('um-create-password');
    var errBox = document.getElementById('um-create-error');
    var saveBtn = createForm ? createForm.querySelector('.um-save') : null;
    var allowedDinas=[
      { name:'DPMPTSP', label:'DPMPTSP - Penanaman Modal dan Pelayanan Terpadu' },
      { name:'Dinas Perdagangan', label:'Dinas Perdagangan' },
      { name:'Dinas Perindustrian', label:'Dinas Perindustrian' },
      { name:'Dinas Koperasi dan UKM', label:'Dinas Koperasi dan UKM' },
      { name:'Dinas Pertanian Tanaman Pangan', label:'Dinas Pertanian Tanaman Pangan' },
      { name:'Dinas Perkebunan dan Peternakan', label:'Dinas Perkebunan dan Peternakan' },
      { name:'Dinas Perikanan', label:'Dinas Perikanan' },
      { name:'Dinas Ketahanan Pangan', label:'Dinas Ketahanan Pangan' },
      { name:'Dinas Pariwisata', label:'Dinas Pariwisata' },
      { name:'Dinas Lingkungan Hidup', label:'Dinas Lingkungan Hidup' },
      { name:'Badan Pendapatan Daerah', label:'Badan Pendapatan Daerah' }
    ];
    var DINAS_MAP = @json($dinMap);
    window.DINAS_MAP = DINAS_MAP;
    function setDinasOptions(list){
      var byName = {};
      list.forEach(function(d){ var n=(d.nama_dinas||'').trim(); byName[n]=d; });
      sDinasSelect.innerHTML='';
      var placeholder=document.createElement('option');placeholder.value='';placeholder.textContent='Pilih Dinas';sDinasSelect.appendChild(placeholder);
      allowedDinas.forEach(function(item){
        var opt=document.createElement('option');
        var idFromMap = DINAS_MAP[item.name] || null;
        var idFromApi = (byName[item.name] && byName[item.name].id) ? byName[item.name].id : null;
        var finalId = idFromMap || idFromApi || '';
        opt.value = finalId ? String(finalId) : '';
        opt.textContent = item.label;
        opt.dataset.name = item.name;
        sDinasSelect.appendChild(opt);
      });
    }
    function refreshDinas(){
      fetch('/forum/dinas',{headers:{'Accept':'application/json'}}).then(function(r){return r.json();}).then(setDinasOptions);
    }
    function toggleDinas(){
      var need = (sRole.value==='admin_dinas' || sRole.value==='user');
      sDinasSelect.required = need;
      dinasRow.style.display = need ? 'block' : 'none';
      validateCreateForm();
    }
    if(sRole){ sRole.addEventListener('change',toggleDinas); }
    function openCreate(){
      errBox.textContent='';
      createForm.reset();
      dinasRow.style.display='none';
      sDinasSelect.required=false;
      createOverlay.style.display='flex';
      refreshDinas();
      validateCreateForm();
    }
    function closeCreate(){
      createOverlay.style.display='none';
      errBox.textContent='';
    }
    if(createClose){ createClose.addEventListener('click',function(){closeCreate();}); }
    var cc=document.getElementById('um-create-cancel'); if(cc){ cc.addEventListener('click',function(e){e.preventDefault();closeCreate();}); }
    function getSelectedDinasId(){
      var val = sDinasSelect.value;
      if(val){ var pid = parseInt(val,10); if(!isNaN(pid) && pid>0) return pid; }
      var idx = sDinasSelect.selectedIndex;
      var opt = sDinasSelect.options[idx];
      var name = opt ? (opt.dataset.name || '') : '';
      var idFromMap = name ? (DINAS_MAP[name] || 0) : 0;
      return idFromMap;
    }
    function validateCreateForm(){
      var nameOk = (iName.value.trim().length>0);
      var emailOk = (/^\S+@\S+\.\S+$/.test(iEmail.value.trim()));
      var roleVal = sRole.value;
      var need = (roleVal==='admin_dinas' || roleVal==='user');
      var dinasOk = (!need) || (!!getSelectedDinasId());
      var pwdOk = (iPwd.value.trim().length>=6);
      if(saveBtn){ saveBtn.disabled = !(nameOk && emailOk && dinasOk && pwdOk); }
    }
    ;[iName,iEmail,sRole,sDinasSelect,iPwd].forEach(function(el){ if(el){ el.addEventListener('input',validateCreateForm); el.addEventListener('change',validateCreateForm); } });
    if(createForm){ createForm.addEventListener('submit',function(e){
      e.preventDefault();
      var emailVal=iEmail.value.trim();var nameVal=iName.value.trim();
      var need = (sRole.value==='admin_dinas' || sRole.value==='user');
      if(!nameVal){errBox.textContent='Nama wajib diisi';return;}
      if(!emailVal || !/^\S+@\S+\.\S+$/.test(emailVal)){errBox.textContent='Format email tidak valid';return;}
      var did = 0;
      if(need){
        var val = sDinasSelect.value;
        if(val){ var pid = parseInt(val,10); if(!isNaN(pid)) did = pid; }
        if(!did){
          var idx = sDinasSelect.selectedIndex;
          var opt = sDinasSelect.options[idx];
          var name = opt ? (opt.dataset.name || '') : '';
          did = name ? (DINAS_MAP[name] || 0) : 0;
        }
        if(!did){ errBox.textContent='Silakan pilih dinas'; return; }
      }
      var payload={name:nameVal,email:emailVal,role:sRole.value,password:iPwd.value.trim()};
      if(need){ payload.dinas_id = did; }
      fetch('/settings/users',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)}).then(async function(r){
        var data = await r.json().catch(function(){return null});
        if(!r.ok){
          if(r.status===422 && data && data.errors){
            var emailErr = data.errors.email;
            if(typeof emailErr === 'string' && emailErr){errBox.textContent=emailErr;return;}
            if(Array.isArray(emailErr) && emailErr.length){errBox.textContent=emailErr[0];return;}
            var dinasErr = data.errors.dinas_id;
            if(typeof dinasErr === 'string' && dinasErr){errBox.textContent='Dinas wajib diisi';return;}
            if(Array.isArray(dinasErr) && dinasErr.length){errBox.textContent='Dinas wajib diisi';return;}
          }
          var code = data && data.error ? data.error : '';
          if(code==='db_connection_failed'){ errBox.textContent='Koneksi database gagal'; return; }
          if(code==='missing_table_users'){ errBox.textContent='Tabel users tidak ditemukan'; return; }
          if(code==='missing_columns'){ errBox.textContent='Skema users tidak lengkap'; return; }
          if(code==='dinas_required'){ errBox.textContent='Dinas wajib diisi'; return; }
          if(code==='db_write_failed'){ errBox.textContent='Gagal menyimpan ke database'; return; }
          errBox.textContent='Gagal menyimpan';return;
        }
        closeCreate();
        var suc=document.getElementById('um-success');if(suc){suc.textContent='Akun berhasil disimpan';suc.style.display='block';setTimeout(function(){suc.style.display='none';},3000);} 
        fetchUsers(search.value||'');
      }).catch(function(){errBox.textContent='Gagal menyimpan';});
    }); }

    function fetchUsers(q){
      err.textContent='';
      var url = '/settings/users'+(q&&q.trim()!==''?('?q='+encodeURIComponent(q.trim())):'');
      fetch(url,{headers:{'Accept':'application/json'}}).then(function(r){return r.json();}).then(renderRows).catch(function(){err.textContent='Gagal memuat data akun';});
    }
    function renderRows(rows){
      tblBody.innerHTML='';
      rows.forEach(function(r){
        var tr=document.createElement('tr');
        tr.innerHTML = '<td>'+escapeHtml(r.name||'')+'</td>'+
                       '<td>'+escapeHtml(r.email||'')+'</td>'+
                       '<td>'+escapeHtml(r.role||'')+'</td>'+
                       '<td>'+escapeHtml(r.dinas_nama||'-')+'</td>'+
                       '<td>'+
                       '<button class="btn btn-secondary btn-sm" data-edit="'+r.id+'" title="Edit"><i class="fas fa-pencil-alt"></i></button> '+
                       '<button class="btn btn-danger btn-sm" data-del="'+r.id+'" title="Hapus"><i class="fas fa-trash"></i></button>'+
                       '</td>';
        tblBody.appendChild(tr);
      });
      tblBody.querySelectorAll('button[data-edit]').forEach(function(b){b.addEventListener('click',function(){openEdit(parseInt(b.getAttribute('data-edit'),10));});});
      tblBody.querySelectorAll('button[data-del]').forEach(function(b){b.addEventListener('click',function(){delUser(parseInt(b.getAttribute('data-del'),10));});});
    }
    function openAdd(){
      openCreate();
    }
    function openEdit(id){
      var row = Array.from(tblBody.querySelectorAll('tr')).map(function(tr){
        var tds = tr.querySelectorAll('td');
        var email = (tds[1] && tds[1].textContent) ? tds[1].textContent : '';
        var matchBtn = tr.querySelector('button[data-edit]');
        var matchId = matchBtn ? parseInt(matchBtn.getAttribute('data-edit'),10) : 0;
        return {id:matchId,name:(tds[0] && tds[0].textContent) ? tds[0].textContent : '',email:email,role:(tds[2] && tds[2].textContent) ? tds[2].textContent : '',dinas_nama:(tds[3] && tds[3].textContent) ? tds[3].textContent : ''};
      }).find(function(r){return r.id===id;});
      if(!row) return;
      editModal.setData(row);
      editModal.open();
    }
    function delUser(id){
      if(!confirm('Hapus akun ini?')) return;
      fetch('/settings/users/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin'}).then(function(r){return r.json();}).then(function(){fetchUsers(search.value||'');}).catch(function(){err.textContent='Gagal menghapus akun';});
    }
    function buildUserModal(title){
      var overlay=document.createElement('div');overlay.className='modal-overlay';
      var modal=document.createElement('div');modal.className='modal';
      var header=document.createElement('div');header.className='modal-header';
      var h=document.createElement('h3');h.textContent=title;var x=document.createElement('button');x.className='btn btn-outline btn-sm';x.textContent='✕';
      header.appendChild(h);header.appendChild(x);
      var body=document.createElement('div');body.className='modal-body';
      var rowName=document.createElement('div');rowName.className='form-row';
      var cName=document.createElement('div');cName.className='form-col';var lName=document.createElement('label');lName.textContent='Nama Akun';var iName=document.createElement('input');iName.placeholder='contoh: admin dinas DPMPTSP';iName.required=true;cName.appendChild(lName);cName.appendChild(iName);
      rowName.appendChild(cName);
      var rowEmail=document.createElement('div');rowEmail.className='form-row';
      var cEmail=document.createElement('div');cEmail.className='form-col';var lEmail=document.createElement('label');lEmail.textContent='Email';var iEmail=document.createElement('input');iEmail.placeholder='contoh@email.com';iEmail.type='email';iEmail.required=true;cEmail.appendChild(lEmail);cEmail.appendChild(iEmail);
      rowEmail.appendChild(cEmail);
      var rowRole=document.createElement('div');rowRole.className='form-row';
      var cRole=document.createElement('div');cRole.className='form-col';var lRole=document.createElement('label');lRole.textContent='Role';var sRole=document.createElement('select');
      var rolePlaceholder=document.createElement('option');rolePlaceholder.value='';rolePlaceholder.textContent='Pilih Role';sRole.appendChild(rolePlaceholder);sRole.required=true;
      [{v:'super_admin',t:'Super Admin'},{v:'admin_dinas',t:'Admin Dinas'},{v:'user',t:'User Dinas'}].forEach(function(opt){var o=document.createElement('option');o.value=opt.v;o.textContent=opt.t;sRole.appendChild(o);});
      cRole.appendChild(lRole);cRole.appendChild(sRole);
      rowRole.appendChild(cRole);
      var row3=document.createElement('div');row3.className='form-row';
      var c4=document.createElement('div');c4.className='form-col';var lDinas=document.createElement('label');lDinas.textContent='Dinas';var sDinasSelect=document.createElement('select');c4.appendChild(lDinas);c4.appendChild(sDinasSelect);
      row3.appendChild(c4);
      var row4=document.createElement('div');row4.className='form-row';
      var c6=document.createElement('div');c6.className='form-col';var lPwd=document.createElement('label');lPwd.textContent='Password';
      var iPwd=document.createElement('input');iPwd.placeholder='Masukkan password';iPwd.type='password';
      c6.appendChild(lPwd);c6.appendChild(iPwd);
      row4.appendChild(c6);
      var errBox=document.createElement('div');errBox.className='field-error';
      body.appendChild(rowName);body.appendChild(rowEmail);body.appendChild(rowRole);body.appendChild(row3);body.appendChild(row4);body.appendChild(errBox);
      var footer=document.createElement('div');footer.className='modal-footer';
      var btnCancel=document.createElement('button');btnCancel.className='btn btn-secondary';btnCancel.textContent='Batal';
      var btnSave=document.createElement('button');btnSave.className='btn btn-primary um-save';btnSave.innerHTML=(title.toLowerCase().indexOf('tambah')>-1?'<i class="fas fa-plus"></i> Tambah Akun':'Simpan');
      footer.appendChild(btnCancel);footer.appendChild(btnSave);
      modal.appendChild(header);modal.appendChild(body);modal.appendChild(footer);
      overlay.appendChild(modal);
      var dinasCache=[];var allowedDinas=[
        { name:'DPMPTSP', label:'DPMPTSP - Penanaman Modal dan Pelayanan Terpadu' },
        { name:'Dinas Perdagangan', label:'Dinas Perdagangan' },
        { name:'Dinas Perindustrian', label:'Dinas Perindustrian' },
        { name:'Dinas Koperasi dan UKM', label:'Dinas Koperasi dan UKM' },
        { name:'Dinas Pertanian Tanaman Pangan', label:'Dinas Pertanian Tanaman Pangan' },
        { name:'Dinas Perkebunan dan Peternakan', label:'Dinas Perkebunan dan Peternakan' },
        { name:'Dinas Perikanan', label:'Dinas Perikanan' },
        { name:'Dinas Ketahanan Pangan', label:'Dinas Ketahanan Pangan' },
        { name:'Dinas Pariwisata', label:'Dinas Pariwisata' },
        { name:'Dinas Lingkungan Hidup', label:'Dinas Lingkungan Hidup' },
        { name:'Badan Pendapatan Daerah', label:'Badan Pendapatan Daerah' }
      ];
      var pendingDinasName = '';
      function setDinasOptions(list){
        var byName = {};
        list.forEach(function(d){ var n=(d.nama_dinas||'').trim(); byName[n]=d; });
        dinasCache = list;
        sDinasSelect.innerHTML='';
        var placeholder=document.createElement('option');placeholder.value='';placeholder.textContent='Pilih Dinas';sDinasSelect.appendChild(placeholder);
        allowedDinas.forEach(function(item){
          var opt=document.createElement('option');
          var idFromMap = window.DINAS_MAP ? (window.DINAS_MAP[item.name] || null) : null;
          var idFromApi = (byName[item.name] && byName[item.name].id) ? byName[item.name].id : null;
          var finalId = idFromMap || idFromApi || '';
          opt.value = finalId ? String(finalId) : '';
          opt.textContent = item.label;
          opt.dataset.name = item.name;
          sDinasSelect.appendChild(opt);
        });
        if(pendingDinasName){
          var idx = Array.from(sDinasSelect.options).findIndex(function(o){return (o.dataset.name||'')===pendingDinasName;});
          if(idx>=0){ sDinasSelect.selectedIndex = idx; }
          pendingDinasName = '';
        }
        if(window.refreshCustomSelect){ try{ window.refreshCustomSelect(sDinasSelect); }catch(e){} }
      }
      function refreshDinas(){
        fetch('/forum/dinas',{headers:{'Accept':'application/json'}}).then(function(r){return r.json();}).then(setDinasOptions);
      }
      function open(){overlay.style.display='flex';refreshDinas();}
      function close(){overlay.style.display='none';errBox.textContent='';}
      x.addEventListener('click',close);btnCancel.addEventListener('click',function(e){e.preventDefault();close();});
      function toggleDinas(){var need = (sRole.value==='admin_dinas' || sRole.value==='user');sDinasSelect.required = need;c4.style.display = need ? 'flex' : 'none';}
      sRole.addEventListener('change',toggleDinas);
      c4.style.display='none';
      function setData(d){
        iName.value=d.name||'';iEmail.value=d.email||'';sRole.value=d.role||'';
        pendingDinasName = d.dinas_nama||'';
        var idByName = (window.DINAS_MAP && d.dinas_nama) ? (window.DINAS_MAP[d.dinas_nama] || '') : '';
        sDinasSelect.value = idByName ? String(idByName) : (d.dinas_id?String(d.dinas_id):'');
        iPwd.value='';toggleDinas();
      }
      function save(isEdit, id){
        var emailVal=iEmail.value.trim();var nameVal=iName.value.trim();
        var need = (sRole.value==='admin_dinas' || sRole.value==='user');
        if(!nameVal){errBox.textContent='Nama wajib diisi';return;}
        if(!emailVal || !/^\S+@\S+\.\S+$/.test(emailVal)){errBox.textContent='Format email tidak valid';return;}
        var did = 0;
        if(need){
          var val = sDinasSelect.value;
          if(val){ var pid = parseInt(val,10); if(!isNaN(pid)) did = pid; }
          if(!did){
            var idx = sDinasSelect.selectedIndex;
            var opt = sDinasSelect.options[idx];
            var name = opt ? (opt.dataset.name || '') : '';
            did = name && window.DINAS_MAP ? (window.DINAS_MAP[name] || 0) : 0;
          }
          if(!did){ errBox.textContent='Silakan pilih dinas'; return; }
        }
        var payload={name:nameVal,email:emailVal,role:sRole.value};
        var pwdVal=iPwd.value.trim(); if(pwdVal.length>=6){ payload.password = pwdVal; }
        if(need){ payload.dinas_id = did; }
        var url=isEdit?('/settings/users/'+id):'/settings/users';
        var method=isEdit?'PUT':'POST';
        fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)}).then(async function(r){
          var data = await r.json().catch(function(){return null});
          if(!r.ok){
            if(r.status===422 && data && data.errors){
              var emailErr = data.errors.email;
              if(typeof emailErr === 'string' && emailErr){errBox.textContent=emailErr;return;}
              if(Array.isArray(emailErr) && emailErr.length){errBox.textContent=emailErr[0];return;}
              var dinasErr = data.errors.dinas_id;
              if(typeof dinasErr === 'string' && dinasErr){errBox.textContent='Dinas wajib diisi';return;}
              if(Array.isArray(dinasErr) && dinasErr.length){errBox.textContent='Dinas wajib diisi';return;}
            }
            var code = data && data.error ? data.error : '';
            if(code==='db_connection_failed'){ errBox.textContent='Koneksi database gagal'; return; }
            if(code==='missing_table_users'){ errBox.textContent='Tabel users tidak ditemukan'; return; }
            if(code==='missing_columns'){ errBox.textContent='Skema users tidak lengkap'; return; }
            if(code==='dinas_required'){ errBox.textContent='Dinas wajib diisi'; return; }
            if(code==='db_write_failed'){ errBox.textContent='Gagal menyimpan ke database'; return; }
            errBox.textContent='Gagal menyimpan';return;
          }
          close();
          var suc=document.getElementById('um-success');if(suc){suc.textContent='Akun berhasil disimpan';suc.style.display='block';setTimeout(function(){suc.style.display='none';},3000);} 
          fetchUsers(search.value||'');
        }).catch(function(){errBox.textContent='Gagal menyimpan';});
      }
      btnSave.addEventListener('click',function(e){e.preventDefault();var isEdit=btnSave.dataset.edit==='1';var id=parseInt(btnSave.dataset.id||'0',10);save(isEdit,id);});
      return {
        overlay:overlay,
        open:open,
        close:close,
        setData:function(d){setData(d);btnSave.dataset.edit = d && d.id ? '1' : '0';btnSave.dataset.id = d && d.id ? String(d.id) : '';},
      };
    }
    function escapeHtml(s){var d=document.createElement('div');d.textContent=s||'';return d.innerHTML;}
    if(addBtn){ addBtn.addEventListener('click',openCreate); }
    if(search){ search.addEventListener('input',function(){fetchUsers(search.value||'');}); }
    fetchUsers('');
  }
});
</script>
@endpush
