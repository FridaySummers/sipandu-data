@extends('layouts.app')
@section('title', 'Agenda & Kalender')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="calendar-page">
        <div class="page-header">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
                <div>
                    <h1>Agenda & Kalender</h1>
                    <p>Kelola jadwal dan event perencanaan</p>
                </div>
                <div><button class="btn btn-primary" id="cal-add"><i class="fas fa-plus"></i> Tambah Event</button></div>
            </div>
        </div>

        <div class="calendar-kpi">
            <div class="kpi-card"><div class="kpi-icon info"><i class="fas fa-calendar-alt"></i></div><div><div class="kpi-value" id="cal-kpi-events">0</div><div class="kpi-label">Event Bulan Ini</div></div></div>
            <div class="kpi-card"><div class="kpi-icon success"><i class="fas fa-user-friends"></i></div><div><div class="kpi-value" id="cal-kpi-meetings">0</div><div class="kpi-label">Meeting</div></div></div>
            <div class="kpi-card"><div class="kpi-icon warning"><i class="fas fa-clock"></i></div><div><div class="kpi-value" id="cal-kpi-deadlines">0</div><div class="kpi-label">Deadline</div></div></div>
            <div class="kpi-card"><div class="kpi-icon training"><i class="fas fa-bell"></i></div><div><div class="kpi-value" id="cal-kpi-training">0</div><div class="kpi-label">Training</div></div></div>
        </div>

        <div class="calendar-grid">
            <div class="card calendar-card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
                    <div class="calendar-title" id="cal-title">November 2025</div>
                    <div class="calendar-controls"><button class="btn btn-outline btn-sm" id="cal-prev"><i class="fas fa-chevron-left"></i></button><button class="btn btn-outline btn-sm" id="cal-today">Hari Ini</button><button class="btn btn-outline btn-sm" id="cal-next"><i class="fas fa-chevron-right"></i></button></div>
                </div>
                <div class="card-body">
                    <div class="calendar-weekdays"><div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div></div>
                    <div class="calendar-month" id="calendar-month"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="cal-upcoming-title">Event Mendatang</h3></div>
                <div class="card-body" id="event-list"></div>
                </div>
            </div>

        <div class="modal-overlay" id="cal-modal" style="display:none;">
            <div class="modal">
                <div class="modal-header"><h3>Tambah Event</h3><button class="btn btn-outline btn-sm" id="cal-close">✕</button></div>
                <div class="modal-body">
                    <div class="form-row"><label>Nama Event</label><input id="cal-ev-name" type="text" placeholder="Nama event"></div>
                    <div class="form-row"><label>Tanggal</label><div style="position:relative;width:100%"><input id="cal-ev-date" type="text" placeholder="yyyy-mm-dd"><div id="cal-datepicker" class="date-picker" style="display:none"></div></div></div>
                    <div class="form-row"><label>Jenis</label><input id="cal-ev-type" type="text" placeholder="mis. Meeting, Deadline, Training atau bebas"></div>
                    <div class="form-row"><label>Warna</label><input id="cal-ev-color" type="color" value="#2563eb" title="Warna badge event"></div>
                    <div class="form-row"><label>Pilih Cepat</label><div id="cal-color-swatches" class="color-swatches"></div></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" id="cal-cancel">Batal</button><button class="btn btn-primary" id="cal-save">Simpan</button></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
#calendar-page .modal-overlay{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:#0f172a40;z-index:2100}
#calendar-page .calendar-kpi .kpi-icon{color:#ffffff}
#calendar-page .calendar-kpi .kpi-icon.info{background:#3b82f6}
#calendar-page .calendar-kpi .kpi-icon.success{background:#10b981}
#calendar-page .calendar-kpi .kpi-icon.warning{background:#f59e0b}
#calendar-page .calendar-kpi .kpi-icon.training{background:#7c3aed}
#calendar-page #cal-modal .modal{width:460px;max-width:92vw;border-radius:16px}
#calendar-page #cal-modal .modal-header{padding:16px;border-bottom:1px solid #e5e7eb}
#calendar-page #cal-modal .modal-body{padding:16px}
#calendar-page #cal-modal .modal-footer{padding:12px 16px;border-top:1px solid #e5e7eb;display:flex;gap:8px;justify-content:flex-end}
#calendar-page #cal-modal .form-row{display:flex;flex-direction:column;gap:6px;margin-bottom:10px;align-items:flex-start}
#calendar-page #cal-modal .form-row label{color:#374151;font-weight:600;text-align:left}
#calendar-page #cal-modal .form-row input,#calendar-page #cal-modal .form-row select{height:36px;border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:0 12px;width:100%}
#calendar-page #cal-modal .date-picker{position:absolute;top:44px;left:0;right:auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:var(--shadow-lg);width:280px;z-index:2100;padding:10px}
#calendar-page #cal-modal .date-foot{display:flex;align-items:center;justify-content:space-between;margin-top:8px}
#calendar-page #cal-modal .date-foot button{height:28px;border-radius:10px;border:1px solid #e5e7eb;background:#f8fafc;padding:0 10px;color:#374151}
#calendar-page #cal-modal .date-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;color:#111827}
#calendar-page #cal-modal .date-head .month{font-weight:600}
#calendar-page #cal-modal .date-head .nav{display:flex;gap:6px}
#calendar-page #cal-modal .date-head .nav .btn{width:28px;height:28px;border-radius:10px;border:1px solid #e5e7eb;background:#f8fafc}
#calendar-page #cal-modal .date-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}
#calendar-page #cal-modal .date-cell{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#111827}
#calendar-page #cal-modal .date-cell:hover{background:#eef2ff}
#calendar-page #cal-modal .date-cell.today{border:1px solid #93c5fd}
#calendar-page #cal-modal .date-cell.active{background:#2563eb;color:#ffffff}
#calendar-page #cal-modal .form-row select{appearance:none;-webkit-appearance:none;padding-right:38px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px}
#calendar-page #cal-modal .form-row input:focus,#calendar-page #cal-modal .form-row select:focus{outline:none;border-color:#93c5fd;box-shadow:none;background:#ffffff}
#calendar-page .cal-upcoming-title{display:inline-block;background:#2563eb;color:#ffffff;border-radius:10px;padding:6px 12px}
#calendar-page .color-swatches{display:flex;flex-wrap:wrap;gap:8px}
#calendar-page .color-swatch{width:28px;height:28px;border-radius:8px;border:1px solid #e5e7eb;cursor:pointer;box-shadow:var(--shadow-sm)}
#calendar-page .color-swatch.selected{outline:2px solid #2563eb}
#calendar-page .cell-badge{display:inline-block;border-radius:12px;padding:4px 8px;color:#ffffff;font-size:12px;margin:2px 0}
#calendar-page .cell-badge.type-meeting{background:#2563eb}
#calendar-page .cell-badge.type-deadline{background:#f59e0b}
#calendar-page .cell-badge.type-training{background:#7c3aed}
#calendar-page .event-type-badge{display:inline-block;border-radius:10px;padding:2px 8px;color:#ffffff;font-size:12px;margin-right:8px}
#calendar-page .event-type-badge.type-meeting{background:#2563eb}
#calendar-page .event-type-badge.type-deadline{background:#f59e0b}
#calendar-page .event-type-badge.type-training{background:#7c3aed}
#calendar-page .event-actions .btn[data-edit-event] i{color:#2563eb}
#calendar-page .event-actions .btn[data-del-event] i{color:#ef4444}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  var inp=document.getElementById('cal-ev-date');
  var dp=document.getElementById('cal-datepicker');
  if(!inp||!dp)return;
  var cur=new Date();
  function fmt(y,m,d){var mm=(''+(m+1)).padStart(2,'0');var dd=(''+d).padStart(2,'0');return y+'-'+mm+'-'+dd}
  function names(){return ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']}
  function render(){var y=cur.getFullYear(),m=cur.getMonth();var first=(new Date(y,m,1)).getDay();var days=(new Date(y,m+1,0)).getDate();var hdr='<div class="date-head"><div class="month">'+names()[m]+' '+y+'</div><div class="nav"><button type="button" class="btn" data-act="prev">◀</button><button type="button" class="btn" data-act="next">▶</button></div></div>';var grid='<div class="date-grid">';for(var i=0;i<first;i++)grid+='<div></div>';for(var d=1;d<=days;d++){var today=(new Date()).getDate()===d&& (new Date()).getMonth()===m && (new Date()).getFullYear()===y;var val=fmt(y,m,d);var active=inp.value===val;grid+='<div class="date-cell'+(today?' today':'')+(active?' active':'')+'" data-day="'+d+'">'+d+'</div>'}grid+='</div>';var foot='<div class="date-foot"><button type="button" data-act="today">Hari ini</button><button type="button" data-act="done">Selesai</button></div>';dp.innerHTML=hdr+grid+foot}
  function open(){render();dp.style.display='block'}
  function close(){dp.style.display='none'}
  inp.addEventListener('focus',open);inp.addEventListener('click',open);
  dp.addEventListener('click',function(e){var act=e.target.getAttribute('data-act');if(act==='prev'){cur.setMonth(cur.getMonth()-1);render();return}if(act==='next'){cur.setMonth(cur.getMonth()+1);render();return}if(act==='today'){var t=new Date();cur=new Date(t.getFullYear(),t.getMonth(),1);inp.value=fmt(t.getFullYear(),t.getMonth(),t.getDate());render();return}if(act==='done'){close();return}var cell=e.target.closest('.date-cell');if(cell){var y=cur.getFullYear(),m=cur.getMonth(),d=parseInt(cell.getAttribute('data-day'),10);inp.value=fmt(y,m,d);render();}});
  document.addEventListener('click',function(e){if(e.target===inp||dp.contains(e.target))return;close()});
});
</script>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';
  var modal=document.getElementById('cal-modal');
  var addBtn=document.getElementById('cal-add');
  var closeBtn=document.getElementById('cal-close');
  var cancelBtn=document.getElementById('cal-cancel');
  var saveBtn=document.getElementById('cal-save');
  var nameInp=document.getElementById('cal-ev-name');
  var dateInp=document.getElementById('cal-ev-date');
  var typeSel=document.getElementById('cal-ev-type');
  var colorInp=document.getElementById('cal-ev-color');
  var swWrap=document.getElementById('cal-color-swatches');
  var presetBase=['#2563eb','#f59e0b','#7c3aed','#10b981','#ef4444','#14b8a6','#f97316','#84cc16','#06b6d4'];
  var customColor='#a855f7';
  function presets(){return presetBase.concat([customColor])}
  function selectSwatch(c){if(!swWrap)return;swWrap.querySelectorAll('.color-swatch').forEach(function(b){b.classList.toggle('selected',b.getAttribute('data-color')===c)});} 
  function renderSwatches(){if(!swWrap)return;var list=presets();swWrap.innerHTML=list.map(function(c){return '<button type="button" class="color-swatch" data-color="'+c+'" style="background:'+c+'"></button>'}).join('');swWrap.querySelectorAll('.color-swatch').forEach(function(btn){btn.addEventListener('click',function(){var c=this.getAttribute('data-color');colorInp.value=c;selectSwatch(c);});});}
  renderSwatches();
  colorInp?.addEventListener('input',function(){var c=(colorInp?.value||'#2563eb');if(presetBase.indexOf(c)<0){customColor=c;renderSwatches();}selectSwatch(c)});
  var kpiEvents=document.getElementById('cal-kpi-events');
  var kpiMeetings=document.getElementById('cal-kpi-meetings');
  var kpiDeadlines=document.getElementById('cal-kpi-deadlines');
  var kpiTraining=document.getElementById('cal-kpi-training');
  var grid=document.getElementById('calendar-month');
  var list=document.getElementById('event-list');
  var title=document.getElementById('cal-title');
  var prev=document.getElementById('cal-prev');
  var next=document.getElementById('cal-next');
  var today=document.getElementById('cal-today');
  var cur=new Date();
  function ym(){return cur.getFullYear()+"-"+String(cur.getMonth()+1).padStart(2,'0');}
  function names(){return ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']}
  async function fetchEvents(){try{var res=await fetch('/calendar/events?month='+ym(),{headers:{'Accept':'application/json'}});var data=await res.json();render(data);}catch(_){render([])}}
  function render(data){var byDay={};(Array.isArray(data)?data:[]).forEach(function(e){var d=e.date;var day=parseInt(d.split('-')[2],10);if(!byDay[day])byDay[day]=[];byDay[day].push(e)});var y=cur.getFullYear(),m=cur.getMonth();title.textContent=names()[m]+" "+y;var first=(new Date(y,m,1)).getDay();var days=(new Date(y,m+1,0)).getDate();grid.innerHTML='';for(var i=0;i<first;i++){var empty=document.createElement('div');empty.className='calendar-cell empty';grid.appendChild(empty)}for(var d=1;d<=days;d++){var cell=document.createElement('div');cell.className='calendar-cell';var head=document.createElement('div');head.className='cell-head';head.textContent=String(d);cell.appendChild(head);var items=document.createElement('div');items.className='cell-items';(byDay[d]||[]).slice(0,3).forEach(function(e){var it=document.createElement('div');var color=e.color||null;var t=(e.type||'').toLowerCase();it.className='cell-badge type-'+t;it.textContent=e.name;if(color){it.style.background=color;it.style.color='#ffffff'}items.appendChild(it)});cell.appendChild(items);grid.appendChild(cell)}var meetings=(data||[]).filter(function(e){return e.type==='Meeting'}).length;var deadlines=(data||[]).filter(function(e){return e.type==='Deadline'}).length;var trainings=(data||[]).filter(function(e){return e.type==='Training'}).length;kpiEvents.textContent=(data||[]).length;kpiMeetings.textContent=meetings;kpiDeadlines.textContent=deadlines;kpiTraining.textContent=trainings;list.innerHTML=(data||[]).map(function(e){var t=(e.type||'').toLowerCase();var color=e.color||'';var badge='<span class="event-type-badge type-'+t+'"'+(color?(' style="background:'+color+';color:#fff"'):'')+'>'+e.type+'</span>';return '<div class="event-item" data-event-id="'+e.id+'" data-event-type="'+(e.type||'')+'" data-event-color="'+color+'"><div><div class="event-name">'+e.name+'</div><div class="event-meta">'+badge+e.date+'</div></div><div class="event-actions"><button class="btn" data-edit-event="'+e.id+'"><i class="fas fa-pen"></i></button><button class="btn" data-del-event="'+e.id+'"><i class="fas fa-trash"></i></button></div></div>'}).join('')}
  var editingId=null;
  function open(){modal.style.display='flex'}
  function close(){modal.style.display='none'}
  addBtn?.addEventListener('click',function(){editingId=null;nameInp.value='';dateInp.value='';typeSel.value='';if(colorInp)colorInp.value='#2563eb';customColor='#a855f7';renderSwatches();selectSwatch('#2563eb');open()});
  closeBtn?.addEventListener('click',close);
  cancelBtn?.addEventListener('click',close);
  list?.addEventListener('click',async function(e){var ed=e.target.closest('[data-edit-event]');var dl=e.target.closest('[data-del-event]');if(ed){var id=parseInt(ed.getAttribute('data-edit-event'),10);var item=e.target.closest('.event-item');if(!item)return;var name=item.querySelector('.event-name')?.textContent||'';var date=(item.querySelector('.event-meta')?.textContent||'').replace(/^[^\d]+/,'').trim().split(' ')[0]||'';var type=item.getAttribute('data-event-type')||'';var color=item.getAttribute('data-event-color')||'';editingId=id;nameInp.value=name;dateInp.value=date;typeSel.value=type;if(colorInp)colorInp.value=color||'#2563eb';if(presetBase.indexOf(color)<0){customColor=color||'#a855f7';renderSwatches();}selectSwatch(color||'#2563eb');open();return}if(dl){var id=parseInt(dl.getAttribute('data-del-event'),10);try{var res=await fetch('/calendar/events/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin'});editingId=null;nameInp.value='';dateInp.value='';typeSel.value='';if(colorInp)colorInp.value='#2563eb';customColor='#a855f7';renderSwatches();selectSwatch('#2563eb');if(res.ok){await fetchEvents();}else{}}catch(__){}}});
  saveBtn?.addEventListener('click',async function(){var name=nameInp.value.trim();var date=dateInp.value.trim();var type=typeSel.value.trim();var color=(colorInp?.value||'').trim();var dinasId=parseInt(document.body.dataset.dinasId||'0',10)||'';try{var url='/calendar/events'+(editingId?('/'+editingId):'');var method=editingId?'PUT':'POST';var form=new URLSearchParams();form.set('name',name);form.set('date',date);form.set('type',type||'');if(color)form.set('color',color);if(dinasId)form.set('dinas_id',String(dinasId));var res=await fetch(url,{method:method,headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:form.toString()});if(res.ok){close();editingId=null;nameInp.value='';dateInp.value='';typeSel.value='';if(colorInp)colorInp.value='#2563eb';await fetchEvents();}else{try{var err=await res.json();alert(err.message||err.error||('Gagal simpan ('+res.status+')'))}catch(__){alert('Gagal simpan')}}}catch(__){alert('Gagal simpan')}});
  prev?.addEventListener('click',function(){cur.setMonth(cur.getMonth()-1);fetchEvents()});
  next?.addEventListener('click',function(){cur.setMonth(cur.getMonth()+1);fetchEvents()});
  today?.addEventListener('click',function(){var t=new Date();cur=new Date(t.getFullYear(),t.getMonth(),1);fetchEvents()});
  fetchEvents();
});
</script>
@endpush
 
