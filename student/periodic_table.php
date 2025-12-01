<?php
require_once '../config/database.php';
require_once '../config/session.php';

checkRole('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periodic Table - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/periodic_table.css">
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
                    <li><a href="periodic_table.php" class="active">Periodic Table</a></li>
                    <li><a href="work_log.php">Work Log</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Periodic Table of Elements</h1>
            </div>
            
            <div class="card">
                <div class="periodic-table-container">
                    <div id="periodicTable" class="periodic-table"></div>
                </div>
                <div class="element-info" id="elementInfo">
                    <p>Click on an element to view details</p>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script src="../assets/js/periodic_table.js"></script>
</body>
</html>

