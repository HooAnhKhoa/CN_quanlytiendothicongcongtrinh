class Profile {
    constructor() {
        this.currentTab = 'overview';
        this.init();
    }

    init() {
        this.setupTabNavigation();
        this.setupImageUpload();
        this.setupFormHandlers();
        this.setupAvatarUpload();
    }

    setupTabNavigation() {
        const tabs = document.querySelectorAll('.profile-tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.getAttribute('data-tab');
                
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show corresponding content
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === `tab-${tabName}`) {
                        content.classList.add('active');
                    }
                });

                this.currentTab = tabName;
            });
        });
    }

    setupImageUpload() {
        const coverUploadBtn = document.querySelector('.cover-upload-btn');
        if (coverUploadBtn) {
            coverUploadBtn.addEventListener('click', () => {
                this.showNotification('Tính năng upload ảnh bìa đang được phát triển', 'info');
            });
        }
    }

    setupAvatarUpload() {
        const avatarUpload = document.getElementById('avatar-upload');
        const avatarPreview = document.getElementById('avatar-preview');
        const imagePreview = document.querySelector('.image-preview');

        avatarUpload.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    this.showNotification('Kích thước ảnh không được vượt quá 5MB', 'error');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    this.showNotification('Vui lòng chọn file ảnh', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Create new image if placeholder exists
                        const placeholder = imagePreview.querySelector('.image-preview-placeholder');
                        if (placeholder) {
                            placeholder.remove();
                            const img = document.createElement('img');
                            img.id = 'avatar-preview';
                            img.src = e.target.result;
                            img.alt = 'Avatar';
                            imagePreview.appendChild(img);
                        }
                    }

                    // Upload image to server
                    this.uploadAvatar(file);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    async uploadAvatar(file) {
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', window.csrfToken);

        try {
            const response = await fetch('/profile/avatar', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                this.showNotification('Cập nhật ảnh đại diện thành công', 'success');
            } else {
                this.showNotification(result.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Upload failed:', error);
            this.showNotification('Có lỗi xảy ra khi upload ảnh', 'error');
        }
    }

    setupFormHandlers() {
        const profileForm = document.getElementById('profile-form');
        if (profileForm) {
            profileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateProfile();
            });
        }

        const passwordForm = document.querySelector('#tab-security form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.changePassword();
            });
        }
    }

    async updateProfile() {
        const formData = new FormData(document.getElementById('profile-form'));
        
        try {
            const response = await window.App.ajax('/profile/update', {
                method: 'POST',
                body: formData
            });

            if (response.success) {
                this.showNotification('Cập nhật hồ sơ thành công', 'success');
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Update failed:', error);
            this.showNotification('Có lỗi xảy ra khi cập nhật hồ sơ', 'error');
        }
    }

    async changePassword() {
        const form = document.querySelector('#tab-security form');
        const formData = new FormData(form);

        try {
            const response = await window.App.ajax('/profile/password', {
                method: 'POST',
                body: formData
            });

            if (response.success) {
                this.showNotification('Đổi mật khẩu thành công', 'success');
                form.reset();
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Password change failed:', error);
            this.showNotification('Có lỗi xảy ra khi đổi mật khẩu', 'error');
        }
    }

    showNotification(message, type = 'info') {
        if (window.App && window.App.showNotification) {
            window.App.showNotification(message, type);
        } else {
            // Fallback notification
            alert(message);
        }
    }
}

// Initialize profile when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Profile();
});