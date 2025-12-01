# Smart Tutor - Educational Management System

A comprehensive web-based educational platform built with HTML, CSS, JavaScript (Frontend) and PHP (Backend) for managing lectures, homework, videos, and tracking student progress.

## Features

### Common Features
- **Unified Login System**: Single login page for Students, Parents, and Lecturers
- **Role-based Access Control**: Separate dashboards for each user type
- **Professional UI**: Modern design with advanced CSS hover effects and animations
- **Responsive Design**: Works on desktop, tablet, and mobile devices

### Lecturer Features
- Upload lectures, homework, and videos
- Manage uploaded materials
- View all uploaded content in organized sections

### Student Features
- View all uploaded materials (lectures, homework, videos)
- Track progress on each material (Not Started, In Progress, Completed)
- Download materials
- Separate pages for each material type

### Parent Features
- View student progress and statistics
- Multi-language support (English, Hindi, Spanish, French)
- Working calculator with advanced functions
- Formula charts for Mathematics, Physics, and Chemistry
- Progress visualization with charts

### Home Page Features
- Advertisement section
- Exam news section (JEE, NEET, UPSC, etc.)

## Installation & Setup

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Edge, etc.)

### Setup Instructions

1. **Install XAMPP**
   - Download and install XAMPP from https://www.apachefriends.org/
   - Make sure Apache and MySQL services are running

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file to create the database and tables
   - The database will be created with sample data

3. **Project Setup**
   - Copy the entire project folder to `C:\xampp\htdocs\smart tutor\`
   - Or place it in your XAMPP htdocs directory

4. **File Permissions**
   - Ensure the `uploads` folder has write permissions
   - Create the following folders if they don't exist:
     - `uploads/lecture/`
     - `uploads/homework/`
     - `uploads/video/`

5. **Access the Application**
   - Open your browser and navigate to: `http://localhost/smart tutor/`
   - Or: `http://localhost/smart%20tutor/`

## Default Login Credentials

### Lecturer
- **Username**: `lecturer1`
- **Password**: `password`

### Student
- **Username**: `student1`
- **Password**: `password`

### Parent
- **Username**: `parent1`
- **Password**: `password`

## Project Structure

```
smart tutor/
├── assets/
│   ├── css/
│   │   ├── style.css          # Main stylesheet
│   │   ├── login.css          # Login page styles
│   │   ├── dashboard.css      # Dashboard styles
│   │   ├── calculator.css     # Calculator styles
│   │   └── formulas.css       # Formulas page styles
│   └── js/
│       ├── main.js            # Main JavaScript
│       ├── student.js         # Student dashboard JS
│       ├── parent.js          # Parent dashboard JS (multi-language)
│       └── calculator.js      # Calculator functionality
├── config/
│   ├── database.php           # Database configuration
│   └── session.php            # Session management
├── lecturer/
│   ├── dashboard.php          # Lecturer dashboard
│   ├── upload_lecture.php     # Upload lecture page
│   ├── upload_homework.php    # Upload homework page
│   └── upload_video.php       # Upload video page
├── student/
│   ├── dashboard.php          # Student dashboard
│   ├── lectures.php           # View lectures
│   ├── homework.php           # View homework
│   └── videos.php             # View videos
├── parent/
│   ├── dashboard.php          # Parent dashboard
│   ├── progress.php           # Detailed progress
│   ├── calculator.php         # Calculator tool
│   └── formulas.php           # Formula charts
├── uploads/                   # Uploaded files directory
│   ├── lecture/
│   ├── homework/
│   └── video/
├── database.sql               # Database schema
├── index.php                  # Login page & home
├── logout.php                 # Logout handler
└── README.md                  # This file
```

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Charts**: Chart.js (for progress visualization)
- **Server**: Apache (XAMPP)

## Features in Detail

### Multi-Language Support (Parent Portal)
- English (Default)
- Hindi (हिंदी)
- Spanish (Español)
- French (Français)

### Calculator Features
- Basic arithmetic operations (+, -, ×, ÷)
- Percentage calculations
- Decimal support
- Clear and Clear Entry functions

### Formula Charts
- **Mathematics**: Quadratic formula, Pythagorean theorem, Area/Volume formulas, etc.
- **Physics**: Newton's laws, Energy formulas, Ohm's law, etc.
- **Chemistry**: Molarity, Ideal gas law, Density, pH, etc.

## Security Notes

- In production, use proper password hashing (password_hash/password_verify)
- Implement CSRF protection
- Validate and sanitize all user inputs
- Use prepared statements (already implemented)
- Set proper file upload restrictions
- Implement proper session security

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Edge (latest)
- Safari (latest)

## Troubleshooting

### Database Connection Error
- Check if MySQL service is running in XAMPP
- Verify database credentials in `config/database.php`
- Ensure database `smart_tutor` exists

### File Upload Not Working
- Check `uploads` folder permissions
- Ensure PHP upload settings allow file uploads
- Check `php.ini` for `upload_max_filesize` and `post_max_size`

### Session Issues
- Ensure cookies are enabled in browser
- Check PHP session configuration
- Clear browser cache and cookies

## Future Enhancements

- Email notifications
- Real-time chat system
- Video streaming integration
- Assignment submission by students
- Grade management system
- Advanced analytics and reports
- Mobile app version

## License

This project is created for educational purposes.

## Support

For issues or questions, please check the code comments or refer to the documentation.

---

**Developed with ❤️ for Smart Education**