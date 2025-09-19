<?php
require_once 'includes/auth.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$inviteCode = $_GET['invite'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $inviteCode = $_POST['invite_code'];
    
    if ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.';
    } else {
        $result = $auth->register($username, $email, $phone, $password, $inviteCode);
        
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HG Community</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Join HG Community</h1>
                <p>Create your account to join Hackers Gurukul Community</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                    <a href="login.php">Sign in now</a>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone (Optional)</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small class="password-help">
                        <strong>Password Requirements:</strong><br>
                        • 8-128 characters long<br>
                        • At least 1 uppercase letter (A-Z)<br>
                        • At least 1 lowercase letter (a-z)<br>
                        • At least 1 number (0-9)<br>
                        • At least 1 special character (@$!%*?&)
                    </small>
                    <small class="password-help" style="color: #718096; font-size: 0.8rem; margin-top: 4px; display: block; line-height: 1.4;">
                        <strong>Required:</strong> Minimum 8 characters, maximum 128 characters<br>
                        Must include: 1 uppercase letter, 1 lowercase letter, 1 number, 1 special character (@$!%*?&)
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="invite_code">Invite Code</label>
                    <input type="text" id="invite_code" name="invite_code" value="<?php echo htmlspecialchars($inviteCode); ?>" required>
                </div>
                
                <button type="submit" class="auth-button">Create Account</button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </div>
</body>
</html>