<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('student');

$conn = getDBConnection();
$materials = $conn->query("SELECT m.*, u.full_name as lecturer_name 
                           FROM materials m 
                           JOIN users u ON m.lecturer_id = u.id 
                           WHERE m.type = 'video'
                           ORDER BY m.uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos - Smart Tutor</title>
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
                <p>Student Portal</p>
            </div>
            <nav>
                <ul class="sidebar-nav">
                    <li><a href="dashboard.php">My Materials</a></li>
                    <li><a href="lectures.php">Lectures</a></li>
                    <li><a href="homework.php">Homework</a></li>
                    <li><a href="videos.php" class="active">Videos</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Videos</h1>
            </div>
            
            <div class="card">
                <div class="materials-grid">
                    <?php if ($materials && $materials->num_rows > 0): ?>
                        <?php while ($material = $materials->fetch_assoc()): ?>
                            <div class="material-card">
                                <div class="material-type-badge type-video">Video</div>
                                <h3><?php echo htmlspecialchars($material['title']); ?></h3>
                                <p><?php echo htmlspecialchars($material['description']); ?></p>
                                <div class="material-meta">
                                    <span class="lecturer-name">By: <?php echo htmlspecialchars($material['lecturer_name']); ?></span>
                                    <span class="upload-date"><?php echo date('M d, Y', strtotime($material['uploaded_at'])); ?></span>
                                </div>
                                <?php if ($material['file_path']): ?>
                                    <?php
                                    $is_url = filter_var($material['file_path'], FILTER_VALIDATE_URL);
                                    ?>
                                    <?php if ($is_url): ?>
                                        <a href="<?php echo htmlspecialchars($material['file_path']); ?>" 
                                           target="_blank" class="btn btn-primary btn-block mt-2">🎥 Watch Video</a>
                                    <?php else: ?>
                                        <a href="../uploads/video/<?php echo htmlspecialchars($material['file_path']); ?>" 
                                           class="btn btn-primary btn-block mt-2" download>🎥 Download Video</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No videos available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

