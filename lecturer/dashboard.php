<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('lecturer');

$conn = getDBConnection();
$lecturer_id = $_SESSION['user_id'];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    
    $upload_dir = '../uploads/' . $type . '/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $target_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
    }
    
    $stmt = $conn->prepare("INSERT INTO materials (lecturer_id, title, description, type, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $lecturer_id, $title, $description, $type, $file_name);
    
    if ($stmt->execute()) {
        $success = "Material uploaded successfully!";
    } else {
        $error = "Error uploading material.";
    }
    $stmt->close();
}

// Get uploaded materials
$materials = $conn->query("SELECT * FROM materials WHERE lecturer_id = $lecturer_id ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard - Smart Tutor</title>
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
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="upload_lecture.php">Upload Lecture</a></li>
                    <li><a href="upload_homework.php">Upload Homework</a></li>
                    <li><a href="upload_video.php">Upload Video</a></li>
                    <li><a href="upload_report_card.php">Upload Report Card</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
                <div class="user-info">
                    <span>Lecturer</span>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="card">
                <h2>Upload New Material</h2>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label for="type">Material Type</label>
                        <select name="type" id="type" required class="form-control">
                            <option value="">Select Type</option>
                            <option value="lecture">Lecture</option>
                            <option value="homework">Homework</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" required class="form-control" placeholder="Enter material title">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Enter description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">File</label>
                        <input type="file" name="file" id="file" required class="form-control">
                    </div>
                    
                    <button type="submit" name="upload" class="btn btn-primary">Upload Material</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Uploaded Materials</h2>
                <div class="materials-grid">
                    <?php if ($materials && $materials->num_rows > 0): ?>
                        <?php while ($material = $materials->fetch_assoc()): ?>
                            <div class="material-card">
                                <div class="material-type-badge type-<?php echo $material['type']; ?>">
                                    <?php echo ucfirst($material['type']); ?>
                                </div>
                                <h3><?php echo htmlspecialchars($material['title']); ?></h3>
                                <p><?php echo htmlspecialchars($material['description']); ?></p>
                                <div class="material-meta">
                                    <span>Uploaded: <?php echo date('M d, Y', strtotime($material['uploaded_at'])); ?></span>
                                    <?php if ($material['file_path']): ?>
                                        <a href="../uploads/<?php echo $material['type']; ?>/<?php echo htmlspecialchars($material['file_path']); ?>" 
                                           class="btn btn-secondary btn-sm" download>Download</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No materials uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

