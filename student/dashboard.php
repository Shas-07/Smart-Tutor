<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('student');

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];

// Get student ID
$student_query = $conn->query("SELECT id FROM students WHERE user_id = $user_id");
$student = $student_query->fetch_assoc();
$student_id = $student['id'] ?? null;

// Get all materials
$materials = $conn->query("SELECT m.*, u.full_name as lecturer_name 
                           FROM materials m 
                           JOIN users u ON m.lecturer_id = u.id 
                           ORDER BY m.uploaded_at DESC");

// Update progress if student marks material as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_progress'])) {
    $material_id = $_POST['material_id'];
    $status = $_POST['status'];
    
    if ($student_id) {
        $check = $conn->query("SELECT id FROM student_progress WHERE student_id = $student_id AND material_id = $material_id");
        
        if ($check->num_rows > 0) {
            $conn->query("UPDATE student_progress SET status = '$status' WHERE student_id = $student_id AND material_id = $material_id");
        } else {
            $conn->query("INSERT INTO student_progress (student_id, material_id, status) VALUES ($student_id, $material_id, '$status')");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/ai-chat.css">
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
                    <li><a href="dashboard.php" class="active">My Materials</a></li>
                    <li><a href="lectures.php">Lectures</a></li>
                    <li><a href="homework.php">Homework</a></li>
                    <li><a href="videos.php">Videos</a></li>
                    <li><a href="calculator.php">Calculator</a></li>
                    <li><a href="formulas.php">Formulas</a></li>
                    <li><a href="periodic_table.php">Periodic Table</a></li>
                    <li><a href="work_log.php">Work Log</a></li>
                    <li><a href="report_cards.php">Report Cards</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
                <div class="user-info">
                    <span>Student</span>
                </div>
            </div>
            
            <div class="card">
                <h2>All Uploaded Materials</h2>
                <div class="materials-grid">
                    <?php if ($materials && $materials->num_rows > 0): ?>
                        <?php while ($material = $materials->fetch_assoc()): ?>
                            <?php
                            // Get progress for this material
                            $progress_query = $conn->query("SELECT status FROM student_progress WHERE student_id = $student_id AND material_id = {$material['id']}");
                            $progress = $progress_query->fetch_assoc();
                            $status = $progress['status'] ?? 'not_started';
                            ?>
                            <div class="material-card">
                                <div class="material-type-badge type-<?php echo $material['type']; ?>">
                                    <?php echo ucfirst($material['type']); ?>
                                </div>
                                <h3><?php echo htmlspecialchars($material['title']); ?></h3>
                                <p><?php echo htmlspecialchars($material['description']); ?></p>
                                <div class="material-meta">
                                    <span class="lecturer-name">By: <?php echo htmlspecialchars($material['lecturer_name']); ?></span>
                                    <span class="upload-date"><?php echo date('M d, Y', strtotime($material['uploaded_at'])); ?></span>
                                </div>
                                
                                <div class="progress-section">
                                    <label>Progress:</label>
                                    <select class="progress-select" data-material-id="<?php echo $material['id']; ?>">
                                        <option value="not_started" <?php echo $status === 'not_started' ? 'selected' : ''; ?>>Not Started</option>
                                        <option value="in_progress" <?php echo $status === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>
                                
                                <?php if ($material['file_path']): ?>
                                    <?php
                                    $is_url = filter_var($material['file_path'], FILTER_VALIDATE_URL);
                                    $file_ext = pathinfo($material['file_path'], PATHINFO_EXTENSION);
                                    $file_icon = '';
                                    
                                    if ($is_url) {
                                        $file_icon = '🔗';
                                        $link_text = 'Watch/Open Video';
                                    } elseif (in_array(strtolower($file_ext), ['pdf'])) {
                                        $file_icon = '📄';
                                        $link_text = 'View PDF';
                                    } elseif (in_array(strtolower($file_ext), ['doc', 'docx'])) {
                                        $file_icon = '📝';
                                        $link_text = 'Download Document';
                                    } elseif (in_array(strtolower($file_ext), ['ppt', 'pptx'])) {
                                        $file_icon = '📊';
                                        $link_text = 'Download Presentation';
                                    } elseif (in_array(strtolower($file_ext), ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'])) {
                                        $file_icon = '🎥';
                                        $link_text = 'Watch Video';
                                    } else {
                                        $file_icon = '📎';
                                        $link_text = 'Download Material';
                                    }
                                    ?>
                                    <a href="<?php echo $is_url ? htmlspecialchars($material['file_path']) : '../uploads/' . $material['type'] . '/' . htmlspecialchars($material['file_path']); ?>" 
                                       class="btn btn-primary btn-block mt-2" 
                                       <?php echo $is_url ? 'target="_blank"' : 'download'; ?>>
                                       <?php echo $file_icon . ' ' . $link_text; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No materials available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <!-- AI Chat Section -->
    <div id="aiChatContainer" class="ai-chat-container">
        <div class="ai-chat-header" onclick="document.getElementById('aiChatContainer').classList.toggle('minimized')">
            <h3><span class="ai-icon">🤖</span> AI Study Assistant</h3>
            <button id="aiChatToggle" class="ai-chat-toggle">−</button>
        </div>
        <div class="ai-chat-messages" id="aiChatMessages">
            <div class="ai-chat-placeholder">
                <div class="ai-icon">🤖</div>
                <p>Ask me anything about your studies!</p>
            </div>
        </div>
        <div class="ai-chat-input-container">
            <input type="text" id="aiChatInput" class="ai-chat-input" placeholder="Type your question...">
            <button id="aiChatSend" class="ai-chat-send">➤</button>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script src="../assets/js/student.js"></script>
    <script src="../assets/js/ai-chat.js"></script>
</body>
</html>

