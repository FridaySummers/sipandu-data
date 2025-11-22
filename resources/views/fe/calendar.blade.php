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
            <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-bell"></i></div><div><div class="kpi-value" id="cal-kpi-training">8</div><div class="kpi-label">Training</div></div></div>
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
                    <div class="form-row"><label>Tanggal</label><input id="cal-ev-date" type="date"></div>
                    <div class="form-row"><label>Jenis</label><select id="cal-ev-type"><option value="Meeting">Meeting</option><option value="Deadline">Deadline</option><option value="Training">Training</option></select></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" id="cal-cancel">Batal</button><button class="btn btn-primary" id="cal-save">Simpan</button></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
