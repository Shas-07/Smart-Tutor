<?php
require_once 'config/database.php';
require_once 'config/session.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $conn = getDBConnection();
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password, role, full_name FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Redirect based on role
            switch ($role) {
                case 'lecturer':
                    header('Location: lecturer/dashboard.php');
                    break;
                case 'student':
                    header('Location: student/dashboard.php');
                    break;
                case 'parent':
                    header('Location: parent/dashboard.php');
                    break;
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        // For parent role, check if student exists with same username, then check parent account
        if ($role === 'parent') {
            // First check if student exists with this username
            $student_check = $conn->prepare("SELECT s.id FROM students s JOIN users u ON s.user_id = u.id WHERE u.username = ?");
            $student_check->bind_param("s", $username);
            $student_check->execute();
            $student_result = $student_check->get_result();
            
            if ($student_result->num_rows === 1) {
                // Student exists, now check if parent account exists with same username
                $parent_check = $conn->prepare("SELECT id, password, full_name FROM users WHERE username = ? AND role = 'parent'");
                $parent_check->bind_param("s", $username);
                $parent_check->execute();
                $parent_result = $parent_check->get_result();
                
                if ($parent_result->num_rows === 1) {
                    $parent_user = $parent_result->fetch_assoc();
                    if (password_verify($password, $parent_user['password'])) {
                        $_SESSION['user_id'] = $parent_user['id'];
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = 'parent';
                        $_SESSION['full_name'] = $parent_user['full_name'];
                        header('Location: parent/dashboard.php');
                        exit();
                    } else {
                        $error = "Invalid password!";
                    }
                } else {
                    $error = "Parent account not found. Please register with your child's username.";
                }
                $parent_check->close();
            } else {
                $error = "No student found with this username. Student must register first.";
            }
            $student_check->close();
        } else {
            $error = "Invalid credentials! Please register first.";
        }
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Tutor - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="login-card">
                <div class="logo-section">
                    <h1 class="logo">Smart Tutor</h1>
                    <p class="tagline">Empowering Education Through Technology</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
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
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required class="form-control" placeholder="Enter your username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required class="form-control" placeholder="Enter your password">
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                </form>
                
                <div class="auth-links">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>

