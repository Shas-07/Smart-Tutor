<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('lecturer');

$conn = getDBConnection();
$lecturer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video_url = $_POST['video_url'] ?? '';
    
    $upload_dir = '../uploads/video/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = '';
    if (!empty($video_url)) {
        // Validate URL
        if (filter_var($video_url, FILTER_VALIDATE_URL)) {
            $file_name = $video_url;
        } else {
            $error = "Invalid video URL. Please enter a valid URL.";
        }
    } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'm4v'];
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
            $error = "Invalid file type. Allowed: MP4, AVI, MOV, WMV, FLV, WEBM, MKV, M4V";
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO materials (lecturer_id, title, description, type, file_path) VALUES (?, ?, ?, 'video', ?)");
    $stmt->bind_param("isss", $lecturer_id, $title, $description, $file_name);
    
    if ($stmt->execute()) {
        $success = "Video uploaded successfully!";
    } else {
        $error = "Error uploading video.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - Smart Tutor</title>
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
                    <li><a href="upload_lecture.php">Upload Lecture</a></li>
                    <li><a href="upload_homework.php">Upload Homework</a></li>
                    <li><a href="upload_video.php" class="active">Upload Video</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Upload Video</h1>
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
                        <label for="title">Video Title</label>
                        <input type="text" name="title" id="title" required class="form-control" placeholder="Enter video title">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Enter video description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_url">Video URL (YouTube, Vimeo, etc.) - Optional</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Or Upload Video File</label>
                        <input type="file" name="file" id="file" class="form-control" accept="video/*">
                    </div>
                    
                    <button type="submit" name="upload" class="btn btn-primary">Upload Video</button>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

