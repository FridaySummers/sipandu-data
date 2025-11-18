@extends('layouts.app')

@section('title', 'Forum')

@section('content')
<div class="forum-container">
    <div class="page-header">
        <h1>Forum Diskusi</h1>
        <button class="btn btn-primary" onclick="openNewTopicModal()">
            <i class="fas fa-plus"></i> Topik Baru
        </button>
    </div>

    <div class="forum-content">
        <div class="topics-list">
            <div class="card">
                <div class="card-body">
                    <div class="forum-topic">
                        <div class="topic-avatar">
                            <img src="https://via.placeholder.com/40" alt="User">
                        </div>
                        <div class="topic-info">
                            <h4>Bagaimana cara mengisi form data dengan benar?</h4>
                            <p>Posted by Admin Bappeda • 2 hours ago</p>
                            <p class="topic-preview">Silakan ikuti panduan dalam dokumen yang telah kami bagikan...</p>
                            <div class="topic-stats">
                                <span><i class="fas fa-comment"></i> 5 Replies</span>
                                <span><i class="fas fa-eye"></i> 45 Views</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="forum-sidebar">
            <div class="card">
                <div class="card-header">
                    <h3>Categories</h3>
                </div>
                <div class="card-body">
                    <ul class="category-list">
                        <li><a href="#">Umum</a></li>
                        <li><a href="#">Panduan & Tutorial</a></li>
                        <li><a href="#">Technical Support</a></li>
                        <li><a href="#">Saran & Masukan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openNewTopicModal() {
        // Implement new topic modal
        alert('Open new topic modal');
    }
</script>
@endpush
