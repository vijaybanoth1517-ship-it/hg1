@@ .. @@
     public function register($username, $email, $phone, $password, $inviteCode) {
         try {
             $db = $this->database->getConnection();
             
+            // Check if username already exists
+            $usernameQuery = "SELECT COUNT(*) as count FROM users WHERE username = :username";
+            $usernameStmt = $db->prepare($usernameQuery);
+            $usernameStmt->bindParam(':username', $username);
+            $usernameStmt->execute();
+            $usernameResult = $usernameStmt->fetch(PDO::FETCH_ASSOC);
+            
+            if ($usernameResult['count'] > 0) {
+                return ['success' => false, 'message' => 'Username already exists. Please choose a different username.'];
+            }
+            
             // Validate invite code
             $inviteQuery = "SELECT * FROM invites WHERE invite_code = :code AND expires_at > NOW() AND used_at IS NULL";
             $inviteStmt = $db->prepare($inviteQuery);
             $inviteStmt->bindParam(':code', $inviteCode);
             $inviteStmt->execute();
             $invite = $inviteStmt->fetch(PDO::FETCH_ASSOC);
             
             if (!$invite) {
                 return ['success' => false, 'message' => 'Invalid or expired invite code.'];
             }
             
+            // Check if email matches the invitation
+            if ($invite['email'] && strtolower($invite['email']) !== strtolower($email)) {
+                return ['success' => false, 'message' => 'You are not allowed to access this. Please use the invited email.'];
+            }
+            
             // Check if email already exists
             $emailQuery = "SELECT COUNT(*) as count FROM users WHERE email = :email";
             $emailStmt = $db->prepare($emailQuery);
             $emailStmt->bindParam(':email', $email);
             $emailStmt->execute();
             $emailResult = $emailStmt->fetch(PDO::FETCH_ASSOC);
             
             if ($emailResult['count'] > 0) {
                 return ['success' => false, 'message' => 'Email already registered.'];
             }
             
+            // Validate password strength
+            if (!$this->validatePasswordStrength($password)) {
+                return ['success' => false, 'message' => 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'];
+            }
+            
             // Create user
             $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
             $insertQuery = "INSERT INTO users (username, email, phone, password, role, status) VALUES (:username, :email, :phone, :password, :role, 'active')";
             $insertStmt = $db->prepare($insertQuery);
             $insertStmt->bindParam(':username', $username);
             $insertStmt->bindParam(':email', $email);
             $insertStmt->bindParam(':phone', $phone);
             $insertStmt->bindParam(':password', $hashedPassword);
             $insertStmt->bindParam(':role', $invite['role']);
             
             if ($insertStmt->execute()) {
                 // Mark invite as used
                 $updateInviteQuery = "UPDATE invites SET used_at = NOW(), used_by = LAST_INSERT_ID() WHERE id = :invite_id";
                 $updateInviteStmt = $db->prepare($updateInviteQuery);
                 $updateInviteStmt->bindParam(':invite_id', $invite['id']);
                 $updateInviteStmt->execute();
                 
                 return ['success' => true, 'message' => 'Account created successfully! You can now login.'];
             } else {
                 return ['success' => false, 'message' => 'Failed to create account.'];
             }
             
         } catch (Exception $e) {
             return ['success' => false, 'message' => 'Registration error: ' . $e->getMessage()];
         }
     }
+    
+    private function validatePasswordStrength($password) {
        // At least 8 characters, max 128, 1 uppercase, 1 lowercase, 1 number, 1 special character
        if (strlen($password) < 8 || strlen($password) > 128) {
            return false;
        }
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,128}$/', $password);
    }
+        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
+        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
+    }
 }