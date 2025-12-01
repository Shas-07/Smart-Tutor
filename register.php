<?php
require_once 'config/database.php';
require_once 'config/session.php';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    
    $conn = getDBConnection();
    
    // Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        // Check if username already exists for this specific role
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND role = ?");
        $check_stmt->bind_param("ss", $username, $role);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Username already exists for this role! Please choose a different username or login instead.";
        } else {
            // For parent registration, check if student exists with same username
            if ($role === 'parent') {
                $student_check = $conn->prepare("SELECT s.id FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = ?");
                $student_check->bind_param("s", $username);
                $student_check->execute();
                $student_result = $student_check->get_result();
                
                if ($student_result->num_rows === 0) {
                    $error = "No student found with this username. Student must register first.";
                } else {
                    $student_data = $student_result->fetch_assoc();
                    // Create parent account
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $insert_stmt = $conn->prepare("INSERT INTO users (username, password, email, role, full_name) VALUES (?, ?, ?, ?, ?)");
                    $insert_stmt->bind_param("sssss", $username, $hashed_password, $email, $role, $full_name);
                    
                    if ($insert_stmt->execute()) {
                        $new_parent_id = $conn->insert_id;
                        // Link parent to student
                        $conn->query("UPDATE students SET parent_id = $new_parent_id WHERE id = {$student_data['id']}");
                        $success = "Registration successful! You can now login.";
                    } else {
                        $error = "Error creating account. Please try again.";
                    }
                    $insert_stmt->close();
                }
                $student_check->close();
            } else {
                // Create student or lecturer account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO users (username, password, email, role, full_name) VALUES (?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("sssss", $username, $hashed_password, $email, $role, $full_name);
                
                if ($insert_stmt->execute()) {
                    $new_user_id = $conn->insert_id;
                    
                    // If student, create student record
                    if ($role === 'student') {
                        $student_id = 'STU' . str_pad($new_user_id, 3, '0', STR_PAD_LEFT);
                        $conn->query("INSERT INTO students (user_id, student_id) VALUES ($new_user_id, '$student_id')");
                    }
                    
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Error creating account. Please try again.";
                }
                $insert_stmt->close();
            }
        }
        $check_stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Tutor - Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="login-card">
                <div class="logo-section">
                    <h1 class="logo">Smart Tutor</h1>
                    <p class="tagline">Create Your Account</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="login-form">
                    <div class="form-group">
                        <label for="role">I am a:</label>
                        <select name="role" id="role" required class="form-control">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="parent">Parent</option>
                            <option value="lecturer">Lecturer</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required class="form-control" placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required class="form-control" placeholder="Enter username">
                        <small class="form-hint" id="username-hint" style="display:none;">For parents: Use your child's username</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-control" placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required class="form-control" placeholder="Enter password (min 6 characters)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="form-control" placeholder="Confirm password">
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                </form>
                
                <div class="auth-links">
                    <p>Already have an account? <a href="index.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const hint = document.getElementById('username-hint');
            if (this.value === 'parent') {
                hint.style.display = 'block';
            } else {
                hint.style.display = 'none';
            }
        });
    </script>
</body>
</html>

