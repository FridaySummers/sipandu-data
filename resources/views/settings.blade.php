@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="settings-container">
    <div class="page-header">
        <h1>Settings</h1>
    </div>

    <div class="settings-content">
        <div class="settings-sidebar">
            <ul class="settings-menu">
                <li><a href="#profile" class="active">Profile</a></li>
                <li><a href="#account">Account</a></li>
                <li><a href="#notifications">Notifications</a></li>
                <li><a href="#preferences">Preferences</a></li>
            </ul>
        </div>

        <div class="settings-main">
            <div id="profile" class="settings-section">
                <div class="card">
                    <div class="card-header">
                        <h2>Profile Settings</h2>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="account" class="settings-section" style="display:none;">
                <div class="card">
                    <div class="card-header">
                        <h2>Account Settings</h2>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" class="form-control" placeholder="Enter current password">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm new password">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
