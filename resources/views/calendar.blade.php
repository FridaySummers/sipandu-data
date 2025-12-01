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
            <div class="kpi-card"><div class="kpi-icon info"><i class="fas fa-calendar-alt"></i></div><div><div class="kpi-value" id="cal-kpi-events">28</div><div class="kpi-label">Event Bulan Ini</div></div></div>
            <div class="kpi-card"><div class="kpi-icon success"><i class="fas fa-user-friends"></i></div><div><div class="kpi-value" id="cal-kpi-meetings">12</div><div class="kpi-label">Meeting</div></div></div>
            <div class="kpi-card"><div class="kpi-icon warning"><i class="fas fa-clock"></i></div><div><div class="kpi-value" id="cal-kpi-deadlines">8</div><div class="kpi-label">Deadline</div></div></div>
            <div class="kpi-card"><div class="kpi-icon training"><i class="fas fa-bell"></i></div><div><div class="kpi-value" id="cal-kpi-training">8</div><div class="kpi-label">Training</div></div></div>
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
                <div class="card-header"><h3>Event Mendatang</h3></div>
                <div class="card-body" id="event-list"></div>
            </div>
        </div>

        <div class="modal-overlay" id="cal-modal" style="display:none;">
            <div class="modal">
                <div class="modal-header"><h3>Tambah Event</h3><button class="btn btn-outline btn-sm" id="cal-close">✕</button></div>
                <div class="modal-body">
                    <div class="form-row"><label>Nama Event</label><input id="cal-ev-name" type="text" placeholder="Nama event"></div>
                    <div class="form-row"><label>Tanggal</label><div style="position:relative;width:100%"><input id="cal-ev-date" type="text" placeholder="yyyy-mm-dd"><div id="cal-datepicker" class="date-picker" style="display:none"></div></div></div>
                    <div class="form-row"><label>Jenis</label><select id="cal-ev-type"><option value="Meeting">Meeting</option><option value="Deadline">Deadline</option><option value="Training">Training</option></select></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" id="cal-cancel">Batal</button><button class="btn btn-primary" id="cal-save">Simpan</button></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
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
@endpush
