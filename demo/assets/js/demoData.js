// Demo Data - Mock database for demo purposes
const demoData = {
    users: [
        {
            id: 1,
            username: 'student1',
            password: 'password',
            role: 'student',
            full_name: 'John Student',
            email: 'student1@example.com'
        },
        {
            id: 2,
            username: 'parent1',
            password: 'password',
            role: 'parent',
            full_name: 'Jane Parent',
            email: 'parent1@example.com'
        },
        {
            id: 3,
            username: 'lecturer1',
            password: 'password',
            role: 'lecturer',
            full_name: 'Dr. Smith Lecturer',
            email: 'lecturer1@example.com'
        }
    ],
    
    materials: [
        {
            id: 1,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            title: 'Introduction to Mathematics',
            description: 'Basic concepts of algebra and geometry',
            type: 'lecture',
            file_path: 'sample.pdf',
            uploaded_at: '2024-01-15'
        },
        {
            id: 2,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            title: 'Physics Homework 1',
            description: 'Complete exercises on Newton\'s laws',
            type: 'homework',
            file_path: 'homework1.pdf',
            uploaded_at: '2024-01-20'
        },
        {
            id: 3,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            title: 'Chemistry Video Tutorial',
            description: 'Understanding periodic table',
            type: 'video',
            file_path: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            uploaded_at: '2024-01-25'
        },
        {
            id: 4,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            title: 'Advanced Calculus',
            description: 'Differential equations and integrals',
            type: 'lecture',
            file_path: 'calculus.pdf',
            uploaded_at: '2024-02-01'
        },
        {
            id: 5,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            title: 'Biology Assignment',
            description: 'Cell structure and function',
            type: 'homework',
            file_path: 'biology_assignment.pdf',
            uploaded_at: '2024-02-05'
        }
    ],
    
    studentProgress: [
        { student_id: 1, material_id: 1, status: 'completed' },
        { student_id: 1, material_id: 2, status: 'in_progress' },
        { student_id: 1, material_id: 3, status: 'not_started' }
    ],
    
    reportCards: [
        {
            id: 1,
            student_id: 1,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            test_name: 'Midterm Exam',
            test_date: '2024-01-30',
            subject: 'Mathematics',
            score: 85,
            max_score: 100,
            percentage: 85,
            grade: 'A',
            remarks: 'Excellent performance! Keep up the good work.',
            file_path: null
        },
        {
            id: 2,
            student_id: 1,
            lecturer_id: 3,
            lecturer_name: 'Dr. Smith Lecturer',
            test_name: 'Physics Quiz',
            test_date: '2024-02-10',
            subject: 'Physics',
            score: 78,
            max_score: 100,
            percentage: 78,
            grade: 'B+',
            remarks: 'Good understanding of concepts.',
            file_path: null
        }
    ],
    
    workLogs: [
        {
            id: 1,
            student_id: 1,
            date: '2024-02-15',
            subject: 'Mathematics',
            topic: 'Calculus',
            hours: 2.5,
            notes: 'Reviewed differential equations'
        },
        {
            id: 2,
            student_id: 1,
            date: '2024-02-16',
            subject: 'Physics',
            topic: 'Mechanics',
            hours: 3,
            notes: 'Solved practice problems'
        }
    ],
    
    ads: [
        { id: 1, title: '🎓 New Course Available: Advanced Physics', link_url: '#' },
        { id: 2, title: '📚 Study Materials Sale - 50% Off', link_url: '#' },
        { id: 3, title: '🏆 Scholarship Applications Open', link_url: '#' }
    ],
    
    news: [
        { id: 1, title: 'JEE Main 2024 Registration Open', exam_type: 'JEE' },
        { id: 2, title: 'NEET 2024 Exam Dates Announced', exam_type: 'NEET' },
        { id: 3, title: 'UPSC Prelims 2024 Schedule Released', exam_type: 'UPSC' }
    ]
};

// Demo Authentication System
const demoAuth = {
    currentUser: null,
    
    login(username, password, role) {
        const user = demoData.users.find(u => 
            u.username === username && 
            u.password === password && 
            u.role === role
        );
        
        if (user) {
            this.currentUser = { ...user };
            delete this.currentUser.password;
            localStorage.setItem('demoUser', JSON.stringify(this.currentUser));
            return true;
        }
        return false;
    },
    
    logout() {
        this.currentUser = null;
        localStorage.removeItem('demoUser');
    },
    
    getCurrentUser() {
        if (!this.currentUser) {
            const stored = localStorage.getItem('demoUser');
            if (stored) {
                this.currentUser = JSON.parse(stored);
            }
        }
        return this.currentUser;
    },
    
    isAuthenticated() {
        return this.getCurrentUser() !== null;
    },
    
    checkRole(requiredRole) {
        const user = this.getCurrentUser();
        return user && user.role === requiredRole;
    }
};

// Demo Data Access Functions
const demoDB = {
    getMaterials() {
        return demoData.materials;
    },
    
    getMaterialById(id) {
        return demoData.materials.find(m => m.id === parseInt(id));
    },
    
    getMaterialsByType(type) {
        return demoData.materials.filter(m => m.type === type);
    },
    
    getStudentProgress(studentId) {
        return demoData.studentProgress.filter(p => p.student_id === studentId);
    },
    
    updateProgress(studentId, materialId, status) {
        const index = demoData.studentProgress.findIndex(
            p => p.student_id === studentId && p.material_id === materialId
        );
        
        if (index >= 0) {
            demoData.studentProgress[index].status = status;
        } else {
            demoData.studentProgress.push({
                student_id: studentId,
                material_id: materialId,
                status: status
            });
        }
        
        // Save to localStorage
        localStorage.setItem('demoProgress', JSON.stringify(demoData.studentProgress));
    },
    
    getProgress(studentId, materialId) {
        const progress = demoData.studentProgress.find(
            p => p.student_id === studentId && p.material_id === materialId
        );
        return progress ? progress.status : 'not_started';
    },
    
    getReportCards(studentId) {
        return demoData.reportCards.filter(r => r.student_id === studentId);
    },
    
    getWorkLogs(studentId) {
        return demoData.workLogs.filter(w => w.student_id === studentId);
    },
    
    addWorkLog(studentId, logData) {
        const newLog = {
            id: demoData.workLogs.length + 1,
            student_id: studentId,
            ...logData
        };
        demoData.workLogs.push(newLog);
        localStorage.setItem('demoWorkLogs', JSON.stringify(demoData.workLogs));
        return newLog;
    },
    
    getAds() {
        return demoData.ads;
    },
    
    getNews() {
        return demoData.news;
    }
};

// Load saved data from localStorage
function loadSavedData() {
    const savedProgress = localStorage.getItem('demoProgress');
    if (savedProgress) {
        demoData.studentProgress = JSON.parse(savedProgress);
    }
    
    const savedWorkLogs = localStorage.getItem('demoWorkLogs');
    if (savedWorkLogs) {
        demoData.workLogs = JSON.parse(savedWorkLogs);
    }
}

// Initialize on load
loadSavedData();

