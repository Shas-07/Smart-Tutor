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

// Get student progress with detailed information
$progress_data = [];
if ($student_db_id) {
    $progress_query = $conn->query("SELECT sp.*, m.title, m.type, m.description, u.full_name as lecturer_name
                                     FROM student_progress sp 
                                     JOIN materials m ON sp.material_id = m.id 
                                     JOIN users u ON m.lecturer_id = u.id
                                     WHERE sp.student_id = $student_db_id
                                     ORDER BY sp.completion_date DESC");
    while ($row = $progress_query->fetch_assoc()) {
        $progress_data[] = $row;
    }
}

// Calculate comprehensive statistics
$total_materials = $conn->query("SELECT COUNT(*) as total FROM materials")->fetch_assoc()['total'];
$completed = count(array_filter($progress_data, function($p) { return $p['status'] === 'completed'; }));
$in_progress = count(array_filter($progress_data, function($p) { return $p['status'] === 'in_progress'; }));
$not_started = $total_materials - $completed - $in_progress;
$completion_rate = $total_materials > 0 ? round(($completed / $total_materials) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/parent.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="progress.php">Student Progress</a></li>
                    <li><a href="report_cards.php">Report Cards</a></li>
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
                <h1 data-translate="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
                <div class="user-info">
                    <span data-translate="parent">Parent</span>
                </div>
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
                            <span class="detail-label">Email Address:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Account Created:</span>
                            <span class="detail-value"><?php echo date('F d, Y', strtotime($student['account_created'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card stat-completed">
                        <h3 data-translate="completed">Completed</h3>
                        <div class="stat-number"><?php echo $completed; ?></div>
                        <div class="stat-subtitle"><?php echo $total_materials > 0 ? round(($completed / $total_materials) * 100, 1) : 0; ?>%</div>
                    </div>
                    <div class="stat-card stat-progress">
                        <h3 data-translate="in_progress">In Progress</h3>
                        <div class="stat-number"><?php echo $in_progress; ?></div>
                        <div class="stat-subtitle">Active Learning</div>
                    </div>
                    <div class="stat-card stat-pending">
                        <h3 data-translate="not_started">Not Started</h3>
                        <div class="stat-number"><?php echo $not_started; ?></div>
                        <div class="stat-subtitle">Pending</div>
                    </div>
                    <div class="stat-card stat-total">
                        <h3 data-translate="total_materials">Total Materials</h3>
                        <div class="stat-number"><?php echo $total_materials; ?></div>
                        <div class="stat-subtitle">Available</div>
                    </div>
                    <div class="stat-card stat-rate">
                        <h3>Completion Rate</h3>
                        <div class="stat-number"><?php echo $completion_rate; ?>%</div>
                        <div class="stat-subtitle">Overall Progress</div>
                    </div>
                </div>
                
                <div class="card">
                    <h2 data-translate="progress_chart">Progress Chart</h2>
                    <canvas id="progressChart" width="400" height="200"></canvas>
                </div>
                
                <div class="card">
                    <h2 data-translate="detailed_progress">📊 Recent Progress</h2>
                    <div class="progress-list">
                        <?php if (count($progress_data) > 0): ?>
                            <?php foreach (array_slice($progress_data, 0, 5) as $progress): ?>
                                <div class="progress-item">
                                    <div class="progress-item-main">
                                        <div class="progress-header">
                                            <h4><?php echo htmlspecialchars($progress['title']); ?></h4>
                                            <span class="material-type-badge type-<?php echo $progress['type']; ?>">
                                                <?php echo ucfirst($progress['type']); ?>
                                            </span>
                                        </div>
                                        <?php if ($progress['description']): ?>
                                            <p class="progress-description"><?php echo htmlspecialchars(substr($progress['description'], 0, 100)) . (strlen($progress['description']) > 100 ? '...' : ''); ?></p>
                                        <?php endif; ?>
                                        <div class="progress-meta">
                                            <span class="meta-item">📝 By: <?php echo htmlspecialchars($progress['lecturer_name']); ?></span>
                                        </div>
                                    </div>
                                    <div class="progress-status">
                                        <span class="progress-badge status-<?php echo $progress['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $progress['status'])); ?>
                                        </span>
                                        <?php if ($progress['score']): ?>
                                            <p class="score">Score: <?php echo $progress['score']; ?>%</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($progress_data) > 5): ?>
                                <div class="view-more">
                                    <a href="progress.php" class="btn btn-secondary">View All Progress →</a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <p data-translate="no_progress">No progress data available yet.</p>
                                <p class="empty-hint">Student progress will appear here once they start working on materials.</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
    <script>
        // Progress Chart
        const ctx = document.getElementById('progressChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Not Started'],
                    datasets: [{
                        data: [<?php echo $completed; ?>, <?php echo $in_progress; ?>, <?php echo $not_started; ?>],
                        backgroundColor: ['#10b981', '#f59e0b', '#6b7280']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    </script>
</body>
</html>

