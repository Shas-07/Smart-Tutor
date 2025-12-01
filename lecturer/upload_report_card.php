<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('lecturer');

$conn = getDBConnection();
$lecturer_id = $_SESSION['user_id'];

// Get all students
$students = $conn->query("SELECT s.id, s.student_id, u.full_name, u.username 
                          FROM students s 
                          JOIN users u ON s.user_id = u.id 
                          ORDER BY u.full_name");

// Handle report card upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_report'])) {
    $student_id = $_POST['student_id'];
    $test_name = $_POST['test_name'];
    $test_date = $_POST['test_date'];
    $subject = $_POST['subject'];
    $score = $_POST['score'];
    $max_score = $_POST['max_score'];
    $grade = $_POST['grade'];
    $remarks = $_POST['remarks'];
    
    $upload_dir = '../uploads/report_cards/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['file']['name']));
            $target_path = $upload_dir . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
        }
    }
    
    // Calculate percentage
    $percentage = $max_score > 0 ? round(($score / $max_score) * 100, 2) : 0;
    
    $stmt = $conn->prepare("INSERT INTO report_cards (student_id, lecturer_id, test_name, test_date, subject, score, max_score, grade, remarks, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssddsss", $student_id, $lecturer_id, $test_name, $test_date, $subject, $score, $max_score, $grade, $remarks, $file_name);
    
    if ($stmt->execute()) {
        $success = "Report card uploaded successfully!";
    } else {
        $error = "Error uploading report card.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Report Card - Smart Tutor</title>
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
                    <li><a href="upload_video.php">Upload Video</a></li>
                    <li><a href="upload_report_card.php" class="active">Upload Report Card</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Upload Report Card / Test Results</h1>
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
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" required class="form-control">
                            <option value="">Select Student</option>
                            <?php if ($students && $students->num_rows > 0): ?>
                                <?php while ($student = $students->fetch_assoc()): ?>
                                    <option value="<?php echo $student['id']; ?>">
                                        <?php echo htmlspecialchars($student['full_name']); ?> (<?php echo htmlspecialchars($student['student_id']); ?>)
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="test_name">Test/Exam Name</label>
                        <input type="text" name="test_name" id="test_name" required class="form-control" placeholder="e.g., Mid-Term Exam, Unit Test 1">
                    </div>
                    
                    <div class="form-group">
                        <label for="test_date">Test Date</label>
                        <input type="date" name="test_date" id="test_date" required class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" required class="form-control" placeholder="e.g., Mathematics, Physics">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="score">Score Obtained</label>
                            <input type="number" name="score" id="score" step="0.01" min="0" required class="form-control" placeholder="e.g., 85">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_score">Maximum Score</label>
                            <input type="number" name="max_score" id="max_score" step="0.01" min="0" required class="form-control" placeholder="e.g., 100">
                        </div>
                        
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <input type="text" name="grade" id="grade" class="form-control" placeholder="e.g., A, B+, 95%">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Teacher's comments or remarks"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Report Card File (Optional)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small>Supported formats: PDF, JPG, PNG, DOC, DOCX</small>
                    </div>
                    
                    <button type="submit" name="upload_report" class="btn btn-primary">Upload Report Card</button>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

