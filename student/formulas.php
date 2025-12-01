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
    <title>Formulas - Smart Tutor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ticker.css">
    <link rel="stylesheet" href="../assets/css/formulas.css">
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
                    <li><a href="formulas.php" class="active">Formulas</a></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <h1>Formulas & Charts</h1>
            </div>
            
            <div class="formulas-container">
                <!-- Mathematics Formulas -->
                <div class="card formula-section">
                    <h2>Mathematics</h2>
                    <div class="formula-grid">
                        <div class="formula-card">
                            <h3>Quadratic Formula</h3>
                            <div class="formula-display">x = (-b ± √(b² - 4ac)) / 2a</div>
                            <p>Used to solve quadratic equations</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Pythagorean Theorem</h3>
                            <div class="formula-display">a² + b² = c²</div>
                            <p>For right-angled triangles</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Area of Circle</h3>
                            <div class="formula-display">A = πr²</div>
                            <p>r = radius</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Volume of Sphere</h3>
                            <div class="formula-display">V = (4/3)πr³</div>
                            <p>r = radius</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Distance Formula</h3>
                            <div class="formula-display">d = √[(x₂-x₁)² + (y₂-y₁)²]</div>
                            <p>Distance between two points</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Slope Formula</h3>
                            <div class="formula-display">m = (y₂-y₁)/(x₂-x₁)</div>
                            <p>Slope of a line</p>
                        </div>
                    </div>
                </div>
                
                <!-- Physics Formulas -->
                <div class="card formula-section">
                    <h2>Physics</h2>
                    <div class="formula-grid">
                        <div class="formula-card">
                            <h3>Newton's Second Law</h3>
                            <div class="formula-display">F = ma</div>
                            <p>Force = mass × acceleration</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Kinetic Energy</h3>
                            <div class="formula-display">KE = (1/2)mv²</div>
                            <p>m = mass, v = velocity</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Potential Energy</h3>
                            <div class="formula-display">PE = mgh</div>
                            <p>m = mass, g = gravity, h = height</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Ohm's Law</h3>
                            <div class="formula-display">V = IR</div>
                            <p>Voltage = Current × Resistance</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Speed</h3>
                            <div class="formula-display">v = d/t</div>
                            <p>velocity = distance / time</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Power</h3>
                            <div class="formula-display">P = VI</div>
                            <p>Power = Voltage × Current</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chemistry Formulas -->
                <div class="card formula-section">
                    <h2>Chemistry</h2>
                    <div class="formula-grid">
                        <div class="formula-card">
                            <h3>Molarity</h3>
                            <div class="formula-display">M = n/V</div>
                            <p>Moles per liter</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Ideal Gas Law</h3>
                            <div class="formula-display">PV = nRT</div>
                            <p>P = pressure, V = volume, n = moles, R = gas constant, T = temperature</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>Density</h3>
                            <div class="formula-display">ρ = m/V</div>
                            <p>Density = mass / volume</p>
                        </div>
                        
                        <div class="formula-card">
                            <h3>pH Formula</h3>
                            <div class="formula-display">pH = -log[H⁺]</div>
                            <p>Acidity measure</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>

