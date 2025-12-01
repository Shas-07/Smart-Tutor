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
    <title>Calculator - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/calculator.css">
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
                    <li><a href="calculator.php" class="active">Calculator</a></li>
                    <li><a href="formulas.php">Formulas</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Calculator</h1>
            </div>
            
            <div class="card calculator-container">
                <div class="calculator">
                    <div class="calculator-display">
                        <input type="text" id="display" readonly value="0">
                    </div>
                    <div class="calculator-buttons">
                        <button class="btn-calc btn-clear" onclick="clearDisplay()">C</button>
                        <button class="btn-calc btn-clear" onclick="clearEntry()">CE</button>
                        <button class="btn-calc btn-operator" onclick="appendOperator('/')">/</button>
                        <button class="btn-calc btn-operator" onclick="appendOperator('*')">×</button>
                        
                        <button class="btn-calc btn-number" onclick="appendNumber('7')">7</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('8')">8</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('9')">9</button>
                        <button class="btn-calc btn-operator" onclick="appendOperator('-')">-</button>
                        
                        <button class="btn-calc btn-number" onclick="appendNumber('4')">4</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('5')">5</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('6')">6</button>
                        <button class="btn-calc btn-operator" onclick="appendOperator('+')">+</button>
                        
                        <button class="btn-calc btn-number" onclick="appendNumber('1')">1</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('2')">2</button>
                        <button class="btn-calc btn-number" onclick="appendNumber('3')">3</button>
                        <button class="btn-calc btn-equals" onclick="calculate()" rowspan="2">=</button>
                        
                        <button class="btn-calc btn-number btn-zero" onclick="appendNumber('0')">0</button>
                        <button class="btn-calc btn-number" onclick="appendDecimal()">.</button>
                        <button class="btn-calc btn-operator" onclick="appendOperator('%')">%</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script src="../assets/js/calculator.js"></script>
</body>
</html>

