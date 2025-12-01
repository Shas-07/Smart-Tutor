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

// Handle work log entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_log'])) {
    $date = $_POST['date'];
    $subject = $_POST['subject'];
    $topic = $_POST['topic'];
    $hours = $_POST['hours_studied'];
    $notes = $_POST['notes'];
    
    if ($student_id) {
        $stmt = $conn->prepare("INSERT INTO work_logs (student_id, date, subject, topic, hours_studied, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $student_id, $date, $subject, $topic, $hours, $notes);
        
        if ($stmt->execute()) {
            $success = "Work log added successfully!";
        } else {
            $error = "Error adding work log.";
        }
        $stmt->close();
    }
}

// Get work logs
$work_logs = [];
if ($student_id) {
    $logs_query = $conn->query("SELECT * FROM work_logs WHERE student_id = $student_id ORDER BY date DESC, created_at DESC LIMIT 50");
    while ($log = $logs_query->fetch_assoc()) {
        $work_logs[] = $log;
    }
}

// Calculate statistics
$total_hours = 0;
$subjects = [];
foreach ($work_logs as $log) {
    $total_hours += floatval($log['hours_studied']);
    if (!isset($subjects[$log['subject']])) {
        $subjects[$log['subject']] = 0;
    }
    $subjects[$log['subject']] += floatval($log['hours_studied']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Log - Smart Tutor</title>
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
                    <li><a href="videos.php">Videos</a></li>
                    <li><a href="calculator.php">Calculator</a></li>
                    <li><a href="formulas.php">Formulas</a></li>
                    <li><a href="periodic_table.php">Periodic Table</a></li>
                    <li><a href="work_log.php" class="active">Work Log</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Study Work Log</h1>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card stat-total">
                    <h3>Total Hours</h3>
                    <div class="stat-number"><?php echo number_format($total_hours, 1); ?></div>
                    <div class="stat-subtitle">Hours Studied</div>
                </div>
                <div class="stat-card stat-progress">
                    <h3>Total Entries</h3>
                    <div class="stat-number"><?php echo count($work_logs); ?></div>
                    <div class="stat-subtitle">Log Entries</div>
                </div>
                <div class="stat-card stat-completed">
                    <h3>Subjects</h3>
                    <div class="stat-number"><?php echo count($subjects); ?></div>
                    <div class="stat-subtitle">Different Subjects</div>
                </div>
            </div>
            
            <!-- Add Work Log Form -->
            <div class="card">
                <h2>Add Study Entry</h2>
                <form method="POST" class="upload-form">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" required class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" required class="form-control" placeholder="e.g., Mathematics, Physics">
                    </div>
                    
                    <div class="form-group">
                        <label for="topic">Topic</label>
                        <input type="text" name="topic" id="topic" class="form-control" placeholder="e.g., Algebra, Newton's Laws">
                    </div>
                    
                    <div class="form-group">
                        <label for="hours_studied">Hours Studied</label>
                        <input type="number" name="hours_studied" id="hours_studied" step="0.5" min="0" required class="form-control" placeholder="e.g., 2.5">
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="What did you study? Any important points?"></textarea>
                    </div>
                    
                    <button type="submit" name="add_log" class="btn btn-primary">Add Entry</button>
                </form>
            </div>
            
            <!-- Work Logs List -->
            <div class="card">
                <h2>Study History</h2>
                <?php if (count($work_logs) > 0): ?>
                    <div class="work-logs-list">
                        <?php foreach ($work_logs as $log): ?>
                            <div class="work-log-item">
                                <div class="log-header">
                                    <h4><?php echo htmlspecialchars($log['subject']); ?></h4>
                                    <span class="log-date"><?php echo date('M d, Y', strtotime($log['date'])); ?></span>
                                </div>
                                <?php if ($log['topic']): ?>
                                    <p class="log-topic"><strong>Topic:</strong> <?php echo htmlspecialchars($log['topic']); ?></p>
                                <?php endif; ?>
                                <div class="log-meta">
                                    <span class="log-hours">⏱️ <?php echo $log['hours_studied']; ?> hours</span>
                                </div>
                                <?php if ($log['notes']): ?>
                                    <p class="log-notes"><?php echo nl2br(htmlspecialchars($log['notes'])); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No work logs yet. Start tracking your study time!</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

