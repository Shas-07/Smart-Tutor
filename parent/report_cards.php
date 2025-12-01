<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('parent');

$conn = getDBConnection();
$parent_id = $_SESSION['user_id'];

// Get student linked to this parent
$student_query = $conn->query("SELECT s.id, s.student_id, u.full_name 
                                FROM students s 
                                JOIN users u ON s.user_id = u.id 
                                WHERE s.parent_id = $parent_id");
$student = $student_query->fetch_assoc();
$student_db_id = $student['id'] ?? null;

// Get report cards
$report_cards = [];
if ($student_db_id) {
    $reports_query = $conn->query("SELECT rc.*, u.full_name as lecturer_name 
                                    FROM report_cards rc 
                                    JOIN users u ON rc.lecturer_id = u.id 
                                    WHERE rc.student_id = $student_db_id 
                                    ORDER BY rc.test_date DESC, rc.uploaded_at DESC");
    while ($report = $reports_query->fetch_assoc()) {
        $report_cards[] = $report;
    }
}

// Calculate statistics
$total_tests = count($report_cards);
$avg_score = 0;
if ($total_tests > 0) {
    $total_percentage = 0;
    foreach ($report_cards as $report) {
        if ($report['max_score'] > 0) {
            $total_percentage += ($report['score'] / $report['max_score']) * 100;
        }
    }
    $avg_score = round($total_percentage / $total_tests, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Cards - Smart Tutor</title>
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
                    <li><a href="progress.php">Student Progress</a></li>
                    <li><a href="report_cards.php" class="active">Report Cards</a></li>
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
                <h1 data-translate="report_cards">Report Cards & Test Results</h1>
            </div>
            
            <?php if ($student): ?>
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card stat-total">
                        <h3>Total Tests</h3>
                        <div class="stat-number"><?php echo $total_tests; ?></div>
                        <div class="stat-subtitle">Tests Taken</div>
                    </div>
                    <div class="stat-card stat-completed">
                        <h3>Average Score</h3>
                        <div class="stat-number"><?php echo $avg_score; ?>%</div>
                        <div class="stat-subtitle">Overall Performance</div>
                    </div>
                </div>
                
                <!-- Report Cards List -->
                <div class="card">
                    <h2>Test Results & Report Cards</h2>
                    <?php if (count($report_cards) > 0): ?>
                        <div class="report-cards-list">
                            <?php foreach ($report_cards as $report): ?>
                                <?php
                                $percentage = $report['max_score'] > 0 ? round(($report['score'] / $report['max_score']) * 100, 2) : 0;
                                $score_class = $percentage >= 90 ? 'excellent' : ($percentage >= 75 ? 'good' : ($percentage >= 60 ? 'average' : 'needs-improvement'));
                                ?>
                                <div class="report-card-item">
                                    <div class="report-header">
                                        <div>
                                            <h3><?php echo htmlspecialchars($report['test_name']); ?></h3>
                                            <p class="report-subject">📚 <?php echo htmlspecialchars($report['subject']); ?></p>
                                            <p class="report-date">📅 <?php echo date('F d, Y', strtotime($report['test_date'])); ?></p>
                                        </div>
                                        <div class="report-score score-<?php echo $score_class; ?>">
                                            <div class="score-percentage"><?php echo $percentage; ?>%</div>
                                            <div class="score-fraction"><?php echo $report['score']; ?> / <?php echo $report['max_score']; ?></div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($report['grade']): ?>
                                        <div class="report-grade">
                                            <strong>Grade:</strong> <?php echo htmlspecialchars($report['grade']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($report['remarks']): ?>
                                        <div class="report-remarks">
                                            <strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($report['remarks'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="report-meta">
                                        <span>📝 By: <?php echo htmlspecialchars($report['lecturer_name']); ?></span>
                                        <?php if ($report['file_path']): ?>
                                            <a href="../uploads/report_cards/<?php echo htmlspecialchars($report['file_path']); ?>" 
                                               class="btn btn-secondary btn-sm" target="_blank">View Report Card</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No report cards available yet.</p>
                            <p class="empty-hint">Test results will appear here once lecturers upload them.</p>
                        </div>
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

