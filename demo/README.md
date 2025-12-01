# Smart Tutor - Demo Version

This is a **static demo version** of Smart Tutor that works without any backend server. Perfect for Vercel deployment!

## 🚀 Features

- **No Backend Required**: Pure HTML, CSS, and JavaScript
- **Demo Login System**: Works with mock data stored in browser localStorage
- **Full UI/UX**: Exactly the same as the original project
- **Vercel Ready**: Configured for static deployment

## 📋 Demo Credentials

### Student
- **Username**: `student1`
- **Password**: `password`

### Parent
- **Username**: `parent1`
- **Password**: `password`

### Lecturer
- **Username**: `lecturer1`
- **Password**: `password`

## 🏗️ Project Structure

```
demo/
├── assets/              # CSS and JavaScript files
│   ├── css/
│   └── js/
│       ├── demoData.js      # Mock data and authentication
│       └── demoHelpers.js   # Helper functions
├── includes/            # Reusable components
│   └── news_ticker.html
├── student/             # Student portal pages
├── lecturer/            # Lecturer portal pages
├── parent/              # Parent portal pages
├── index.html           # Login page
└── vercel.json          # Vercel configuration
```

## 🚀 Local Development

1. **Open in Browser**:
   - Simply open `index.html` in your web browser
   - Or use a local server:
     ```bash
     # Python
     python -m http.server 8000
     
     # Node.js
     npx serve
     
     # PHP
     php -S localhost:8000
     ```

2. **Access**:
   - Navigate to `http://localhost:8000`

## 🌐 Vercel Deployment

### ⚠️ IMPORTANT: Root Directory Configuration

When importing from Git, **you MUST set the Root Directory to `demo`** in Vercel project settings!

### Method 1: GitHub Integration (Recommended)

1. **Push your code to GitHub** (including the `demo` folder)
2. Go to [Vercel Dashboard](https://vercel.com)
3. Click **"New Project"**
4. **Import your GitHub repository**
5. **CRITICAL**: In project settings, set **"Root Directory"** to `demo`
   - Click "Edit" next to "Root Directory"
   - Enter: `demo`
   - Click "Continue"
6. **Framework Preset**: Select "Other" or leave as default
7. **Build Command**: Leave empty
8. **Output Directory**: Leave empty
9. Click **"Deploy"**

### Method 2: Vercel CLI

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Navigate to demo folder
cd demo

# Deploy
vercel

# Follow prompts
# When asked about root directory, make sure you're in the demo folder
```

### Method 3: Drag & Drop

1. Go to [Vercel Dashboard](https://vercel.com)
2. Click **"New Project"**
3. Drag and drop the **`demo` folder** (not the parent folder)
4. Deploy!

### Method 4: Separate Repository (Cleanest)

Create a separate repository just for the demo:

```bash
# Create new repo and push only demo folder
cd demo
git init
git add .
git commit -m "Initial demo"
git remote add origin https://github.com/yourusername/smart-tutor-demo.git
git push -u origin main
```

Then import this new repository in Vercel - no root directory configuration needed!

### Troubleshooting: Can't Find Demo Folder

**Problem**: Vercel shows the main project, not the demo folder.

**Solution**: 
1. Go to your project in Vercel Dashboard
2. Go to **Settings** → **General**
3. Find **"Root Directory"** section
4. Click **"Edit"**
5. Enter: `demo`
6. Click **"Save"**
7. Redeploy your project

See `VERCEL_SETUP.md` for detailed instructions.

## 📝 How It Works

### Authentication
- Uses `localStorage` to store user session
- Mock user data in `demoData.js`
- No server-side validation (demo only)

### Data Storage
- Mock data stored in `demoData.js`
- Progress updates saved to `localStorage`
- Data persists across page refreshes

### File Uploads
- Simulated (no actual file uploads)
- Enter URLs or filenames in demo mode
- Files are stored in memory only

## ⚠️ Limitations (Demo Mode)

- **No Real File Uploads**: File uploads are simulated
- **No Database**: All data is stored in browser localStorage
- **No Server-Side Validation**: All validation is client-side
- **Data Resets**: Clearing browser data will reset all progress

## 🎯 Pages Included

### Student Portal
- ✅ Dashboard (My Materials)
- ✅ Lectures
- ✅ Homework
- ✅ Videos
- ✅ Calculator
- ✅ Formulas
- ✅ Periodic Table
- ✅ Work Log
- ✅ Report Cards

### Lecturer Portal
- ✅ Dashboard
- ✅ Upload Lecture
- ✅ Upload Homework
- ✅ Upload Video
- ✅ Upload Report Card

### Parent Portal
- ✅ Dashboard
- ✅ Student Progress
- ✅ Report Cards

## 🔧 Customization

### Adding More Demo Data

Edit `assets/js/demoData.js`:

```javascript
materials: [
    {
        id: 6,
        lecturer_id: 3,
        lecturer_name: 'Dr. Smith Lecturer',
        title: 'Your New Material',
        description: 'Description here',
        type: 'lecture',
        file_path: 'new-material.pdf',
        uploaded_at: '2024-02-20'
    }
]
```

### Changing Demo Credentials

Edit `assets/js/demoData.js`:

```javascript
users: [
    {
        id: 1,
        username: 'your_username',
        password: 'your_password',
        role: 'student',
        full_name: 'Your Name',
        email: 'your@email.com'
    }
]
```

## 📚 Documentation

For full project documentation, see the main `README.md` in the parent directory.

## 🆘 Troubleshooting

### Login Not Working
- Make sure you're using the exact demo credentials
- Check browser console for errors
- Clear browser cache and localStorage

### Pages Not Loading
- Check that all files are in the correct directories
- Verify file paths are relative (not absolute)
- Check browser console for 404 errors

### Vercel Deployment Issues
- Ensure `vercel.json` is in the `demo` folder
- Check that all file paths are correct
- Verify no PHP files are included

## 📄 License

Same as the main project - for educational purposes.

---

**Enjoy the Demo! 🎉**

