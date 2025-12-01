<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('lecturer');

$conn = getDBConnection();
$lecturer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $upload_dir = '../uploads/lecture/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'xls', 'xlsx'];
        $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['file']['name']));
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                // File uploaded successfully
            } else {
                $error = "Failed to upload file. Please try again.";
            }
        } else {
            $error = "Invalid file type. Allowed: PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX";
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO materials (lecturer_id, title, description, type, file_path) VALUES (?, ?, ?, 'lecture', ?)");
    $stmt->bind_param("isss", $lecturer_id, $title, $description, $file_name);
    
    if ($stmt->execute()) {
        $success = "Lecture uploaded successfully!";
    } else {
        $error = "Error uploading lecture.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Lecture - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
</head>
<body>
    <?php include '../includes/news_ticker.php'; ?>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Smart Tutor</h2>
                <p>Lecturer Portal</p>
            </div>
            <nav>
                <ul class="sidebar-nav">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="upload_lecture.php" class="active">Upload Lecture</a></li>
                    <li><a href="upload_homework.php">Upload Homework</a></li>
                    <li><a href="upload_video.php">Upload Video</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Upload Lecture</h1>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="card">
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label for="title">Lecture Title</label>
                        <input type="text" name="title" id="title" required class="form-control" placeholder="Enter lecture title">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Enter lecture description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Lecture File (PDF, DOC, PPT, etc.)</label>
                        <input type="file" name="file" id="file" required class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    </div>
                    
                    <button type="submit" name="upload" class="btn btn-primary">Upload Lecture</button>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

