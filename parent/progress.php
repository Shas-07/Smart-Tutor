<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('parent');

$conn = getDBConnection();
$parent_id = $_SESSION['user_id'];

// Get comprehensive student information
$student_query = $conn->query("SELECT s.id, s.student_id, u.full_name, u.email, u.created_at as account_created
                                FROM students s 
                                JOIN users u ON s.user_id = u.id 
                                WHERE s.parent_id = $parent_id");
$student = $student_query->fetch_assoc();
$student_db_id = $student['id'] ?? null;

// Get all materials available
$all_materials = $conn->query("SELECT COUNT(*) as total FROM materials")->fetch_assoc()['total'];

// Get student progress with detailed information
$progress_data = [];
$all_progress = [];
if ($student_db_id) {
    // Get progress for materials student has interacted with
    $progress_query = $conn->query("SELECT sp.*, m.title, m.type, m.description, m.uploaded_at as material_date, u.full_name as lecturer_name
                                     FROM student_progress sp 
                                     JOIN materials m ON sp.material_id = m.id 
                                     JOIN users u ON m.lecturer_id = u.id
                                     WHERE sp.student_id = $student_db_id
                                     ORDER BY sp.completion_date DESC, sp.id DESC");
    while ($row = $progress_query->fetch_assoc()) {
        $progress_data[] = $row;
        $all_progress[] = $row;
    }
    
    // Get all materials to show what's available
    $materials_query = $conn->query("SELECT m.*, u.full_name as lecturer_name 
                                      FROM materials m 
                                      JOIN users u ON m.lecturer_id = u.id 
                                      ORDER BY m.uploaded_at DESC");
    $all_materials_list = [];
    while ($mat = $materials_query->fetch_assoc()) {
        $all_materials_list[] = $mat;
    }
}

// Calculate comprehensive statistics
$completed = count(array_filter($progress_data, function($p) { return $p['status'] === 'completed'; }));
$in_progress = count(array_filter($progress_data, function($p) { return $p['status'] === 'in_progress'; }));
$not_started = $all_materials - $completed - $in_progress;
$completion_rate = $all_materials > 0 ? round(($completed / $all_materials) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/parent.css">
</head>
<body>
    <?php include '../includes/news_ticker.php'; ?>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Smart Tutor</h2>
                <p>Parent Portal</p>
            </div>
            <nav>
                <ul class="sidebar-nav">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="progress.php" class="active">Student Progress</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="language-selector">
                <label>Language:</label>
                <select id="languageSelect" class="form-control">
                    <option value="en">English</option>
                    <option value="hi">हिंदी (Hindi)</option>
                    <option value="es">Español (Spanish)</option>
                    <option value="fr">Français (French)</option>
                </select>
            </div>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1 data-translate="detailed_progress">Detailed Progress</h1>
            </div>
            
            <?php if ($student): ?>
                <!-- Comprehensive Student Information Card -->
                <div class="card student-info-card">
                    <h2 data-translate="student_info">📚 Student Information</h2>
                    <div class="student-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Full Name:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($student['full_name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Student ID:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($student['student_id']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Account Created:</span>
                            <span class="detail-value"><?php echo date('F d, Y', strtotime($student['account_created'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Overview -->
                <div class="stats-grid">
                    <div class="stat-card stat-completed">
                        <h3>Completed</h3>
                        <div class="stat-number"><?php echo $completed; ?></div>
                        <div class="stat-subtitle"><?php echo $all_materials > 0 ? round(($completed / $all_materials) * 100, 1) : 0; ?>%</div>
                    </div>
                    <div class="stat-card stat-progress">
                        <h3>In Progress</h3>
                        <div class="stat-number"><?php echo $in_progress; ?></div>
                        <div class="stat-subtitle">Active Learning</div>
                    </div>
                    <div class="stat-card stat-pending">
                        <h3>Not Started</h3>
                        <div class="stat-number"><?php echo $not_started; ?></div>
                        <div class="stat-subtitle">Pending</div>
                    </div>
                    <div class="stat-card stat-total">
                        <h3>Total Materials</h3>
                        <div class="stat-number"><?php echo $all_materials; ?></div>
                        <div class="stat-subtitle">Available</div>
                    </div>
                    <div class="stat-card stat-rate">
                        <h3>Completion Rate</h3>
                        <div class="stat-number"><?php echo $completion_rate; ?>%</div>
                        <div class="stat-subtitle">Overall Progress</div>
                    </div>
                </div>
                
                <!-- Detailed Progress -->
                <div class="card">
                    <h2 data-translate="progress_details">📊 Detailed Progress Report</h2>
                    <?php if (count($progress_data) > 0): ?>
                        <div class="progress-list">
                            <?php foreach ($progress_data as $progress): ?>
                                <div class="progress-item">
                                    <div class="progress-item-main">
                                        <div class="progress-header">
                                            <h4><?php echo htmlspecialchars($progress['title']); ?></h4>
                                            <span class="material-type-badge type-<?php echo $progress['type']; ?>">
                                                <?php echo ucfirst($progress['type']); ?>
                                            </span>
                                        </div>
                                        <?php if ($progress['description']): ?>
                                            <p class="progress-description"><?php echo htmlspecialchars($progress['description']); ?></p>
                                        <?php endif; ?>
                                        <div class="progress-meta">
                                            <span class="meta-item">📝 Lecturer: <?php echo htmlspecialchars($progress['lecturer_name']); ?></span>
                                            <span class="meta-item">📅 Uploaded: <?php echo date('M d, Y', strtotime($progress['material_date'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="progress-status">
                                        <span class="progress-badge status-<?php echo $progress['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $progress['status'])); ?>
                                        </span>
                                        <?php if ($progress['completion_date']): ?>
                                            <p class="completion-date">✅ Completed: <?php echo date('M d, Y', strtotime($progress['completion_date'])); ?></p>
                                        <?php endif; ?>
                                        <?php if ($progress['score']): ?>
                                            <p class="score">🎯 Score: <?php echo $progress['score']; ?>%</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p data-translate="no_progress">No progress data available yet.</p>
                            <p class="empty-hint">Student progress will appear here once they start working on materials.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- All Available Materials -->
                <div class="card">
                    <h2>📚 All Available Materials</h2>
                    <?php if (count($all_materials_list) > 0): ?>
                        <div class="materials-overview">
                            <?php 
                            $materials_by_type = ['lecture' => 0, 'homework' => 0, 'video' => 0];
                            foreach ($all_materials_list as $mat) {
                                $materials_by_type[$mat['type']] = ($materials_by_type[$mat['type']] ?? 0) + 1;
                            }
                            ?>
                            <div class="materials-summary">
                                <div class="summary-item">
                                    <span class="summary-icon">📄</span>
                                    <span class="summary-label">Lectures:</span>
                                    <span class="summary-value"><?php echo $materials_by_type['lecture']; ?></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-icon">📝</span>
                                    <span class="summary-label">Homework:</span>
                                    <span class="summary-value"><?php echo $materials_by_type['homework']; ?></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-icon">🎥</span>
                                    <span class="summary-label">Videos:</span>
                                    <span class="summary-value"><?php echo $materials_by_type['video']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No materials available yet.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <p data-translate="no_student">No student linked to this parent account.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script src="../assets/js/parent.js"></script>
</body>
</html>

