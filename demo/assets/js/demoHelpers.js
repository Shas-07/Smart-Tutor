// Demo Helper Functions

// Generate sidebar navigation based on role
function generateSidebar(role, activePage) {
    const navs = {
        student: [
            { href: 'dashboard.html', text: 'My Materials' },
            { href: 'lectures.html', text: 'Lectures' },
            { href: 'homework.html', text: 'Homework' },
            { href: 'videos.html', text: 'Videos' },
            { href: 'calculator.html', text: 'Calculator' },
            { href: 'formulas.html', text: 'Formulas' },
            { href: 'periodic_table.html', text: 'Periodic Table' },
            { href: 'work_log.html', text: 'Work Log' },
            { href: 'report_cards.html', text: 'Report Cards' }
        ],
        lecturer: [
            { href: 'dashboard.html', text: 'Dashboard' },
            { href: 'upload_lecture.html', text: 'Upload Lecture' },
            { href: 'upload_homework.html', text: 'Upload Homework' },
            { href: 'upload_video.html', text: 'Upload Video' },
            { href: 'upload_report_card.html', text: 'Upload Report Card' }
        ],
        parent: [
            { href: 'dashboard.html', text: 'Dashboard' },
            { href: 'progress.html', text: 'Student Progress' },
            { href: 'report_cards.html', text: 'Report Cards' }
        ]
    };
    
    const roleNames = {
        student: 'Student Portal',
        lecturer: 'Lecturer Portal',
        parent: 'Parent Portal'
    };
    
    let html = `
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Smart Tutor</h2>
                <p>${roleNames[role]}</p>
            </div>
            <nav>
                <ul class="sidebar-nav">
    `;
    
    navs[role].forEach(item => {
        const active = item.href === activePage ? ' class="active"' : '';
        html += `<li><a href="${item.href}"${active}>${item.text}</a></li>`;
    });
    
    html += `
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="#" onclick="demoAuth.logout(); window.location.href='../index.html';">Logout</a></li>
                </ul>
            </nav>
        </aside>
    `;
    
    return html;
}

// Load news ticker
function loadNewsTicker() {
    const container = document.getElementById('newsTickerContainer');
    if (container) {
        fetch('../includes/news_ticker.html')
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(err => console.error('Error loading ticker:', err));
    }
}

// Check authentication and redirect if needed
function checkAuth(requiredRole) {
    if (!demoAuth.checkRole(requiredRole)) {
        window.location.href = '../index.html';
        return false;
    }
    return true;
}

// Format date
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    });
}

// Get file icon and text
function getFileInfo(filePath) {
    const isUrl = filePath.startsWith('http');
    const fileExt = filePath.split('.').pop().toLowerCase();
    
    if (isUrl) {
        return { icon: '🔗', text: 'Watch/Open Video' };
    } else if (fileExt === 'pdf') {
        return { icon: '📄', text: 'View PDF' };
    } else if (['doc', 'docx'].includes(fileExt)) {
        return { icon: '📝', text: 'Download Document' };
    } else if (['ppt', 'pptx'].includes(fileExt)) {
        return { icon: '📊', text: 'Download Presentation' };
    } else if (['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'].includes(fileExt)) {
        return { icon: '🎥', text: 'Watch Video' };
    } else {
        return { icon: '📎', text: 'Download Material' };
    }
}

