<?php
require_once 'includes/auth.php';

$auth = new Auth();

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - HG Community</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #2b2d31;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #3f4147;
        }
        
        .profile-avatar {
            position: relative;
            width: 120px;
            height: 120px;
        }
        
        .profile-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #5865f2;
        }
        
        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #5865f2;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-info h1 {
            color: #ffffff;
            font-size: 2rem;
            margin-bottom: 8px;
        }
        
        .profile-info .role-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: inline-block;
        }
        
        .profile-info .join-date {
            color: #949ba4;
            font-size: 0.9rem;
        }
        
        .profile-form {
            display: grid;
            gap: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            color: #dbdee1;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 6px;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 12px;
            background: #383a40;
            border: 1px solid #4f545c;
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.9rem;
            transition: all 0.15s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #5865f2;
            box-shadow: 0 0 0 2px rgba(88, 101, 242, 0.2);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .save-button {
            background: #5865f2;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            justify-self: start;
        }
        
        .save-button:hover {
            background: #4752c4;
        }
        
        .form-group label input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        
        .back-button {
            background: #4f545c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .back-button:hover {
            background: #5d6269;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="main-content" style="width: 100%; max-width: none;">
            <div class="profile-container">
                <a href="index.php" class="back-button">← Back to Community</a>
                
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img src="<?php echo $user['avatar'] ? htmlspecialchars($user['avatar']) : 'assets/images/default-avatar.png'; ?>" alt="Profile Picture" id="profile-avatar-img">
                        <button class="avatar-upload" onclick="document.getElementById('avatar-input').click()">📷</button>
                        <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                        <span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                        <div class="join-date">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></div>
                    </div>
                </div>
                
                <form id="profile-form" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email (Read-only)</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="opacity: 0.6;">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Hidden from regular users">
                        </div>
                        <div class="form-group">
                            <label for="role">Role (Read-only)</label>
                            <input type="text" id="role" name="role" value="<?php echo ucfirst($user['role']); ?>" readonly style="opacity: 0.6;">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="snapchat">Snapchat</label>
                            <input type="text" id="snapchat" name="snapchat" value="<?php echo htmlspecialchars($user['snapchat'] ?? ''); ?>" placeholder="@username">
                        </div>
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text" id="instagram" name="instagram" value="<?php echo htmlspecialchars($user['instagram'] ?? ''); ?>" placeholder="@username">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text" id="facebook" name="facebook" value="<?php echo htmlspecialchars($user['facebook'] ?? ''); ?>" placeholder="Profile URL or username">
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>" placeholder="City, Country">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio / About</label>
                        <textarea id="bio" name="bio" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="interests">Interests</label>
                        <textarea id="interests" name="interests" placeholder="Your hobbies, interests, skills..."><?php echo htmlspecialchars($user['interests'] ?? ''); ?></textarea>
                    </div>
                    
                    <h3 style="color: #ffffff; margin: 30px 0 20px 0; border-bottom: 1px solid #3f4147; padding-bottom: 10px;">Privacy Settings</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="phone-visible" name="phone_visible" <?php echo $user['phone_visible'] ? 'checked' : ''; ?>>
                                Show phone number to all users
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="social-links-visible" name="social_links_visible" <?php echo $user['social_links_visible'] ? 'checked' : ''; ?>>
                                Show social media links
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="profile-picture-visible" name="profile_picture_visible" <?php echo $user['profile_picture_visible'] ? 'checked' : ''; ?>>
                                Show profile picture
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="online-status-visible" name="online_status_visible" <?php echo $user['online_status_visible'] ? 'checked' : ''; ?>>
                                Show online status
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="allow-dms" name="allow_dms" <?php echo $user['allow_dms'] ? 'checked' : ''; ?>>
                            Allow direct messages
                        </label>
                    </div>
                    
                    <button type="submit" class="save-button">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Handle avatar upload
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                return;
            }
            
            const formData = new FormData();
            formData.append('avatar', file);
            
            fetch('api/profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profile-avatar-img').src = data.avatar;
                    showNotification('Profile picture updated successfully!', 'success');
                } else {
                    showNotification('Failed to update profile picture: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating profile picture', 'error');
            });
        });
        
        // Handle profile form submission
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                username: formData.get('username'),
                phone: formData.get('phone'),
                bio: formData.get('bio'),
                snapchat: formData.get('snapchat'),
                instagram: formData.get('instagram'),
                facebook: formData.get('facebook'),
                location: formData.get('location'),
                interests: formData.get('interests'),
                phone_visible: document.getElementById('phone-visible').checked,
                social_links_visible: document.getElementById('social-links-visible').checked,
                profile_picture_visible: document.getElementById('profile-picture-visible').checked,
                online_status_visible: document.getElementById('online-status-visible').checked,
                allow_dms: document.getElementById('allow-dms').checked
            };
            
            fetch('api/profile.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Profile updated successfully!', 'success');
                } else {
                    showNotification('Failed to update profile: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating profile', 'error');
            });
        });
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }
    </script>
</body>
</html>