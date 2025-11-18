@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<div class="calendar-container">
    <div class="page-header">
        <h1>Event Calendar</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Important Dates & Deadlines</h2>
        </div>
        <div class="card-body">
            <div id="calendar" class="calendar-widget"></div>
        </div>
    </div>

    <div class="events-list">
        <div class="card">
            <div class="card-header">
                <h3>Upcoming Deadlines</h3>
            </div>
            <div class="card-body">
                <ul class="event-items">
                    <li>
                        <span class="event-date">15 Nov 2025</span>
                        <span class="event-name">Data Submission Deadline - Dinas Perdagangan</span>
                    </li>
                    <li>
                        <span class="event-date">20 Nov 2025</span>
                        <span class="event-name">Validation Review Meeting</span>
                    </li>
                    <li>
                        <span class="event-date">25 Nov 2025</span>
                        <span class="event-name">Final Report Submission</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
