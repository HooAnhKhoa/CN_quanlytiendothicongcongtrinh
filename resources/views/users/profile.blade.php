@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('styles')
<style>
.profile-cover {
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    border-radius: 1rem 1rem 0 0;
    overflow: hidden;
}

.profile-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
}

.profile-avatar-edit {
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.cover-upload-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.cover-upload-btn:hover {
    background: white;
    transform: translateY(-2px);
}

.skill-tag {
    display: inline-flex;
    align-items: center;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-color);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    margin: 0.25rem;
    transition: all 0.3s ease;
}

.skill-tag:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.05);
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary-color);
    border: 2px solid white;
    box-shadow: 0 0 0 3px var(--primary-color);
}

.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    padding: 1rem;
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #f8fafc;
    border-left-color: var(--primary-color);
}

.activity-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-color);
}

.stat-card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.stat-card-gradient .stat-value {
    font-size: 2.5rem;
    font-weight: 700;
}

.profile-tab {
    border-bottom: 2px solid transparent;
    padding: 1rem 1.5rem;
    color: var(--secondary-color);
    cursor: pointer;
    transition: all 0.3s ease;
}

.profile-tab.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
    font-weight: 600;
}

.profile-tab:hover:not(.active) {
    color: var(--primary-color);
    background: rgba(59, 130, 246, 0.05);
}
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Profile Header -->
    <div class="card mb-8">
        <div class="profile-cover">
            <button class="cover-upload-btn no-print">
                <i class="fas fa-camera"></i>
                <span>Thay ảnh bìa</span>
            </button>
        </div>
        
        <div class="relative">
            <div class="profile-avatar-edit">
                <div class="image-upload-container">
                    <div class="image-preview">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" id="avatar-preview">
                        @else
                            <div class="image-preview-placeholder">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                        <div class="image-upload-overlay">
                            <button class="image-upload-btn" onclick="document.getElementById('avatar-upload').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <input type="file" id="avatar-upload" class="image-upload-input" accept="image/*">
                </div>
            </div>
            
            <div class="pt-20 pb-6 px-6 text-center">
                <h1 class="text-3xl font-bold text-gray-900">{{ auth()->user()->username }}</h1>
                <p class="text-gray-600 mt-2">{{ auth()->user()->user_type }} • {{ auth()->user()->email }}</p>
                <div class="flex justify-center gap-2 mt-3">
                    <span class="badge badge-primary">{{ auth()->user()->status }}</span>
                    <span class="badge badge-secondary">{{ auth()->user()->phone }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Tabs -->
    <div class="card mb-8">
        <div class="card-header">
            <div class="flex overflow-x-auto">
                <div class="profile-tab active" data-tab="overview">Tổng quan</div>
                <div class="profile-tab" data-tab="edit">Chỉnh sửa hồ sơ</div>
                <div class="profile-tab" data-tab="security">Bảo mật</div>
                <div class="profile-tab" data-tab="activity">Hoạt động</div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="card-body">
            <!-- Overview Tab -->
            <div id="tab-overview" class="tab-content active">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <!-- About Section -->
                        <div class="card mb-6">
                            <div class="card-header">
                                <h3 class="card-title">Giới thiệu</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-gray-700">Chưa có thông tin giới thiệu. <a href="#" class="text-primary hover:underline">Thêm giới thiệu</a></p>
                            </div>
                        </div>

                        <!-- Projects Section -->
                        <div class="card mb-6">
                            <div class="card-header">
                                <h3 class="card-title">Dự án gần đây</h3>
                            </div>
                            <div class="card-body">
                                <div class="space-y-4">
                                    @foreach(auth()->user()->engineeredProjects->take(3) as $project)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <h4 class="font-semibold">{{ $project->project_name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $project->location }}</p>
                                            <div class="flex items-center gap-2 mt-2">
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: {{ $project->progress_percent ?? 0 }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-600">{{ $project->progress_percent ?? 0 }}%</span>
                                            </div>
                                        </div>
                                        <span class="badge badge-{{ $project->status === 'completed' ? 'success' : 'primary' }}">
                                            {{ $project->status }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Stats -->
                        <div class="card stat-card-gradient">
                            <div class="card-body text-center">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="stat-value">{{ auth()->user()->engineeredProjects->count() }}</span>
                                        <div class="stat-label">Dự án</div>
                                    </div>
                                    <div>
                                        <span class="stat-value">{{ auth()->user()->progressUpdates->count() }}</span>
                                        <div class="stat-label">Cập nhật</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin liên hệ</h3>
                            </div>
                            <div class="card-body">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        <span>{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                        <span>Tham gia: {{ auth()->user()->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kỹ năng</h3>
                            </div>
                            <div class="card-body">
                                <div class="flex flex-wrap gap-2">
                                    <span class="skill-tag">Quản lý dự án</span>
                                    <span class="skill-tag">Xây dựng</span>
                                    <span class="skill-tag">Giám sát</span>
                                    <span class="skill-tag">Kế hoạch</span>
                                    <button class="skill-tag bg-transparent border border-dashed border-gray-300">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Tab -->
            <div id="tab-edit" class="tab-content">
                <form id="profile-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label form-label-required">Tên đăng nhập</label>
                            <input type="text" class="form-input" value="{{ auth()->user()->username }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label form-label-required">Email</label>
                            <input type="email" class="form-input" value="{{ auth()->user()->email }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-input" value="{{ auth()->user()->phone }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Vai trò</label>
                            <select class="form-select" disabled>
                                <option>{{ ucfirst(auth()->user()->user_type) }}</option>
                            </select>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-textarea" rows="3" placeholder="Nhập địa chỉ của bạn"></textarea>
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Giới thiệu bản thân</label>
                            <textarea class="form-textarea" rows="4" placeholder="Mô tả về bản thân và kinh nghiệm làm việc"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Cập nhật hồ sơ
                        </button>
                        <button type="reset" class="btn btn-outline">
                            <i class="fas fa-undo mr-2"></i>Đặt lại
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="tab-security" class="tab-content">
                <div class="space-y-6">
                    <!-- Change Password -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Đổi mật khẩu</h3>
                        </div>
                        <div class="card-body">
                            <form class="space-y-4">
                                <div class="form-group">
                                    <label class="form-label form-label-required">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-input" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label form-label-required">Mật khẩu mới</label>
                                    <input type="password" class="form-input" required>
                                    <div class="form-help">Mật khẩu phải có ít nhất 8 ký tự</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label form-label-required">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-input" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key mr-2"></i>Đổi mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Xác thực hai yếu tố</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold">Xác thực hai yếu tố</h4>
                                    <p class="text-gray-600 text-sm">Tăng cường bảo mật cho tài khoản của bạn</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Tab -->
            <div id="tab-activity" class="tab-content">
                <div class="activity-feed">
                    <div class="timeline">
                        <!-- Activity items would be populated here -->
                        <div class="timeline-item">
                            <div class="card">
                                <div class="card-body">
                                    <div class="flex items-start gap-3">
                                        <div class="activity-icon">
                                            <i class="fas fa-project-diagram"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold">Đã tạo dự án mới</h4>
                                            <p class="text-gray-600">Dự án "Tòa nhà Sunshine" đã được tạo</p>
                                            <span class="text-sm text-gray-500">2 giờ trước</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="card">
                                <div class="card-body">
                                    <div class="flex items-start gap-3">
                                        <div class="activity-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold">Cập nhật tiến độ</h4>
                                            <p class="text-gray-600">Đã cập nhật tiến độ công việc "Xây dựng móng" lên 75%</p>
                                            <span class="text-sm text-gray-500">5 giờ trước</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/views/profile.js') }}"></script>
@endsection